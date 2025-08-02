<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
    <title>Change Password</title>
    <style>
        .container {
            width: 400px;
            margin: 80px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .buttons button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .buttons button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
<header>
        <div class="logo">
            <img src="logo.png" alt="Logo"> <!-- Replace with your logo path -->
            <span>Brand Name</span>
        </div>
        
        <nav>
            <ul class="nav-container">
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
            </ul>
        </nav>
        
    </header>

<div class="container">
    <h2>Change Password</h2>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div id="message" class="message <?= $_SESSION['flash_type'] ?>">
            <?= $_SESSION['flash_message'] ?>
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById("message");
                if (msg) msg.style.display = "none";
            }, 3000);
        </script>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <form method="POST" action="../backend/update_password.php">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>

        <div class="buttons">
            <button type="submit">Reset Password</button>
            <button type="button" onclick="window.location.href='profile.php'">Back</button>
        </div>
    </form>
</div>

</body>
</html>
