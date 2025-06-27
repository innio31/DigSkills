<?php
header('Content-Type: application/json');
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

$response = ['success' => false, 'message' => ''];

try {
    $schoolName = sanitizeInput($_POST['schoolName']);
    
    // Check if school already exists
    $check = $conn->prepare("SELECT id FROM schools WHERE name = ? AND state = ?");
    $check->bind_param("ss", $schoolName, $_POST['schoolState']);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        throw new Exception("This school is already registered in the selected state");
    }
    
    // Process logo upload
    $logoPath = null;
    if (!empty($_FILES['schoolLogo']['name'])) {
        $logoPath = uploadFile($_FILES['schoolLogo'], 0, "uploads/school_logos/", 
            ['jpg', 'jpeg', 'png'], 2097152);
    }
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO schools (...) VALUES (...)");
    
    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'School registered successfully',
            'schoolName' => $schoolName
        ];
    } else {
        throw new Exception("Database error: " . $stmt->error);
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>
