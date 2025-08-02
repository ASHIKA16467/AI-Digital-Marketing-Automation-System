<?php
session_start();
$username = $_SESSION['user_name'] ?? 'Guest';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Load SMTP config
$config = parse_ini_file("C:/wamp64/private/ai_config.ini", true);
$smtpEmail = $config['mailer']['email'];
$smtpPassword = $config['mailer']['password'];
$smtpHost = $config['mailer']['host'];
$smtpPort = $config['mailer']['port'];

// Initialize message
$message = "";
$messageClass = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $senderEmail = htmlspecialchars($_POST["email"]);
    $description = htmlspecialchars($_POST["description"]);

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpEmail;
        $mail->Password = $smtpPassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $smtpPort;

        // Recipients
        $mail->setFrom($smtpEmail, 'AI Marketing Automation Contact');
        $mail->addAddress($smtpEmail, 'Support Team');

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Message from $name";
        $mail->Body = "
            <strong>Name:</strong> $name<br>
            <strong>Phone:</strong> $phone<br>
            <strong>Email:</strong> $senderEmail<br>
            <strong>Message:</strong><br>$description
        ";

        $mail->send();
        $message = "✅ Your message has been sent successfully!";
        $messageClass = "success";
    } catch (Exception $e) {
        $message = "❌ Message could not be sent. Error: {$mail->ErrorInfo}";
        $messageClass = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | AI Digital Marketing</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .contact-container {
        max-width: 800px;
        margin: 50px auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .contact-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .contact-info {
        margin-bottom: 30px;
    }
    .contact-info p {
        margin: 5px 0;
    }
    .contact-form input, .contact-form textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .contact-form button {
        background: #1abc9c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }
    .contact-form button:hover {
        background: #16a085;
    }
    .success {
        background: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 15px;
    }
    .error {
        background: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 15px;
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

<div class="contact-container">
  <h2>Contact Us</h2>

  <?php if ($message): ?>
      <div class="<?php echo $messageClass; ?>" id="messageBox">
          <?php echo $message; ?>
      </div>
  <?php endif; ?>

  <div class="contact-info">
      <p><strong>Address:</strong> 123 AI Marketing Street, Tech City</p>
      <p><strong>Email:</strong> support@yourcompany.com</p>
      <p><strong>Phone:</strong> +1 234 567 890</p>
  </div>

  <form method="POST" class="contact-form">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="tel" name="phone" placeholder="Phone Number" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <textarea name="description" placeholder="Your Message" rows="5" required></textarea>
      <button type="submit">Send Message</button>
  </form>
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

// Auto-hide success/error message after 3 seconds
document.addEventListener("DOMContentLoaded", function() {
  const msg = document.getElementById("messageBox");
  if (msg) {
      setTimeout(() => {
          msg.style.display = "none";
      }, 3000);
  }
});
</script>
</body>
</html>
