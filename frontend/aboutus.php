<?php
session_start();
$username = $_SESSION['user_name'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us - AI Digital Marketing</title>
  <link rel="stylesheet" href="styles.css"/>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #fff;
      color: #333;
    }
    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: rgb(13, 13, 13);
      color: white;
      padding: 10px 20px;
      flex-wrap: wrap;
      position: relative;
    }
    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .logo img {
      height: 40px;
      vertical-align: middle;
    }
    nav {
      flex: 1;
    }
    .nav-container {
      display: flex;
      list-style: none;
      gap: 15px;
      justify-content: center;
      margin: 0;
      padding: 0;
      flex-wrap: wrap;
    }
    .nav-container li {
      position: relative;
    }
    .nav-container a {
      text-decoration: none;
      color: white;
      padding: 10px;
      display: block;
    }
    /* .dropdown-menu {
      display: none;
      position: absolute;
      background: #333;
      min-width: 200px;
      z-index: 10;
      flex-direction: column;
    }
    .dropdown-menu li a {
      padding: 10px;
      color: white;
    }
    .dropdown:hover .dropdown-menu {
      display: flex;
    }
    .user-dropdown {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .dropbtn {
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      font-size: 16px;
    }
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      right: 0;
      min-width: 160px;
      z-index: 1;
    }
    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }
    .dropdown-content a:hover {
      background-color: #ddd;
    } */
    .show {
      display: block;
    }
    .hamburger {
      display: none;
      font-size: 24px;
      cursor: pointer;
      background: none;
      border: none;
      color: white;
    }
    @media screen and (max-width: 768px) {
      .hamburger {
        display: block;
        margin-left: auto;
      }
      nav {
        width: 100%;
      }
      .nav-container {
        display: none;
        flex-direction: column;
        background-color: #333;
        width: 100%;
      }
      .nav-container.show {
        display: flex;
      }
      .user-dropdown {
        width: 100%;
        justify-content: center;
        margin-top: 10px;
      }
    }

    .section {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 50px 20px;
      flex-wrap: wrap;
      border-bottom: 1px solid #ddd;
    }
    .section:nth-child(even) {
      background: #f9f9f9;
    }
    .section-content, .section-image {
      flex: 1;
      min-width: 300px;
      padding: 20px;
    }
    .section-content h2 {
      color: #004080;
    }
    .section-image img {
      width: 100%;
      border-radius: 8px;
    }
    footer {
      background-color: rgb(13, 13, 13);
      color: white;
      text-align: center;
      padding: 20px;
    }
  </style>
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
</head>
<body>

<!-- Header -->
<header>
  <div class="logo">
    <img src="logo.png" alt="Logo">
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

<!-- About Us Sections -->
<div class="section">
  <div class="section-content">
    <h2>Our Story</h2>
    <p>Founded in 2025, our mission is to revolutionize digital marketing with AI. From content generation to smart analytics, our platform makes marketing simple, effective, and data-driven.</p>
  </div>
  <div class="section-image">
    <img src="images/about1.jpg" alt="Our Story">
  </div>
</div>

<div class="section">
  <div class="section-image">
    <img src="images/about2.jpg" alt="Our Vision">
  </div>
  <div class="section-content">
    <h2>Our Vision</h2>
    <p>We envision a world where every business, big or small, can leverage artificial intelligence to connect with customers in meaningful ways and grow sustainably.</p>
  </div>
</div>

<div class="section">
  <div class="section-content">
    <h2>Our Team</h2>
    <p>Our diverse team of developers, marketers, and AI researchers are passionate about building tools that empower your success. We believe in innovation, transparency, and results.</p>
  </div>
  <div class="section-image">
    <img src="images/about3.jpg" alt="Our Team">
  </div>
</div>

<!-- Footer -->
<footer>
  <p>&copy; 2025 AI Digital Marketing. All rights reserved.</p>
</footer>

</body>
</html>
