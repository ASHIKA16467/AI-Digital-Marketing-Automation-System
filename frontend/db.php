
<?php
class MySqlDB {
    private $conn;

    public function __construct() {
        $config = parse_ini_file(__DIR__ . '/../../../private/ai_config.ini', true);

        $host = $config['database']['host'];
        $db = $config['database']['dbname'];
        $user = $config['database']['username'];
        $pass = $config['database']['password'];

        $this->conn = new mysqli($host, $user, $pass, $db);
        
    

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    // Fetch profile data by profile ID for the current user
    public function getProfileById($profile_id, $user_id) {
        $stmt = $this->conn->prepare("SELECT project_name, company_name, contact_name, email, phone, social_media, business_info, additional_info, url, tagline, preferred_keywords FROM user_profiles WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $profile_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getConnection() {
    return $this->conn;
}


    // Update profile data
    public function updateProfile($data) {
        $stmt = $this->conn->prepare("UPDATE user_profiles SET project_name = ?, company_name = ?, contact_name = ?, email = ?, phone = ?, social_media = ?, business_info = ?, additional_info = ?, url = ?, tagline = ?, preferred_keywords = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param(
            "ssssssssssssi",
            $data['project_name'],
            $data['company_name'],
            $data['contact_name'],
            $data['email'],
            $data['phone'],
            $data['social_media'],
            $data['business_info'],
            $data['additional_info'],
            $data['url'],
            $data['tagline'],
            $data['preferred_keywords'],
            $data['id'],
            $data['user_id']
        );
        return $stmt->execute();
    }

    public function fetchCredentials($userId) {
        $stmt = $this->conn->prepare("SELECT id, platform, username FROM user_credentials WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addCredential($userId, $platform, $username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // Secure the password
        $stmt = $this->conn->prepare("INSERT INTO user_credentials (user_id, platform, username, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $platform, $username, $hashedPassword);
        
        if ($stmt->execute()) {
            return true;
        } else {
            // 👇 PLACE THIS HERE
            error_log("Credential Save Error: " . $stmt->error);  // This logs the error to php_error.log
            return false;
        }
    }
    

    public function deleteCredential($id) {
        $stmt = $this->conn->prepare("DELETE FROM user_credentials WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function fetchCredentialById($id){
        $stmt = $this->conn->prepare("SELECT id, platform, username , password FROM user_credentials WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();   
    }
    public function isCredentialExists($userId, $platform, $username, $currentId){
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM user_credentials WHERE user_id = ? AND platform = ? AND username = ? AND id != ?");
        $stmt->bind_param("issi", $userId, $platform, $username, $currentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] > 0;
    }
    public function editCredential($id, $platform, $username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE user_credentials SET platform = ?, username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $platform, $username, $hashedPassword, $id);
        return $stmt->execute();
    }

    public function fetchProfiles($userId) {
        $stmt = $this->conn->prepare("SELECT id, project_name, company_name, contact_name, email, phone, social_media, business_info, additional_info, url, tagline, preferred_keywords, submitted_at FROM user_profiles WHERE user_id = ? ORDER BY submitted_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchProfileById($id) {
        $stmt = $this->conn->prepare("SELECT id, project_name, company_name, contact_name, email, phone, social_media, business_info, additional_info, url, tagline, preferred_keywords FROM user_profiles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function addProfile($userId, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords) {
        $stmt = $this->conn->prepare("INSERT INTO user_profiles (user_id, project_name, company_name, contact_name, email, phone, social_media, business_info, additional_info, url, tagline, preferred_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssssssss", $userId, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords);
       
        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            error_log($stmt->error);  // Log any error for debugging
            return false;
        }
    }
    
    
    public function editProfile($id, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords) {
        $stmt = $this->conn->prepare("UPDATE user_profiles SET project_name = ?, company_name = ?, contact_name = ?, email = ?, phone = ?, social_media = ?, business_info = ?, additional_info = ?, url = ?, tagline = ?, preferred_keywords = ? WHERE id = ?");
        $stmt->bind_param("sssssssssssi", $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords, $id);
        return $stmt->execute();
    }

    public function deleteProfile($id) {
        $stmt = $this->conn->prepare("DELETE FROM user_profiles WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function fetchCompanies($userId) {
        $stmt = $this->conn->prepare("SELECT id, project_name, company_name, contact_name, email, phone, social_media, business_info, additional_info, url, tagline, preferred_keywords, DATE_FORMAT(submitted_at, '%d-%m-%Y %h:%i %p') AS formatted_date FROM user_profiles WHERE user_id = ? ORDER BY submitted_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchCompanyById($id) {
        $stmt = $this->conn->prepare("SELECT id, project_name, company_name, contact_name, email, phone, social_media, business_info, additional_info, url, tagline, preferred_keywords FROM user_profiles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function addCompany($userId, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords) {
        $stmt = $this->conn->prepare("INSERT INTO user_profiles (user_id, project_name, company_name, contact_name, email, phone, social_media, business_info, additional_info, url, tagline, preferred_keywords, submitted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isssssssssss", $userId, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords);
        return $stmt->execute();
    }
    

    public function editCompany($id, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords) {
        $stmt = $this->conn->prepare("UPDATE user_profiles SET project_name = ?, company_name = ?, contact_name = ?, email = ?, phone = ?, social_media = ?, business_info = ?, additional_info = ?, url = ?, tagline=?, preferred_keywords=? WHERE id = ?");
        $stmt->bind_param("sssssssssssi", $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords, $id);
        return $stmt->execute();
    }

    public function deleteCompany($id) {
        $stmt = $this->conn->prepare("DELETE FROM user_profiles WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function isCompanyExists($userId, $companyName, $projectName, $socialMedia, $currentId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM user_profiles WHERE user_id = ? AND company_name = ? AND project_name = ? AND social_media = ? AND id != ?");
        $stmt->bind_param("isssi", $userId, $companyName, $projectName, $socialMedia, $currentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] > 0;
    }

    public function getCompanyById($id) {
        $query = "SELECT * FROM user_profiles WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_assoc();
    }
    public function saveCompanySuggestion($userId, $suggestion) {
    $stmt = $this->conn->prepare("INSERT INTO suggestions (user_id, suggestion, source, created_at) VALUES (?, ?, 'user', NOW())");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("is", $userId, $suggestion);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    return true;
}

    
    
    public function getCredentialsByuserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM user_credentials WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $credentials = [];
        while ($row = $result->fetch_assoc()) {
            $credentials[] = $row;
        }
    
        return $credentials;
    }
    
   public function getCompanyByUserId($userId) {
    $stmt = $this->conn->prepare("SELECT * FROM user_profiles WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}



public function getOrCreateGroupId($groupName) {
    // Check if group already exists
    $stmt = $this->conn->prepare("SELECT id FROM email_groups WHERE name = ?");
    $stmt->bind_param("s", $groupName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['id'];
    }

    // Insert new group
    $stmt = $this->conn->prepare("INSERT INTO email_groups (name) VALUES (?)");
    $stmt->bind_param("s", $groupName);
    if ($stmt->execute()) {
        return $this->conn->insert_id;
    }

    error_log("Error in getOrCreateGroupId: " . $stmt->error);
    return false;
}
public function contactExists($groupId, $email) {
    $stmt = $this->conn->prepare("SELECT id FROM contacts WHERE group_id = ? AND email = ?");
    $stmt->bind_param("is", $groupId, $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

// Check if an email already exists in the group
public function isEmailExistsInGroup($groupId, $email) {
    $stmt = $this->conn->prepare("SELECT id FROM contacts WHERE group_id = ? AND email = ?");
    $stmt->bind_param("is", $groupId, $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}


// Save contact only if not duplicate
public function saveContactToGroup($groupId, $name, $email, $phone, $remark) {
    $stmt = $this->conn->prepare("INSERT INTO contacts (group_id, name, email, phone, remark) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $groupId, $name, $email, $phone, $remark);
    $stmt->execute();
}



public function getEmailsByGroupId($groupId) {
    $stmt = $this->conn->prepare("SELECT name, email FROM email_groups WHERE group_id = ?");
    $stmt->execute([$groupId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getContactsByGroupId($groupId) {
    $stmt = $this->conn->prepare("SELECT name, email, phone, remark FROM contacts WHERE group_id = ?");
    $stmt->bind_param("i", $groupId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}


public function getAllGroups() {
    $stmt = $this->conn->prepare("SELECT id, name FROM email_groups");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

public function getEmailLogs()
{
    $conn = $this->getConnection();
    $query = "SELECT * FROM email_logs ORDER BY sent_at DESC";
    $result = $conn->query($query);
    return $result;
}

public function getAllEmailGroups()
{
    $conn = $this->getConnection();
    $query = "SELECT id, group_name FROM email_groups ORDER BY group_name";
    $result = $conn->query($query);
    $groups = [];

    while ($row = $result->fetch_assoc()) {
        $groups[] = ['id' => $row['id'], 'name' => $row['group_name']];
    }

    return $groups;
}


public function getSocialMediaPlatformByUserId($userId)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT social_media FROM user_profiles WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // returns ['social_media' => 'platform']
}


public function getUserByEmail($email)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT id, full_name, email, phone, location, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $email, $phone, $location, $hashed_password);
        $stmt->fetch();

        return [
            'id' => $id,
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'location' => $location,
            'password' => $hashed_password
        ];
    }

    return null;
}

public function updateUserProfileByEmail($email, $full_name, $phone, $location)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, location = ? WHERE email = ?");
    $stmt->bind_param("ssss", $full_name, $phone, $location, $email);
    return $stmt->execute();
}

public function userExistsByEmailOrPhone($contact)
{
    $conn = $this->getConnection();

    if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
    }

    $stmt->bind_param("s", $contact);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}

public function updateOTPByContact($otp, $contact)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE users SET otp = ? WHERE email = ? OR phone = ?");
    $stmt->bind_param("iss", $otp, $contact, $contact);
    return $stmt->execute();
}

public function isEmailExists($email)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

public function registerUser($full_name, $email, $phone, $location, $hashedPassword)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, location, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $email, $phone, $location, $hashedPassword);
    return $stmt->execute();
}

public function getUserPasswordByEmail($email)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // returns ['password' => 'hashed']
}

public function updateUserPasswordByEmail($email, $newHashedPassword)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $newHashedPassword, $email);
    return $stmt->execute();
}

public function updateUserProfile($email, $full_name, $phone, $age, $location)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, age = ?, location = ? WHERE email = ?");
    $stmt->bind_param("sssss", $full_name, $phone, $age, $location, $email);
    return $stmt->execute();
}

public function getStoredOTPByContact($contact)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $contact, $contact);
    $stmt->execute();
    $stmt->bind_result($otp);
    $stmt->fetch();
    $stmt->close();
    return $otp; // returns null if not found
}

public function resetPasswordByContact($contact, $hashedPassword)
{
    $conn = $this->getConnection();
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ? OR phone = ?");
    $stmt->bind_param("sss", $hashedPassword, $contact, $contact);
    return $stmt->execute();
}
public function getUserByEmailOrPhone($input)
{
    $conn = $this->getConnection();

    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT * FROM users WHERE email = ?";
    } else {
        $query = "SELECT * FROM users WHERE phone = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // returns user row or null
}

    public function closeConnection() {
        $this->conn->close();
    }
}
?>
