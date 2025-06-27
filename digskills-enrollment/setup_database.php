<?php
require_once 'includes/db_config.php';

$tables = [
    "CREATE TABLE IF NOT EXISTS enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        country VARCHAR(50) NOT NULL DEFAULT 'Nigeria',
        state VARCHAR(50) NOT NULL,
        lga VARCHAR(50) NOT NULL,
        address TEXT,
        program_category VARCHAR(50) NOT NULL,
        program VARCHAR(100) NOT NULL,
        school VARCHAR(100),
        how_heard VARCHAR(50),
        comments TEXT,
        supporting_docs TEXT,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_program (program)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    "CREATE TABLE IF NOT EXISTS schools (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        type VARCHAR(20) NOT NULL,
        state VARCHAR(50) NOT NULL,
        lga VARCHAR(50) NOT NULL,
        address TEXT NOT NULL,
        contact_person VARCHAR(100),
        contact_email VARCHAR(100),
        contact_phone VARCHAR(20),
        logo_path VARCHAR(255),
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_school (name, state)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

try {
    foreach ($tables as $sql) {
        if ($conn->query($sql) === FALSE) {
            throw new Exception("Error creating table: " . $conn->error);
        }
    }
    echo "Database setup completed successfully!";
} catch (Exception $e) {
    die("Database setup failed: " . $e->getMessage());
}

$conn->close();
?>
