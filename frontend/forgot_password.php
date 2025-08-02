<?php
$config = parse_ini_file('C:/wamp64/private/ai_config.ini', true);
$forgotAction = $config['urls']['forgot_password_action'] ?? '#';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | AI Marketing Automation</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Center the forgot password container */
        .forgot-container {
            width: 350px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 100px auto;
        }

        /* Form Inputs */
        .forgot-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Submit Button */
        .forgot-container button {
            width: 100%;
            padding: 10px;
            background: #1abc9c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .forgot-container button:hover {
            background: #16a085;
        }

        /* Message Box Styling */
        #message-box {
            display: none;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
    </header>

    <div class="forgot-container">
        <!-- Message Box (Initially Hidden) -->
        <div id="message-box"></div>
        
        <h2>Forgot Password</h2>
        <p>Enter your email or phone number to reset your password.</p>
        <form action="<?= $forgotAction ?>" method="POST">
            <input type="text" name="contact" placeholder="Email or Phone Number" required>
            <button type="submit">Send OTP</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>

    <footer>
        <p>&copy; <script>document.write(new Date().getFullYear());</script> AI Marketing Automation. All rights reserved.</p>
    </footer>
    
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const messageBox = document.getElementById("message-box");

            if (status === "success") {
                messageBox.innerHTML = "✅ OTP sent successfully. Check your email or phone.";
                messageBox.classList.add("success");
                messageBox.style.display = "block";
            } else if (status === "error") {
                messageBox.innerHTML = "❌ Failed to send OTP. Please try again.";
                messageBox.classList.add("error");
                messageBox.style.display = "block";
            } else if (status === "not_found") {
                messageBox.innerHTML = "❌ This email or phone number is not registered.";
                messageBox.classList.add("error");
                messageBox.style.display = "block";
            }

            // Hide message after 3 seconds
            if (messageBox.style.display === "block") {
                setTimeout(() => {
                    messageBox.style.display = "none";
                }, 3000);
            }
        };
    </script>
</body>
</html>
