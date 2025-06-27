<?php require_once 'includes/db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigSkills Enrollment Form</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="enrollment-container">
        <div class="form-header">
            <img src="assets/images/logo.png" alt="DigSkills Logo">
            <h1>Program Enrollment Form</h1>
            <p>Fill out this form to register for DigSkills programs</p>
        </div>
        
        <form id="enrollmentForm" action="process_enrollment.php" method="POST" enctype="multipart/form-data">
               <!-- Contact Details Section -->
            <fieldset>
                <legend>Contact Information</legend>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName" class="required">First Name</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName" class="required">Last Name</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="required">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="required">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="country" class="required">Country</label>
                    <select id="country" name="country" class="form-control" required>
                        <option value="">Select Country</option>
                        <option value="Nigeria" selected>Nigeria</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="state" class="required">State</label>
                        <select id="state" name="state" class="form-control" required>
                            <option value="">Select State</option>
                            <!-- States will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lga" class="required">Local Government</label>
                        <select id="lga" name="lga" class="form-control" required>
                            <option value="">Select LGA</option>
                            <!-- LGAs will be populated based on state selection -->
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Full Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3"></textarea>
                </div>
            </fieldset>
                
                <!-- Other form fields remain the same as your original -->
                
                <div class="form-group">
                    <label for="supportingDocs">Supporting Documents</label>
                    <input type="file" id="supportingDocs" name="supportingDocs[]" class="form-control" multiple>
                    <p class="help-text">Max 5MB per file (PDF, DOC, JPG, PNG)</p>
                </div>
            </fieldset>
            
            <!-- Program Information Section -->
            <fieldset>
                <legend>Program Information</legend>
                
                <!-- Your existing program fields -->
                
                <div id="schoolContainer" class="form-group hidden">
                    <label for="school" class="required">School</label>
                    <select id="school" name="school" class="form-control">
                        <option value="">Select Your School</option>
                        <?php
                        $schools = $conn->query("SELECT name FROM schools ORDER BY name");
                        while ($school = $schools->fetch_assoc()) {
                            echo "<option value='{$school['name']}'>{$school['name']}</option>";
                        }
                        ?>
                    </select>
                    <button type="button" id="registerSchoolBtn" class="btn">Register New School</button>
                </div>
            </fieldset>
            
            <div class="form-group">
                <input type="checkbox" id="agreeTerms" name="agreeTerms" required>
                <label for="agreeTerms">I agree to the terms and conditions</label>
            </div>
            
            <button type="submit" class="btn btn-block">Submit Enrollment</button>
        </form>
    </div>

    <!-- School Registration Modal -->
    <div id="schoolModal" class="modal hidden">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Register a New School</h2>
            <form id="schoolRegistrationForm" enctype="multipart/form-data">
                <!-- School form fields -->
                <div class="form-group">
                    <label for="schoolName" class="required">School Name</label>
                    <input type="text" id="schoolName" name="schoolName" class="form-control" required>
                </div>
                
                <!-- Other school fields -->
                
                <button type="submit" class="btn">Register School</button>
            </form>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
