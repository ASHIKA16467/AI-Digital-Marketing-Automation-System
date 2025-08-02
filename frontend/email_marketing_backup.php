<?php
require_once 'db.php';
$db = new MySqlDB();
$groups = $db->getAllGroups();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Marketing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="mb-4">Email Marketing Campaign</h2>

    <form id="emailForm">
        <!-- Group Selection -->
        <div class="mb-3">
            <label class="form-label">Select Group:</label>
            <select name="group_id" id="group_id" class="form-select" required>
                <option value="">-- Select Existing Group --</option>
                <?php foreach ($groups as $group): ?>
                    <option value="<?= htmlspecialchars($group['id']) ?>">
                        <?= htmlspecialchars($group['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <a href="create_group.php" class="btn btn-sm btn-link mt-2">➕ Create New Group</a>
        </div>

        <!-- Subject -->
        <div class="mb-3">
            <label class="form-label">Subject:</label>
            <input type="text" name="subject" class="form-control" required>
        </div>

        <!-- About / Keyword -->
        <div class="mb-3">
            <label class="form-label">About / Keyword:</label>
            <textarea name="about" class="form-control" rows="3" required></textarea>
            <button type="button" class="btn btn-secondary mt-2" onclick="generateEmail()">Generate Email</button>
        </div>

        <!-- Generated Email -->
        <div class="mb-3">
            <label class="form-label">Generated Email Content:</label>
            <textarea name="generated_email" id="generated_email" class="form-control" rows="10" required></textarea>
        </div>

        <!-- From Email -->
        <div class="mb-3">
            <label class="form-label">From Email Address:</label>
            <input type="email" name="fromEmail" class="form-control" required>
        </div>

        <!-- Send Button -->
        <div class="mb-4">
            <button type="submit" class="btn btn-primary">📤 Send Email</button>
        </div>

        <!-- Status Message -->
        <div id="emailStatus" class="alert d-none" role="alert"></div>
    </form>

    <script>
        // Generate email content
        function generateEmail() {
            const subject = document.querySelector('[name="subject"]').value;
            const about = document.querySelector('[name="about"]').value;
            const data = new FormData();
            data.append('subject', subject);
            data.append('about', about);

            fetch('generate_email_content.php', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(result => {
                document.getElementById('generated_email').value = result.content;
            });
        }

        // Submit form via AJAX
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('send_email.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(result => {
                const alertBox = document.getElementById('emailStatus');
                alertBox.textContent = result.message;
                alertBox.className = `alert ${result.status === 'success' ? 'alert-success' : 'alert-danger'}`;
                alertBox.classList.remove('d-none');
                setTimeout(() => alertBox.classList.add('d-none'), 5000);
            });
        });
    </script>
</body>
</html>
