<?php
session_start();
$username = $_SESSION['user_name'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AI-Generated Content | AI Digital Marketing</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .coming-soon {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 60vh;
      font-size: 2rem;
      font-weight: bold;
      color: #333;
      text-align: center;
    }
  </style>
</head>
<body>
<header>
  <div class="logo">
    <img src="logo.png" alt="Logo">
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
          <li><a href="email_marketing.php">Email Marketing Automation</a></li>
          <li><a href="ads.php">Paid Ads Management</a></li>
          <li><a href="lead.php">Lead Generation</a></li>
          <li><a href="analytics_reporting.php">Analytics & Reporting</a></li>
        </ul>
      </li>
      <li><a href="aboutus.php">About Us</a></li>
      <li><a href="contactus.php">Contact</a></li>
    </ul>
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

<div class="coming-soon">
   This feature is coming soon. Stay tuned!
</div>

<footer>
  <p>&copy; 2025 AI Digital Marketing. All rights reserved.</p>
</footer>

<script>
function toggleMenu() {
  document.querySelector(".nav-container").classList.toggle("show");
}
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
</body>
</html>
