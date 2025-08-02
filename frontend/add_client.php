<?php
require_once '../frontend/db.php';
$db = new MySqlDB();
session_start();
if (!isset($_SESSION["full_name"])) {
    $username = "Guest"; // Default if not logged in
} else {
    $username = $_SESSION["full_name"];
}
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?status=please_login");
    exit();
}
$username = $_SESSION['user_name'] ?? 'Guest';


$user_id = $_SESSION["user_id"];
$profile_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;

$isEditing = $profile_id !== null;

error_log("user id=".$user_id."profile=".$profile_id);  // Log any error for debugging
   

// Fetch the profile data if editing
if ($isEditing) {
    if (!$profile_id || !is_numeric($profile_id)) {
        error_log("❌ Missing or invalid profile ID for editing (profile_id=$profile_id)");
        echo "Invalid profile ID!";
        exit();
    }

    $profile = $db->getProfileById($profile_id, $user_id);
    if (!$profile) {
        error_log("❌ No profile found for user_id=$user_id and profile_id=$profile_id");
        echo "Profile not found!";
        exit();
    }
}


// Handle profile update if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $data = [
        'id' => $_POST['id'],
        'user_id' => $user_id,
        'project_name' => $_POST['project_name'],
        'company_name' => $_POST['company_name'],
        'contact_name' => $_POST['contact_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'social_media' => ucfirst(strtolower(trim($_POST['social_media']))),
        'business_info' => $_POST['business_info'],
        'additional_info' => $_POST['additional_info'],
        'url' => $_POST['url'],
        'tagline' => $_POST['tagline'],
        'preferred_keywords' => $_POST['preferred_keywords']
    ];

    $updateSuccess = updateProfile($data);

    if ($updateSuccess) {
        header("Location: company_profile.php?id=" . $_POST['id'] . "&status=updated");
        exit();
    } else {
        $error = "Error updating profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management - AI Digital Marketing</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        
       body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    font-size: 18px; /* Increased base font size */
    color: #222; /* Darker font color for better contrast */
}

.profile-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 30px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.profile-container h2 {
    text-align: center;
    color: #1a237e; /* Changed heading color */
    font-size: 26px;
    margin-bottom: 20px;
}

.profile-form label {
    margin-bottom: 8px;
    font-weight: bold;
    font-size: 18px;
    color: #333;
}

.profile-form input,
.profile-form textarea,
.profile-form select {
    margin-bottom: 20px;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    width: 100%;
    box-sizing: border-box;
    color: #333;
}

.profile-form button {
    padding: 12px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    margin-right: 10px;
    cursor: pointer;
}

#save-btn {
    background-color: #4CAF50;
    color: white;
}

#save-btn:hover {
    background-color: #45a049;
}

#back-btn {
    background-color: #f44336;
    color: white;
}

#back-btn:hover {
    background-color: #d32f2f;
}

.success-message,
.error-message {
    font-size: 16px;
    padding: 12px;
}

    </style>

</head>
<script>
    function validateForm() {
        let fields = ['project-name', 'company-name', 'contact-name', 'email', 'phone', 'social-media', 'business-info', 'additional-info'];
        for (let field of fields) {
            let input = document.getElementById(field);
            if (!input.value.trim()) {
                alert("Please fill out all required fields.");
                input.focus();
                return false;
            }
        }
        return true;
    }
</script>
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
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="#">Contact</a></li>
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


