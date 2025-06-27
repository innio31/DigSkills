<?php
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $firstName = sanitizeInput($_POST['firstName']);
    $lastName = sanitizeInput($_POST['lastName']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    // Process file uploads
    $uploadedFiles = [];
    if (!empty($_FILES['supportingDocs']['name'][0])) {
        $uploadDir = "uploads/supporting_docs/";
        createDirectory($uploadDir);
        
        foreach ($_FILES['supportingDocs']['name'] as $key => $name) {
            $uploadedFiles[] = uploadFile($_FILES['supportingDocs'], $key, $uploadDir, 
                ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'], 5242880);
        }
    }
    
    // Prepare and execute database insertion
    $stmt = $conn->prepare("INSERT INTO enrollments (...) VALUES (...)");
    $supportingDocsJson = json_encode($uploadedFiles);
    
    if ($stmt->execute()) {
        sendConfirmationEmail($email, $firstName);
        header("Location: enrollment_success.php");
        exit();
    } else {
        error_log("Database error: " . $stmt->error);
        die("An error occurred. Please try again later.");
    }
}

$conn->close();
?>
