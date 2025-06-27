<?php
// Database configuration
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "digskills_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $firstName = sanitizeInput($_POST['firstName']);
    $lastName = sanitizeInput($_POST['lastName']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = sanitizeInput($_POST['phone']);
    $country = sanitizeInput($_POST['country']);
    $state = sanitizeInput($_POST['state']);
    $lga = sanitizeInput($_POST['lga']);
    $address = sanitizeInput($_POST['address']);
    $programCategory = sanitizeInput($_POST['programCategory']);
    $program = sanitizeInput($_POST['program']);
    $school = isset($_POST['school']) ? sanitizeInput($_POST['school']) : null;
    $howHeard = sanitizeInput($_POST['howHeard']);
    $comments = sanitizeInput($_POST['comments']);
    
    // Handle file upload
    $supportingDocs = [];
    if (!empty($_FILES['supportingDocs']['name'][0])) {
        $uploadDir = "uploads/supporting_docs/";
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        foreach ($_FILES['supportingDocs']['name'] as $key => $name) {
            $fileTmp = $_FILES['supportingDocs']['tmp_name'][$key];
            $fileSize = $_FILES['supportingDocs']['size'][$key];
            $fileError = $_FILES['supportingDocs']['error'][$key];
            $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            
            if (in_array($fileExt, $allowedExtensions)) {
                if ($fileError === 0) {
                    if ($fileSize <= 5242880) { // 5MB max
                        $fileName = uniqid('', true) . '.' . $fileExt;
                        $fileDest = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($fileTmp, $fileDest)) {
                            $supportingDocs[] = $fileDest;
                        }
                    }
                }
            }
        }
    }
    
    // Insert into database
    $sql = "INSERT INTO enrollments (
        first_name, last_name, email, phone, country, state, lga, address,
        program_category, program, school, how_heard, comments, supporting_docs,
        created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $docsJson = json_encode($supportingDocs);
    $stmt->bind_param(
        "ssssssssssssss",
        $firstName, $lastName, $email, $phone, $country, $state, $lga, $address,
        $programCategory, $program, $school, $howHeard, $comments, $docsJson
    );
    
    if ($stmt->execute()) {
        // Send confirmation email
        sendConfirmationEmail($email, $firstName);
        
        // Redirect to success page
        header("Location: enrollment_success.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendConfirmationEmail($to, $name) {
    $subject = "DigSkills Enrollment Confirmation";
    $message = "
    <html>
    <head>
        <title>DigSkills Enrollment Confirmation</title>
    </head>
    <body>
        <h2>Thank you for enrolling, $name!</h2>
        <p>We've received your enrollment form and will process it shortly.</p>
        <p>You'll receive another email with program details within 48 hours.</p>
        <p>If you have any questions, please contact support@digskills.com</p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@digskills.com" . "\r\n";
    
    mail($to, $subject, $message, $headers);
}
?>
