-- Create Database
CREATE DATABASE IF NOT EXISTS ai_marketing;
USE ai_marketing;

Create Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    age INT NOT NULL,
    location VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Data (Optional)
INSERT INTO users (full_name, email, phone, age, location, password) 
VALUES 
('John Doe', 'john@example.com', '1234567890', 25, 'New York', '$2y$10$abcdefg12345hashedpasswordexample');