<div class="profile-container">
    <h2>Client Business Information</h2>
    <div id="success-message" class="success-message"></div>
    <p id="error-message" class="error-message" style="color: red; display: none;"></p>

    <form id="profile-form" class="profile-form" onsubmit="return validateForm()">
        <input type="hidden" id="profile-id" value="<?php echo $isEditing ? $profile_id : ''; ?>">
        <label>Project Name:</label>
        <input type="text" id="project-name" placeholder="Enter project name" value="<?php echo $isEditing ? htmlspecialchars($profile['project_name']) : ''; ?>"require>

        <label>Company Name:</label>
        <input type="text" id="company-name" placeholder="Enter company name" value="<?php echo $isEditing ? htmlspecialchars($profile['company_name']) : ''; ?>"required>

        <label>Contact Name:</label>
        <input type="text" id="contact-name" placeholder="Enter contact name" value="<?php echo $isEditing ? htmlspecialchars($profile['contact_name']) : ''; ?>"required>

        <label>Email:</label>
        <input type="email" id="email" placeholder="Enter email" value="<?php echo $isEditing ? htmlspecialchars($profile['email']) : ''; ?>"required>

        <label>Phone:</label>
        <input type="text" id="phone" placeholder="Enter phone number" value="<?php echo $isEditing ? htmlspecialchars($profile['phone']) : ''; ?>"required>

        <label>Social Media:</label>
        <select id="social-media" name="social_media"required>
            <option value="instagram" <?php echo $isEditing && $profile['social_media'] === 'instagram' ? 'selected' : ''; ?>>Instagram</option>
            <option value="linkedin" <?php echo $isEditing && $profile['social_media'] === 'linkedin' ? 'selected' : ''; ?>>LinkedIn</option>
            <option value="facebook" <?php echo $isEditing && $profile['social_media'] === 'facebook' ? 'selected' : ''; ?>>Facebook</option>
            <option value="Twitter" <?php echo $isEditing && $profile['social_media'] === 'Twitter' ? 'selected' : ''; ?>>Twitter</option>
        </select>

        <label>Business Info:</label>
        <textarea id="business-info" placeholder="Enter business information" required><?php echo $isEditing ? htmlspecialchars($profile['business_info']) : ''; ?></textarea>

        <label>Additional Info:</label>
        <textarea id="additional-info" placeholder="Enter additional information" required><?php echo $isEditing ? htmlspecialchars($profile['additional_info']) : ''; ?></textarea>


        <label>Website URL:</label>
        <input type="text" id="url" placeholder="Enter website URL" value="<?php echo $isEditing ? htmlspecialchars($profile['url'] ?? '') : ''; ?>">

        <label>Tagline:</label>
        <input type="text" id="tagline" placeholder="Enter company tagline" value="<?php echo $isEditing ? htmlspecialchars($profile['tagline'] ?? '') : ''; ?>">

        <label>Preferred Keywords:</label>
        <input type="text" id="preferred-keywords" placeholder="Enter preferred keywords" value="<?php echo $isEditing ? htmlspecialchars($profile['preferred_keywords'] ?? '') : ''; ?>">

        <button type="button" id="save-btn" onclick="saveProfile()" 
    style="background-color: #4CAF50; color: white; padding: 5px 10px; font-size: 14px;">
    <?php echo $isEditing ? 'Update Profile' : 'Save Profile'; ?>
</button>

<button type="button" id="back-btn" onclick="goBack()" 
    style="background-color: #f44336; color: white; padding: 5px 10px; font-size: 14px; display: <?php echo $isEditing ? 'block' : 'none'; ?>;">
    Cancel
</button>

<button type="button" id="cancel-btn" onclick="resetForm()" 
    style="background-color: #ff9800; color: white; padding: 5px 10px; font-size: 14px; display: <?php echo $isEditing ? 'none' : 'block'; ?>;">
    Cancel
</button>

    </form>

    <!-- <div class="profile-list">
        <h2>Your Submitted Profiles</h2>
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
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="profile-table-body"> -->
                <!-- Profiles will be loaded here dynamically using JavaScript -->
            <!-- </tbody>
        </table> -->
    <!-- </div> -->
</div>

