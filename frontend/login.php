<?php
echo realpath(__DIR__ . '/../private/ai_config.ini');
$config = parse_ini_file(__DIR__ . '/../../../private/ai_config.ini', true);
$loginAction = $config['urls']['login_action'] ?? '#';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AI Marketing Automation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* Center the login container */
.signup-container {
    width: 350px;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin: 100px auto; /* Centers the form */
}

/* Form Inputs */
.signup-container input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Login Button */
.signup-container button {
    width: 100%;
    padding: 10px;
    background: #1abc9c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

.signup-container button:hover {
    background: #16a085;
}

/* Forgot Password & Sign Up Links */
.signup-container p {
    margin-top: 15px;
}

.signup-container a {
    color: #1abc9c;
    text-decoration: none;
}

.signup-container a:hover {
    text-decoration: underline;
}

/* Message Box */
#error-message {
    color: red;
    font-weight: normal;
    margin-top: 10px;
}
/* Message Box Styling */
#message-box {
        display: none;
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        background: #ffcccc;
        color: red;
        font-weight: bold;
        text-align: center;
        border-radius: 5px;
        border: 1px solid red;
    }

    .show-message {
        display: block;
    }



    /* .error {
    color: red;
    font-weight: bold;
    margin-top: 10px;
} */

</style>
<body>
    
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo"> <!-- Replace with your logo path -->
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
            </li>
            </ul>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="#">Contact</a></li>
           
        </nav>

        
    </header>
    

    <div class="signup-container">
        <div id="message-box"></div>
        <h2>Login</h2>
        <form action="<?= $loginAction ?>" method="POST">

            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        
        <p><a href="forgot_password.php">Forgot Password?</a></p>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </div>
   
<script>
     window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const messageBox = document.getElementById("message-box");

        if (status === "invalid_password") {
            messageBox.innerHTML = "❌ Invalid password. Please try again.";
            messageBox.style.display = "block"; // Show the message
        } else if (status === "user_not_found") {
            messageBox.innerHTML = "❌ User not found. Please check your email.";
            messageBox.style.display = "block"; // Show the message
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
