<?php
// Database configuration
$servername = "localhost";
$username = "your_username";
$password = "your_password";

// Create connection without selecting a database
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS digskills_db";
if ($conn->query($sql) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

// Select the database
$conn->select_db("digskills_db");

// Create enrollments table
$sql = "CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    country VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    lga VARCHAR(50) NOT NULL,
    address TEXT,
    program_category VARCHAR(50) NOT NULL,
    program VARCHAR(100) NOT NULL,
    school VARCHAR(100),
    how_heard VARCHAR(50),
    comments TEXT,
    supporting_docs TEXT,
    created_at DATETIME NOT NULL
)";

if ($conn->query($sql)) {
    echo "Enrollments table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Create schools table
$sql = "CREATE TABLE IF NOT EXISTS schools (
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
    created_at DATETIME NOT NULL,
    UNIQUE KEY unique_school (name, state)
)";

if ($conn->query($sql)) {
    echo "Schools table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

echo "Database setup complete!";
?>