<script>
    let isEditing = <?php echo $isEditing ? 'true' : 'false'; ?>;


    function saveProfile() {
    const projectName = document.getElementById('project-name').value.trim();
    const companyName = document.getElementById('company-name').value.trim();
    const contactName = document.getElementById('contact-name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const businessInfo = document.getElementById('business-info').value.trim();
    const additionalInfo = document.getElementById('additional-info').value.trim();
    const url = document.getElementById('url').value.trim();
    const tagline = document.getElementById('tagline').value.trim();
    const preferredKeywords = document.getElementById('preferred-keywords').value.trim();
    const profileId = document.getElementById('profile-id').value;
    const socialMedia = document.getElementById('social-media').value;
    const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;
    const action = isEditing ? 'edit' : 'add';

    // ✅ Required Field Validation
    if (!projectName || !companyName || !contactName || !email || !phone || !businessInfo || !additionalInfo) {
        showInfoMessage("All fields are required!");
        return;
    }

    // ✅ Email Validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showInfoMessage("Invalid email format!");
        return;
    }

    // ✅ Phone Validation
    const phonePattern = /^[0-9]{10,15}$/;
    if (!phonePattern.test(phone)) {
        showInfoMessage("Phone number must be 10-15 digits!");
        return;
    }

    // ✅ Now safely create and use xhr
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'processprofile.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    showSuccessMessage(response.message);
                    window.location.href = 'company_profile.php';
                    resetForm();
                } else {
                    showInfoMessage(response.message || "Something went wrong.");
                }
            } catch (e) {
                console.error("Invalid JSON Response", xhr.responseText);
                showInfoMessage("Unexpected response from server.");
            }
        }
    };

    const payload = `action=${encodeURIComponent(action)}&user_id=${userId}&id=${encodeURIComponent(profileId)}&project_name=${encodeURIComponent(projectName)}&company_name=${encodeURIComponent(companyName)}&contact_name=${encodeURIComponent(contactName)}&email=${encodeURIComponent(email)}&phone=${encodeURIComponent(phone)}&social_media=${encodeURIComponent(socialMedia)}&business_info=${encodeURIComponent(businessInfo)}&additional_info=${encodeURIComponent(additionalInfo)}&url=${encodeURIComponent(url)}&tagline=${encodeURIComponent(tagline)}&preferred_keywords=${encodeURIComponent(preferredKeywords)}`;

    xhr.send(payload); // ✅ send after everything is ready
}

    function editProfile(id) {
        isEditing = true;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'processprofile.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const profile = response.data;
                    document.getElementById('profile-id').value = profile.id;
                    document.getElementById('project-name').value = profile.project_name;
                    document.getElementById('company-name').value = profile.company_name;
                    document.getElementById('contact-name').value = profile.contact_name;
                    document.getElementById('email').value = profile.email;
                    document.getElementById('phone').value = profile.phone;
                    document.getElementById('social-media').value = profile.social_media;
                    document.getElementById('business-info').value = profile.business_info;
                    document.getElementById('additional-info').value = profile.additional_info;
                    document.getElementById('url').value=profile.url;
                    document.getEementById('tagline').value=profile.tagline;
                    document.grtElementById('preferred-keywords').value=profile.preferred_keywords;
                    document.getElementById('save-btn').innerText = 'Update Profile';
                    document.getElementById('back-btn').style.display = 'block';
                     showSuccessMessage(response.message);
                } else {
                    document.getElementById('error-message').innerText = response.message;
                    document.getElementById('error-message').style.display = 'block';
                }
            }
        };
        xhr.send(`action=fetch-single&id=${id}`);
    }

    function deleteProfile(id) {
        if (confirm("Are you sure you want to delete this profile?")) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'processprofile.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showSuccessMessage(response.message);
                        fetchProfiles();
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
        document.getElementById('profile-form').reset();
        document.getElementById('save-btn').innerText = 'Save Profile';
        document.getElementById('save-btn').style.display = 'block';
        document.getElementById('back-btn').style.display = 'none';
    }
    function goBack() {
        window.location.href = 'companies.php'; // Redirect to companies page
    }

    function fetchProfiles() {
        const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'processprofile.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const tableBody = document.getElementById('profile-table-body');
                    tableBody.innerHTML = ''; // Clear existing rows
                    response.data.forEach(profile => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${profile.project_name}</td>
                            <td>${profile.company_name}</td>
                            <td>${profile.contact_name}</td>
                            <td>${profile.email}</td>
                            <td>${profile.phone}</td>
                            <td>${profile.social_media}</td>
                            <td>${profile.business_info}</td>
                            <td>${profile.additional_info}</td>
                            <td>${profile.submitted_at}</td>
                            <td>
                                <button class="edit-btn" onclick="editProfile(${profile.id})">Edit</button>
                                <button class="delete-btn" onclick="deleteProfile(${profile.id})">Delete</button>
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


    // Fetch profiles on page load
    fetchProfiles();
</script>

<?php include 'footer.php'; ?>  <!-- Include Footer -->

</body>
</html>
