<?php
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "digskills_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Process school registration
$response = ['success' => false, 'message' => ''];

try {
    $schoolName = sanitizeInput($_POST['schoolName']);
    $schoolType = sanitizeInput($_POST['schoolType']);
    $schoolState = sanitizeInput($_POST['schoolState']);
    $schoolLga = sanitizeInput($_POST['schoolLga']);
    $schoolAddress = sanitizeInput($_POST['schoolAddress']);
    $schoolContact = isset($_POST['schoolContact']) ? sanitizeInput($_POST['schoolContact']) : null;
    $schoolEmail = isset($_POST['schoolEmail']) ? filter_var($_POST['schoolEmail'], FILTER_SANITIZE_EMAIL) : null;
    $schoolPhone = isset($_POST['schoolPhone']) ? sanitizeInput($_POST['schoolPhone']) : null;
    
    // Handle logo upload
    $logoPath = null;
    if (!empty($_FILES['schoolLogo']['name'])) {
        $uploadDir = "uploads/school_logos/";
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileExt = strtolower(pathinfo($_FILES['schoolLogo']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExt, $allowedExtensions)) {
            if ($_FILES['schoolLogo']['error'] === 0) {
                if ($_FILES['schoolLogo']['size'] <= 2097152) { // 2MB max
                    $fileName = uniqid('school_', true) . '.' . $fileExt;
                    $fileDest = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['schoolLogo']['tmp_name'], $fileDest)) {
                        $logoPath = $fileDest;
                    }
                }
            }
        }
    }
    
    // Insert into database
    $sql = "INSERT INTO schools (
        name, type, state, lga, address, contact_person, contact_email,
        contact_phone, logo_path, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssss",
        $schoolName, $schoolType, $schoolState, $schoolLga, $schoolAddress,
        $schoolContact, $schoolEmail, $schoolPhone, $logoPath
    );
    
    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'School registered successfully',
            'schoolName' => $schoolName
        ];
    } else {
        $response['message'] = 'Database error: ' . $conn->error;
    }
    
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();

echo json_encode($response);

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
require_once 'db_config.php';
?>
