<?php

// Include the DB connection
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

include 'db.php';
$db = new MySqlDB();
$userId = $_GET['id'];


if (isset($_GET['id'])) {
    $userId = intval($_GET['id']); // Securely cast to integer
    $company = $db->getCompanyById($userId);

    if (!$company) {
        echo "Company not found.";
        exit;
    }
} else {
    echo "No company selected.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Start Digital Marketing</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional -->
    <style>
        body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: #f2f2f2;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

header {
    background-color: black;
    padding: 10px 20px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-container,
.user-dropdown {
    display: flex;
    gap: 15px;
    align-items: center;
}

main {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.company-form {
    border: 1px solid #ccc;
    padding: 25px 30px;
    border-radius: 10px;
    background: #ffffff;
    max-width: 600px;
    width: 100%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.company-form input {
    display: block;
    width: 100%;
    margin: 5px 0 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.generate-btn {
    background-color: #007BFF;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
}

.generate-btn:hover {
    background-color: #0056b3;
}

#generatedContent {
    margin-top: 20px;
    white-space: pre-wrap;
    background-color: #f1f1f1;
    padding: 15px;
    border-radius: 8px;
    font-size: 15px;
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
                    <li><a href="email_marketing.php">Email Marketing Automation</a></li>
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
                    <a href="profile.php">Profile</a>
                    <a href="companies.php">Companies</a>
                    <a href="credentials.php">🔑 Credentials</a>
                    <a href="" onclick="confirmLogout()">Logout</a>
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

  
      
<h2 style="text-align: center; margin-top: 30px;">AI-Powered Content Generator & Social Media Posting</h2>

   

<main>
    <form class="company-form">
        <label>Project Name:</label>
        <input type="text" value="<?php echo htmlspecialchars($company['project_name']); ?>" readonly>

        <label>Company Name:</label>
        <input type="text" value="<?php echo htmlspecialchars($company['company_name']); ?>" readonly>

        <label>Contact Name:</label>
        <input type="text" value="<?php echo htmlspecialchars($company['contact_name']); ?>" readonly>

        <label>Email:</label>
        <input type="text" value="<?php echo htmlspecialchars($company['email']); ?>" readonly>

        <label>Phone:</label>
        <input type="text" value="<?php echo htmlspecialchars($company['phone']); ?>" readonly>

        <label>Social media</label>
        <input type="text" value="<?php echo htmlspecialchars($company['social_media']); ?>" readonly>

        <label>Tagline:</label>
        <input type="text" value="<?php echo htmlspecialchars($company['tagline']); ?>" readonly>

        <label>Business Info:</label>
        <input type="text" value="<?php echo htmlspecialchars($company['business_info'])?>"readonly>

        <label>Additional Info:</label>
        <input type="text" value="<?php echo htmlspecialchars($company['additional_info'])?>"readonly>

        <label>Preferred Keywords:</label>
        <input type="text" value="<?php echo htmlspecialchars($company['preferred_keywords']); ?>" readonly>

        <!-- RIGHT COLUMN: AI + User Suggestions -->
<div style="flex: 1;" class="suggestion-box">
    <h3>AI Suggestions</h3>
    <p>Based on your tagline and keywords:</p>
    <ul id="suggestions-list">
        <li>Loading suggestions...</li>
    </ul>

    <label for="userSuggestion">➕ Add Your Suggestion:</label>
    <input type="text" id="userSuggestion" placeholder="Enter your own idea..." style="width: 100%; padding: 6px; margin-bottom: 10px;">
    <button type="button" onclick="addUserSuggestion()">Add Suggestion</button>
    
    <!-- <button type="button" onclick="fetchAISuggestions()" style="margin-top:15px;">🔁 Refresh AI Suggestions</button> -->
</div>

        <button type="button" class="generate-btn" onclick="generateContent()">Generate AI Content</button>
        <div id="generatedContent"></div>
        <button type="button" id="copyBtn" class="generate-btn" style="margin-top: 15px; display:none;" onclick="copyToClipboard()">📋 Copy</button>

        <button type="button" id="postBtn" class="generate-btn" style="margin-top: 15px; display:none;" onclick="postToSocialMedia()">
    📤 Post 
</button>

    </form>

    
</main>



<script>
    function copyToClipboard() {
    const text = document.getElementById("generatedContent").innerText.trim();
    navigator.clipboard.writeText(text).then(() => {
        showToast();
    });
}

function showToast() {
    const toast = document.getElementById("toast");
    toast.style.visibility = "visible";

    setTimeout(() => {
        toast.style.visibility = "hidden";
    }, 2000); // Toast disappears after 2 seconds
}


    
    const selecteduserId = <?= json_encode($_SESSION['user_id']) ?>;

let content = document.getElementById("content").value;

function generateContent() {
    const tagline = "<?php echo addslashes($company['tagline']); ?>";
    const businessInfo = "<?php echo addslashes($company['business_info']); ?>";
    const additionalInfo = "<?php echo addslashes($company['additional_info']); ?>";
    const keywords = "<?php echo addslashes($company['preferred_keywords']); ?>";
    const companyName = "<?php echo addslashes($company['company_name']); ?>";
    const projectName = "<?php echo addslashes($company['project_name']); ?>";
    const platform = "<?php echo strtolower($company['social_media']); ?>"; // Get platform (twitter, linkedin, etc.)

    let allSuggestions = "";
    const suggestionItems = document.querySelectorAll('#suggestions-list li');
    suggestionItems.forEach((item, index) => {
        allSuggestions += `Suggestion ${index + 1}: ${item.textContent}\n`;
    });

    let prompt = "";

    // 🟦 Platform-specific logic
    if (platform === "twitter") {
    prompt = `Write a single promotional tweet under 180 characters for "${companyName}" and its project "${projectName}". Include 1 or 2 relevant hashtags based on these keywords: ${keywords}. Use the tagline "${tagline}". Don't mention Bangalore. Reply only with the tweet text — no explanation, no heading.`;
    } else {
        prompt = `
Create a digital marketing promotional content for a company named "${companyName}" and its project "${projectName}".
Use the tagline: "${tagline}" and focus on these keywords: ${keywords}.

Include:
- A short intro (50-70 words)
- Key benefits
- Unique strengths of the company
- SEO-friendly content using the keywords naturally
- A catchy call-to-action at the end
- 3 to 5 relevant hashtags based on the keywords at the bottom

⚠️ Do not include any headings or section titles. Just write continuous engaging content with natural flow.
Write in a professional, engaging, marketing tone suitable for social media.
`;
    }

    // ⏳ Show loading indicator
    document.getElementById('generatedContent').innerHTML = "<em>⏳ Generating content, please wait...</em>";

    // 🚀 Send POST to generate_area.php
    fetch('generate_area.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `prompt=${encodeURIComponent(prompt)}`
    })
    .then(response => response.text())
    .then(data => {
    const formattedData = formatAIResponse(data);
    document.getElementById('generatedContent').innerHTML = `<strong></strong><br>${formattedData}`;
    document.getElementById('postBtn').style.display = "inline-block";

    const platform = "<?php echo strtolower($company['social_media']); ?>";
    if (platform !== 'twitter') {
        document.getElementById('copyBtn').style.display = "inline-block";
    } else {
        document.getElementById('copyBtn').style.display = "none";
    }
})
const hashtagged = data + "\n" + generateHashtagsFromKeywords(keywords);
function generateHashtagsFromKeywords(keywords) {
    return keywords
        .split(',')
        .map(kw => "#" + kw.trim().replace(/\s+/g, ''))
        .join(' ');
}


}

function addUserSuggestion() {
    
    const input = document.getElementById("userSuggestion");
    const suggestion = input.value.trim();
    if (suggestion === "") return;

    // Save to DB
    fetch('save_suggestion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `user_id=<?php echo $userId; ?>&suggestion=${encodeURIComponent(suggestion)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            const list = document.getElementById("suggestions-list");
            const li = document.createElement("li");
            li.textContent = suggestion + " (user)";
            li.style.color = "#007BFF";
            list.appendChild(li);
            input.value = "";
        } else {
            alert("Failed to save suggestion: " + data.message);
        }
    })
    .catch(err => alert("Error: " + err));
}

function postToSocialMedia() {
    const userId = <?= json_encode($userId) ?>;
    const content = document.getElementById('generatedContent').innerText.trim();

    if (!content) {
        alert("No content to post.");
        return;
    }

    fetch('post_to_social.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            user_id: userId,
            content: content
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' && data.redirect_url) {
            // ✅ Redirect to Facebook or LinkedIn share page
            window.open(data.redirect_url, '_blank');
        } else if (data.status === 'no_redirect') {
            // ⚠️ For platforms like Twitter or Instagram
            alert(`Posting for ${data.platforms.join(', ')} doesn't require redirection.`);
        } else {
            alert("❌ " + (data.message || "Unable to post."));
        }
    })
    .catch(err => {
        console.error("Posting error:", err);
        alert("Something went wrong while posting.");
    });
}


function formatAIResponse(text) {
    // Convert **text** to <strong>text</strong>
    return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
}

</script>

<div id="toast" style="
    visibility: hidden;
    min-width: 250px;
    background-color: #323232;
    color: #fff;
    text-align: center;
    border-radius: 4px;
    padding: 12px;
    position: fixed;
    z-index: 9999;
    left: 50%;
    bottom: 30px;
    transform: translateX(-50%);
    font-size: 14px;
">
    Copied to clipboard!
</div>


</body>
</html>
