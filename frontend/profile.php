<?php
session_start();
if (!isset($_SESSION["full_name"])) {
    $username = "Guest"; // Default if not logged in
} else {
    $username = $_SESSION["full_name"];
}

if (!isset($_SESSION["user_email"])) {
    header("Location: login.html");
    exit();
}
$username = $_SESSION['user_name'] ?? 'Guest';
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .profile-container {
            width: 400px;
            margin: 60px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }

        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
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

        .hidden {
            display: none;
        }
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
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
        <!-- <div class="user-dropdown">
            <?php if (!isset($_SESSION["user_id"])) { ?>
                <a href="login.html" class="login-btn">Login</a>
            <?php } else { ?>
                <button onclick="toggleDropdown()" class="dropbtn">
                    👤 <?php echo htmlspecialchars($username); ?> ▼
                </button>
                <div id="userDropdown" class="dropdown-content">
                    <a href="company_profile.php">Profile</a>
                    <a href="companies.php">Companies</a>
                    <a href="credentials.php">🔑 Credentials</a>
                    <a href="#" onclick="confirmLogout()">Logout</a>
                </div> -->
            <?php } ?>
        </div>
    </header>

<div class="profile-container">
    <h2>My Profile</h2>
    <form id="profileForm" method="POST" action="../backend/save_profile.php">
        <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" readonly>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" readonly>
        <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>" readonly pattern="[0-9]{10}" maxlength="10" title="Enter a 10-digit phone number">
        <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($_SESSION['user_location'] ?? ''); ?>" readonly>

        <div class="buttons">
            <button type="button" id="editBtn" onclick="enableEdit()">Edit</button>
            <button type="submit" id="saveBtn" class="hidden">Save</button>
            <button type="button" id="cancelBtn" class="hidden" onclick="cancelEdit()">Cancel</button>
            <button type="button" onclick="window.location.href='index.php'">Back</button>
            <button type="button" onclick="window.location.href='change_password.php'">Change Password</button>

        </div>
    </form>
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
    <p style="color: green; text-align:center;">Profile updated successfully!</p>
<?php endif; ?>

</div>

<script>
    function enableEdit() {
        document.getElementById("full_name").readOnly = false;
        document.getElementById("phone").readOnly = false;
        document.getElementById("location").readOnly = false;

        document.getElementById("editBtn").classList.add("hidden");
        document.getElementById("saveBtn").classList.remove("hidden");
        document.getElementById("cancelBtn").classList.remove("hidden");
    }

    // Function to allow only numbers in phone field
    document.addEventListener('DOMContentLoaded', () => {
        const phoneInput = document.getElementById('phone');

        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });


    function cancelEdit() {
        // Reload to restore original data
        window.location.reload();
    }
</script>

</body>
</html>
