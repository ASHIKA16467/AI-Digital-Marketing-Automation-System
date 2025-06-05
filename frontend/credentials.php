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
    <title>Credential Manager - AI Digital Marketing</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 48%;
        }
        .btn-save {
            background-color: #28a745;
            color: white;
        }
        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .credential-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .toggle-password {
            cursor: pointer;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
        .hidden {
            display: none;
        }
        .show-message {
            display: block;
        }
    </style>
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

    <!-- Header -->
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
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
           
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


    <div class="credential-container">
        <h2>🔐 Save Your Social Media Credentials</h2>
        <div id="success-message" class="success-message"></div>
        <p id="error-message" style="color: red; display: none;"></p>

        <form id="credential-form">
            <input type="hidden" id="credential-id">
            <label>Platform:</label>
            <select id="platform">
                <option value="Instagram">📷 Instagram</option>
                <option value="LinkedIn">🔗 LinkedIn</option>
                <option value="Facebook">📘 Facebook</option>
                <option value="Twitter">🐦 Twitter</option>
            </select>

            <label>Username:</label>
            <input type="text" id="username" placeholder="Enter username">

            <label>Password:</label>
            <input type="password" id="password" placeholder="Enter password">
            <span class="toggle-password" onclick="togglePassword(this)">👁️</span>

            <div class="button-group">
                <button type="button" class="btn btn-save" onclick="saveCredential()">Save</button>
                <button type="button" class="btn btn-cancel" onclick="resetForm()">Cancel</button>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Platform</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="credential-table-body">
                <!-- Credentials will be loaded here dynamically using JavaScript -->
            </tbody>
        </table>
    </div>
    <script>
        // Show/Hide Dropdown
        function toggleDropdown() {
            document.getElementById("userDropdown").classList.toggle("show");
        }

        // Close dropdown if user clicks outside
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
                window.location.href = "../backend/logout.php"; // Redirect to logout.php
            }
        }
    </script>

    <script>
    let isEditing = false;

    function togglePassword(element) {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        element.innerHTML = type === 'password' ? '👁️' : '🙈';
    }

    function saveCredential() {
        const credentialId = document.getElementById('credential-id').value;
        const platform = document.getElementById('platform').value;
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        if(!platform || !username || !password){
            showInfoMessage("All fields are required!!");
            return;
        }
        
        const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'processcredential.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    showSuccessMessage(response.message);
                    fetchCredentials();
                    resetForm();
                } else {
                    if (response.message === 'Credential already exists!') {
                        showInfoMessage(response.message);

                    } else {
                        document.getElementById('error-message').innerText = response.message;
                        document.getElementById('error-message').style.display = 'block';
                    }
                }
            }
        };
        const action = isEditing ? 'edit' : 'add';
        xhr.send(`action=${action}&user_id=${userId}&id=${credentialId}&platform=${platform}&username=${username}&password=${password}`);
    }
    function editCredential(id) {
        isEditing = true;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'processcredential.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const credential = response.data;
                    document.getElementById('credential-id').value = credential.id;
                    document.getElementById('platform').value = credential.platform;
                    document.getElementById('username').value = credential.username;
                    document.getElementById('password').value = credential.password; // Do not pre-fill password for security
                    document.getElementById('save-btn').innerText = 'Update Credential';
                    document.getElementById('back-btn').style.display = 'block';
                } else {
                    document.getElementById('error-message').innerText = response.message;
                    document.getElementById('error-message').style.display = 'block';
                }
            }
        };
        xhr.send(`action=fetch-single&id=${id}`);
    }

    function deleteCredential(id) {
        if (confirm("Are you sure you want to delete this credential?")) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'processcredential.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    
                    if (response.success) {
                        showSuccessMessage(response.message);
                        fetchCredentials();
                    } else {
                        document.getElementById('error-message').innerText = response.message;
                        document.getElementById('error-message').style.display = 'block';
                    }
                }
            };
            xhr.send(`action=delete&id=${id}`);
        }
    }

    function resetForm() {
        isEditing = false;
        document.getElementById('credential-form').reset();
        document.getElementById('save-btn').innerText = 'Add Credential';
        document.getElementById('save-btn').style.display = 'block';
        document.getElementById('back-btn').style.display = 'none';
        document.getElementById('cancel-btn').innerText = 'Reset'; // Hide Cancel Button
    }

    function fetchCredentials() {
        const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'processcredential.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const tableBody = document.getElementById('credential-table-body');
                    tableBody.innerHTML = ''; // Clear existing rows
                    response.data.forEach(credential => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${credential.platform}</td>
                            <td>${credential.username}</td>
                            <td>
                                <button onclick="editCredential(${credential.id})">Edit</button>
                                <button onclick="deleteCredential(${credential.id})">Delete</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    document.getElementById('error-message').innerText = response.message;
                    document.getElementById('error-message').style.display = 'block';
                }
            }
        };
        xhr.send(`action=fetch&user_id=${userId}`);
    }

    function showSuccessMessage(message) {
        const successMessage = document.getElementById('success-message');
        successMessage.innerText = message;
        successMessage.style.display = 'block';
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000); // Hide the message after 3 seconds
    }

    function showInfoMessage(message) {
        const infoMessage = document.getElementById('error-message');
        infoMessage.innerText = message;
        infoMessage.style.display = 'block';
        setTimeout(() => {
            infoMessage.style.display = 'none';
        }, 3000); // Hide the message after 3 seconds
    }

    // Fetch credentials on page load
    fetchCredentials();
</script>


    <!-- Footer -->
    <footer>
        <p>&copy; 2025 AI Digital Marketing. All rights reserved.</p>
    </footer>
</body>
</html>
