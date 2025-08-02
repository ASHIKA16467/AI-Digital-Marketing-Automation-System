<?php
session_start();
require_once 'db.php';
$db = new MySqlDB();
$groups = $db->getAllGroups();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?status=please_login");
    exit();
}
$username = $_SESSION['user_name'] ?? 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Marketing Campaign</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            background-color: #222;
            color: white;
            padding: 10px 20px;
            box-sizing: border-box;
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .logo span {
            font-size: 20px;
            font-weight: bold;
        }

        .nav-container {
            display: flex;
            gap: 15px;
            list-style: none;
        }

        .nav-container li {
            list-style: none;
        }

        .nav-container a {
            color: white;
            text-decoration: none;
        }

        /* .user-dropdown {
            position: relative;
        }

        .dropbtn {
            background-color: #222;
            color: white;
            border: none;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            right: 0;
            min-width: 150px;
            z-index: 1;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        .dropdown-content a {
            color: #333;
            padding: 10px;
            display: block;
            text-decoration: none;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        } */

        .show {
            display: block;
        }

        main {
            max-width: 1200px;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
        }

        footer {
            background-color: #222;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .btn-sm {
            padding: 3px 8px;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="logo.png" alt="Logo">
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

<main>
    <h2 class="mb-4">Email Marketing Campaign</h2>

    <form id="emailForm" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Select Group (for Database Contacts):</label>
            <div class="input-group">
                <select name="group_id" id="group_id" class="form-select">
                    <option value="">-- Select Existing Group --</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= htmlspecialchars($group['id']) ?>"><?= htmlspecialchars($group['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <a href="create_group.php" class="btn btn-sm btn-outline-primary">➕ Create New Group</a>
                <button type="button" class="btn btn-sm btn-info ms-2" onclick="viewGroup()">👁️ View Group</button>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">OR Upload Excel File:</label>
            <input type="file" name="excel_file" accept=".xlsx, .xls" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Select Row Range (for Excel):</label>
            <div class="d-flex">
                <input type="number" name="row_start" class="form-control me-2" placeholder="Start Row (e.g., 2)">
                <input type="number" name="row_end" class="form-control" placeholder="End Row (e.g., 100)">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Subject:</label>
            <input type="text" name="subject" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">About / Keyword:</label>
            <textarea name="about" class="form-control" rows="3" required></textarea>
            <button type="button" class="btn btn-secondary mt-2" onclick="generateEmail()">Generate Email</button>
        </div>

        <div class="mb-3">
            <label class="form-label">Generated Email Content:</label>
            <textarea name="generated_email" id="generated_email" class="form-control" rows="10" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">From Email Address:</label>
            <input type="email" name="fromEmail" class="form-control" required>
        </div>

        <div class="mb-4">
            <button type="submit" class="btn btn-primary">📤 Send Email</button>
        </div>

        <div id="emailStatus" class="alert d-none" role="alert"></div>
    </form>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> Brand Name. All Rights Reserved.</p>
</footer>

<script>
    function toggleDropdown() {
        document.getElementById("userDropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (let dropdown of dropdowns) {
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    }

    function confirmLogout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "../backend/logout.php";
        }
    }

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

    function viewGroup() {
        const groupId = document.getElementById('group_id').value;
        if (!groupId) {
            alert('Please select a group to view.');
            return;
        }
        window.location.href = 'view_group.php?group_id=' + encodeURIComponent(groupId);
    }
</script>

</body>
</html>
