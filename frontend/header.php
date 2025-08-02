<header>
        <div class="logo">
            <img src="logo.png" alt="Logo"> <!-- Replace with your logo path -->
            <span>Brand Name</span>
        </div>
        <nav class="nav-container">
                <li><a href="#">Home</a></li>
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
            </li>
            </ul>
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
                    <a href="company_profile.php">Profile</a>
                    <a href="companies.php">Companies</a>
                    <a href="credentials.php">🔑 Credentials</a>
                    <a href="#" onclick="confirmLogout()">Logout</a>
                </div>
            <?php } ?>
        </div>
    </header>