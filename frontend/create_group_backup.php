<?php
require_once 'db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$db = new MySqlDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupName = trim($_POST['group_name'] ?? '');
    $inputMode = $_POST['inputMode'] ?? '';

    if ($groupName === '') {
        die('❌ Group name is required.');
    }

    // Create or get group ID
    $groupId = $db->getOrCreateGroupId($groupName);
    if (!$groupId) {
        die('❌ Failed to create or get group ID.');
    }

    if ($inputMode === 'manual') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $remark = $_POST['remark'] ?? '';

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $db->saveContactToGroup($groupId, $name, $email, $phone, $remark);
        }

    } elseif ($inputMode === 'excel' && isset($_FILES['excel_file']) && $_FILES['excel_file']['size'] > 0) {
        try {
            $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();

            foreach ($sheet->getRowIterator() as $i => $row) {
                if ($i === 1) continue; // Skip header row
                $cells = [];

                foreach ($row->getCellIterator() as $cell) {
                    $cells[] = $cell->getValue();
                }

                $email = $cells[1] ?? '';
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $name = $cells[0] ?? '';
                    $phone = $cells[2] ?? '';
                    $remark = $cells[3] ?? '';
                    $db->saveContactToGroup($groupId, $name, $email, $phone, $remark);
                }
            }
        } catch (Exception $e) {
            die('❌ Failed to process Excel file: ' . $e->getMessage());
        }
    }

    // Redirect back to email_marketing with selected group ID
    header("Location: email_marketing.php?selected_group={$groupId}");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Create New Group</h3>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Group Name:</label>
            <input type="text" name="group_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Choose Input Mode:</label><br>
            <input type="radio" name="inputMode" value="manual" checked onclick="toggleMode('manual')"> Manual
            <input type="radio" name="inputMode" value="excel" onclick="toggleMode('excel')"> Excel
        </div>

        <div id="manualFields">
            <label>Name:</label>
            <input type="text" name="name" class="form-control">
            <label>Email:</label>
            <input type="email" name="email" class="form-control">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control">
            <label>Remark:</label>
            <input type="text" name="remark" class="form-control">
        </div>

        <div id="excelFields" style="display: none;">
            <label>Upload Excel (.xlsx):</label>
            <input type="file" name="excel_file" class="form-control">
        </div>

        <button type="submit" class="btn btn-success mt-3">Save Group</button>
    </form>

    <script>
        function toggleMode(mode) {
            document.getElementById('manualFields').style.display = mode === 'manual' ? 'block' : 'none';
            document.getElementById('excelFields').style.display = mode === 'excel' ? 'block' : 'none';
        }
    </script>
</body>
</html>
