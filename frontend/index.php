<?php
session_start();
if (!isset($_SESSION["full_name"])) {
    $username = "Guest"; // Default if not logged in
} else {
    $username = $_SESSION["full_name"];
}
$username = $_SESSION['user_name'] ?? 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - AI Digital Marketing</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Success Message Box -->
    <div id="message-box" class="hidden"></div>

    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const messageBox = document.getElementById("message-box");

            if (status === "profile_saved") {
                messageBox.innerHTML = "✅ Profile saved successfully!";
                messageBox.classList.add("show-message"); // Show the message

                // Remove the message after 3 seconds
                setTimeout(() => {
                    messageBox.classList.remove("show-message");
                }, 3000);
            }
        };
    </script>
    
    <style>
        .hamburger {
            display: none;
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
            color: white;
        }
        .nav-container {
            display: flex;
            list-style: none;
            gap: 15px;
            margin-left: 20px;
        }
        .nav-container li {
            display: inline;
            
        }
        .nav-container a {
            text-decoration: none;
            color: white;
            padding: 10px;
        }
        
        @media screen and (max-width: 768px) {
            .hamburger {
                display: block;
            }
            .nav-container {
                display: none;
                flex-direction: column;
                background-color: #333;
                position: absolute;
                top: 60px;
                left: 0;
                width: 100%;
                text-align: center;
                padding: 10px 0;
            }
            .nav-container.show {
                display: flex;
            }
        }
        .fade-slide {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeSlideIn 1.5s ease-out forwards;
            font-size: 2.5rem;
            font-weight: bold;
            color: #2196F3;;
            }

            @keyframes fadeSlideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
}

    </style>
    
    <script>
        function toggleMenu() {
            document.querySelector(".nav-container").classList.toggle("show");
        }
    </script>
    
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo"> <!-- Replace with your logo path -->
            <span>Brand Name</span>
        </div>
        <button class="hamburger" onclick="toggleMenu()">☰</button>
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
        <div class="user-dropdown">
            <?php if (!isset($_SESSION["user_id"])) { ?>
                <a href="login.html" class="login-btn">Login</a>
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
    
    <script>
        function toggleDropdown() {
            document.getElementById("userDropdown").classList.toggle("show");
        }
        
        window.onclick = function(event) {
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

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
           <h1 class="fade-slide">The Ultimate AI Digital Marketing Solution</h1>

            <p>Automate content creation, SEO, social media, and more with AI-powered tools.</p>
            <div class="buttons">
                <a href="signup.html" class="btn primary">Get Started</a>
                <a href="#features" class="btn secondary">Learn More</a>
            </div>
        </div>
    </section>
    <section id="features">
        <h2>Key Features</h2>
        <div class="features-grid">
            <div class="feature"> <a href="ai_gener_content.php">AI-Generated Content</a></div>
            <div class="feature"><a href="seo_optimization.php">SEO Optimization</a></div>
            <div class="feature"><a href="social_media_management.php">Social Media Management</a></div>
            <div class="feature"><a href="email.php">Email Marketing Automation</a></div>
            <div class="feature"><a href="ads.php">Paid Ads Management</a></div>
            <div class="feature"><a href="lead.php">Lead Generation</a></div>
            <div class="feature"><a href="analytics_reporting.php">Analytics & Reporting</a></div>
        </div>
    </section>
    <!-- Footer -->
    <footer>
        <p>&copy; 2025 AI Digital Marketing. All rights reserved.</p>
    </footer>
</body>
</html>
