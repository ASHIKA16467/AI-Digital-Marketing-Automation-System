<?php
session_start();
require_once 'db.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$db = new MySqlDB();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?status=please_login");
    exit();
}

$username = $_SESSION['user_name'] ?? 'Guest';

// Handle form submission
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
            if (!$db->isEmailExistsInGroup($groupId, $email)) {
                $db->saveContactToGroup($groupId, $name, $email, $phone, $remark);
            }
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
                    if (!$db->isEmailExistsInGroup($groupId, $email)) {
                        $db->saveContactToGroup($groupId, $name, $email, $phone, $remark);
                    }
                }
            }
        } catch (Exception $e) {
            die('❌ Failed to process Excel file: ' . $e->getMessage());
        }
    }

    header("Location: email_marketing.php?selected_group={$groupId}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Email Group | AI Digital Marketing</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Header Start -->
<header>
    <div class="logo">
        <img src="logo.png" alt="Logo"> <!-- Replace with your logo path -->
        <span>Brand Name</span>
    </div>
    <nav class="nav-container">
        <li><a href="index.php">Home</a></li>
        <li class="dropdown">
            <a href="#">Features ▼</a>
            <ul class="dropdown-menu">
                <li><a href="ai_gener_content.php">AI-Generated Content</a></li>
                <li><a href="seo_optimization.php">SEO Optimization</a></li>
                <li><a href="social_media_management.php">Social Media Management</a></li>
                <li><a href="email.php">Email Marketing Automation</a></li>
                <li><a href="ads.php">Paid Ads Management</a></li>
                <li><a href="lead.php">Lead Generation</a></li>
                <li><a href="analytics_reporting.php">Analytics & Reporting</a></li>
            </ul>
        </li>
        <li><a href="aboutus.php">About Us</a></li>
        <li><a href="#">Contact</a></li>
    </nav>

    <div class="user-dropdown">
        <button onclick="toggleDropdown()" class="dropbtn">
            👤 <?php echo htmlspecialchars($username); ?> ▼
        </button>
        <div id="userDropdown" class="dropdown-content">
            <a href="profile.php">Profile</a>
            <a href="companies.php">Companies</a>
            <a href="credentials.php">🔑 Credentials</a>
            <a href="#" onclick="confirmLogout()">Logout</a>
        </div>
    </div>
</header>
<!-- Header End -->

<main class="container mt-4">
    <h3>Create New Email Group</h3>
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
</main>

<script>
    function toggleMode(mode) {
        document.getElementById('manualFields').style.display = mode === 'manual' ? 'block' : 'none';
        document.getElementById('excelFields').style.display = mode === 'excel' ? 'block' : 'none';
    }

    function toggleDropdown() {
        document.getElementById("userDropdown").classList.toggle("show");
    }

    window.onclick = function (event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    function confirmLogout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "../backend/logout.php";
        }
    }
</script>


<footer>
    <p>&copy; <?= date('Y') ?> Brand Name. All Rights Reserved.</p>
</footer>

</body>
</html>
