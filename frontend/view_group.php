<?php
session_start();
require_once 'db.php';
$db = new MySqlDB();

// Set username for header
$username = $_SESSION['full_name'] ?? 'Guest';

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?status=please_login");
    exit();
}
$username = $_SESSION['user_name'] ?? 'Guest';
$groupId = $_GET['group_id'] ?? '';

if (!$groupId) {
    echo "<p>Group ID is missing. <a href='email_marketing.php'>Back</a></p>";
    exit;
}

$contacts = $db->getContactsByGroupId($groupId);

// Remove duplicates by email
$uniqueContacts = [];
foreach ($contacts as $contact) {
    $email = strtolower(trim($contact['email']));
    if (!isset($uniqueContacts[$email])) {
        $uniqueContacts[$email] = $contact;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Group | AI Digital Marketing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .main-container {
            padding: 30px;
        }
        /* .user-dropdown {
            position: relative;
        }
        .dropbtn {
            background-color: transparent;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 1;
        }
        .dropdown-content a {
            padding: 12px 16px;
            display: block;
            text-decoration: none;
            color: black;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        } */
        .show {
            display: block;
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
        nav.nav-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        nav.nav-container li {
            list-style: none;
            margin: 0 10px;
        }
        nav.nav-container a {
            color: white;
            text-decoration: none;
        }
        /* .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #333;
            padding: 10px;
            list-style-type: none;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        } */
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
        <?php if (!isset($_SESSION["user_id"])) { ?>
            <a href="login.php" class="login-btn">Login</a>
        <?php } else { ?>
            <button onclick="toggleDropdown()" class="dropbtn">
                👤 <?php echo htmlspecialchars($username); ?> ▼
            </button>
            <div id="userDropdown" class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="companies.php">Companies</a>
                <a href="credentials.php">🔑 Credentials</a>
                <a href="#" onclick="confirmLogout()">Logout</a>
            </div>
        <?php } ?>
    </div>
</header>

<main class="main-container">
    <h2 class="mb-4">Group Contacts</h2>

    <div class="d-flex justify-content-between mb-3">
        <a href="create_group.php?group_id=<?= urlencode($groupId) ?>" class="btn btn-success">➕ Add Member</a>
        <!-- <a href="create_group.php" class="btn btn-info">➕ Create New Group</a> -->
    </div>

    <?php if (count($uniqueContacts) > 0): ?>
        <form action="email_marketing.php" method="POST">
            <input type="hidden" name="group_id" value="<?= htmlspecialchars($groupId) ?>">
            <table class="table table-bordered bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>Select</th>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($uniqueContacts as $contact): ?>
                        <tr>
                            <td><input type="checkbox" name="selected_emails[]" value="<?= htmlspecialchars($contact['email']) ?>"></td>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($contact['name']) ?></td>
                            <td><?= htmlspecialchars($contact['email']) ?></td>
                            <td><?= htmlspecialchars($contact['phone']) ?></td>
                            <td><?= htmlspecialchars($contact['remark']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-3">
                <a href="email_marketing.php" class="btn btn-secondary">⬅️ Back</a>
                <button type="submit" class="btn btn-primary">Next ➡️</button>
            </div>
        </form>
    <?php else: ?>
        <p>No contacts found in this group.</p>
        <a href="email_marketing.php" class="btn btn-secondary mt-3">⬅️ Back</a>
    <?php endif; ?>
</main>

<script>
    function toggleDropdown() {
        document.getElementById("userDropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            const dropdowns = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < dropdowns.length; i++) {
                if (dropdowns[i].classList.contains('show')) {
                    dropdowns[i].classList.remove('show');
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

<footer class="text-center p-3 bg-light mt-4">
    <p>&copy; <?= date('Y') ?> Brand Name. All Rights Reserved.</p>
</footer>

</body>
</html>
