<?php
session_start();
if (!isset($_SESSION["full_name"])) {
    $username = "Guest"; // Default if not logged in
} else {
    $username = $_SESSION["full_name"];
}

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?status=please_login");
    exit();
}
$username = $_SESSION['user_name'] ?? 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Companies | AI Digital Marketing</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            
            height: 100%;
            min-height: 100%;
            width: 100%;


        }
        nav.nav-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            /* background-color: black; */
            width: 100%;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            background-color: #222;
            color: white;
            padding: 10px 20px;
            box-sizing: border-box;
            position: relative;
        }

        .profile-list {
            max-width: 1500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .success-message, .error-message {
            text-align: center;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success-message {
            color: green;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        .profile-list h2 {
            text-align: center;
            color: #333;
        }
        .profile-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .profile-list table, .profile-list th, .profile-list td {
            border: 1px solid #ccc;
        }
        .profile-list th, .profile-list td {
            padding: 10px;
            text-align: left;
        }
        .profile-list th {
            background-color: #f8f9fa;
            color: #ff0000;  /* Red color for headings */
        }

        .profile-list button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .profile-list .edit-btn {
            background-color: #28a745;
            color: #fff;
        }
        .profile-list .delete-btn {
            background-color: #dc3545;
            color: #fff;
        }
        .profile-list .edit-btn:hover, .profile-list .delete-btn:hover {
            opacity: 0.9;
        }
        .start-btn {
        background-color: #17a2b8;
        color: #fff;
        margin-top: 5px;
}

    </style>
</head>
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
<main class="main-container">
<div class="profile-list">
    <h2>Your Submitted clients Business Profile</h2><a href="add_client.php">Add New Client</a>
    <div id="success-message" class="success-message"></div>
    <p id="error-message" class="error-message" style="color: red; display: none;"></p>
    <div style="overflow-x:auto;">
    <table>
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Company Name</th>
                <th>Contact Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Social Media</th>
                <th>Business Info</th>
                <th>Additional Info</th>
                <th>URL</th>
                <th>Tagline</th>
                <th>Preferred Keywords</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="company-table-body">
            <!-- Company profiles will be loaded here dynamically using JavaScript -->
        </tbody>
    </table>
    </div>
</div>

<script>
    function fetchCompanies() {
        const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'processCompanies.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const tableBody = document.getElementById('company-table-body');
                    tableBody.innerHTML = ''; // Clear existing rows
                    response.data.forEach(company => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${company.project_name}</td>
                            <td>${company.company_name}</td>
                            <td>${company.contact_name}</td>
                            <td>${company.email}</td>
                            <td>${company.phone}</td>
                            <td>${company.social_media}</td>
                            <td>${company.business_info}</td>
                            <td>${company.additional_info}</td>
                            <td>${company.url}</td>
                            <td>${company.tagline}</td>
                            <td>${company.preferred_keywords}</td>

                            <td>${company.formatted_date}</td>
                            <td>
                                <button class="edit-btn" onclick="editCompany(${company.id})">Edit</button>
                                <button class="delete-btn" onclick="deleteCompany(${company.id})">Delete</button>
                                <button class="start-btn" onclick="startDigitalMarketing(${company.id})">Start DM</button>

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
    function startDigitalMarketing(id) {
    
    window.location.href = `start_DM.php?id=${id}`;
}


    function editCompany(id) {
        window.location.href = `company_profile.php?id=${id}`;
    }

    function deleteCompany(id) {
        if (confirm("Are you sure you want to delete this company profile?")) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'processCompanies.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showSuccessMessage(response.message);
                        fetchCompanies();
                    } else {
                        showInfoMessage(response.message);
                    }
                }
            };
            xhr.send(`action=delete&id=${id}`);
        }
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


    // Fetch companies on page load
    fetchCompanies();
</script>
</main>
<?php include 'footer.php'; ?>  <!-- Include Footer -->

</body>
</html>
