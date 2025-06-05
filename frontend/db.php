<?php
class MySqlDB {
    private $host = 'localhost';
    private $db = 'ai_marketing';
    private $user = 'root';
    private $pass = '';
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

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





    public function closeConnection() {
        $this->conn->close();
    }
}
?>
