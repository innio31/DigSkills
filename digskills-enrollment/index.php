<?php 
require_once 'includes/db_config.php';

// Fetch schools from database for the dropdown
$schools = [];
$schoolQuery = $conn->query("SELECT name FROM schools ORDER BY name");
if ($schoolQuery) {
    while ($row = $schoolQuery->fetch_assoc()) {
        $schools[] = $row['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigSkills Enrollment Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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

                <div class="form-group">
                    <label for="supportingDocs">Supporting Documents (Transcripts, Certificates, etc.)</label>
                    <input type="file" id="supportingDocs" name="supportingDocs[]" class="form-control" multiple>
                    <p class="help-text">You can upload multiple files (PDF, DOC, JPG, PNG) - Max 5MB each</p>
                </div>
            </fieldset>
            
            <!-- Program Details Section -->
            <fieldset>
                <legend>Program Information</legend>
                
                <div class="form-group">
                    <label for="programCategory" class="required">Program Category</label>
                    <select id="programCategory" name="programCategory" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="secondary">Secondary Schools Program</option>
                        <option value="undergrad">Undergraduates/School Leavers</option>
                        <option value="online">Distance Learning</option>
                        <option value="professional">Professional Programs</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="program" class="required">Program</label>
                    <select id="program" name="program" class="form-control" required disabled>
                        <option value="">Select a category first</option>
                    </select>
                </div>
                
                <div id="schoolContainer" class="form-group hidden">
                    <label for="school">School</label>
                    <select id="school" name="school" class="form-control">
                        <option value="">Select Your School</option>
                        <?php foreach ($schools as $school): ?>
                            <option value="<?= htmlspecialchars($school) ?>"><?= htmlspecialchars($school) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" id="registerSchoolBtn" class="btn btn-secondary">Register New School</button>
                </div>
                
                <div class="form-group">
                    <label for="howHeard">How did you hear about us?</label>
                    <select id="howHeard" name="howHeard" class="form-control">
                        <option value="">Select Option</option>
                        <option value="social">Social Media</option>
                        <option value="friend">Friend/Family</option>
                        <option value="school">School</option>
                        <option value="search">Search Engine</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="comments">Additional Comments</label>
                    <textarea id="comments" name="comments" class="form-control" rows="3"></textarea>
                </div>
            </fieldset>
            
            <div class="form-group">
                <input type="checkbox" id="agreeTerms" name="agreeTerms" required>
                <label for="agreeTerms">I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Privacy Policy</a></label>
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
                <div class="form-group">
                    <label for="schoolName" class="required">School Name</label>
                    <input type="text" id="schoolName" name="schoolName" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="schoolType" class="required">School Type</label>
                    <select id="schoolType" name="schoolType" class="form-control" required>
                        <option value="">Select Type</option>
                        <option value="public">Public School</option>
                        <option value="private">Private School</option>
                        <option value="international">International School</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="schoolState" class="required">State</label>
                        <select id="schoolState" name="schoolState" class="form-control" required>
                            <option value="">Select State</option>
                            <!-- States will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="schoolLga" class="required">LGA</label>
                        <select id="schoolLga" name="schoolLga" class="form-control" required>
                            <option value="">Select LGA</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="schoolAddress" class="required">School Address</label>
                    <textarea id="schoolAddress" name="schoolAddress" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="schoolContact">Contact Person</label>
                    <input type="text" id="schoolContact" name="schoolContact" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="schoolEmail">Contact Email</label>
                    <input type="email" id="schoolEmail" name="schoolEmail" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="schoolPhone">Contact Phone</label>
                    <input type="tel" id="schoolPhone" name="schoolPhone" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="schoolLogo">School Logo (Optional)</label>
                    <input type="file" id="schoolLogo" name="schoolLogo" class="form-control" accept="image/*">
                    <p class="help-text">Max 2MB (JPG, PNG)</p>
                </div>
                
                <button type="submit" class="btn">Register School</button>
            </form>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <script>
        // Nigerian States and LGAs Data
        const nigeriaData = {
            "Lagos": ["Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Epe", "Eti-Osa", "Ibeju-Lekki", "Ifako-Ijaiye", "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland", "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"],
            "Ogun": ["Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Egbado North", "Egbado South", "Ewekoro", "Ifo", "Ijebu East", "Ijebu North", "Ijebu North East", "Ijebu Ode", "Ikenne", "Imeko Afon", "Ipokia", "Obafemi Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North", "Shagamu"],
            "Oyo": ["Afijio", "Akinyele", "Atiba", "Atisbo", "Egbeda", "Ibadan North", "Ibadan North-East", "Ibadan North-West", "Ibadan South-East", "Ibadan South-West", "Ibarapa Central", "Ibarapa East", "Ibarapa North", "Ido", "Irepo", "Iseyin", "Itesiwaju", "Iwajowa", "Kajola", "Lagelu", "Ogbomosho North", "Ogbomosho South", "Ogo Oluwa", "Olorunsogo", "Oluyole", "Ona Ara", "Orelope", "Ori Ire", "Oyo East", "Oyo West", "Saki East", "Saki West", "Surulere"]
        };

        // Programs by Category
        const programsByCategory = {
            "secondary": [
                "Digital Literacy Bootcamp",
                "Coding Foundations",
                "STEM Projects"
            ],
            "undergrad": [
                "Full-Stack Development",
                "Data Analysis",
                "Cybersecurity Fundamentals"
            ],
            "online": [
                "Digital Marketing",
                "Cloud Computing",
                "UI/UX Design"
            ],
            "professional": [
                "Tech for Managers",
                "Data-Driven Decision Making",
                "Cybersecurity for Executives"
            ]
        };

        // DOM Elements
        const countrySelect = document.getElementById('country');
        const stateSelect = document.getElementById('state');
        const lgaSelect = document.getElementById('lga');
        const programCategorySelect = document.getElementById('programCategory');
        const programSelect = document.getElementById('program');
        const schoolContainer = document.getElementById('schoolContainer');
        const schoolSelect = document.getElementById('school');
        const schoolModal = document.getElementById('schoolModal');
        const registerSchoolBtn = document.getElementById('registerSchoolBtn');
        const closeModal = document.querySelector('.close');
        const schoolStateSelect = document.getElementById('schoolState');
        const schoolLgaSelect = document.getElementById('schoolLga');

        // Populate States
        function populateStates(selectElement) {
            const select = document.getElementById(selectElement);
            select.innerHTML = '<option value="">Select State</option>';
            Object.keys(nigeriaData).forEach(state => {
                const option = document.createElement('option');
                option.value = state;
                option.textContent = state;
                select.appendChild(option);
            });
        }

        // Populate LGAs based on state
        function populateLgas(stateSelectElement, lgaSelectElement) {
            const stateSelect = document.getElementById(stateSelectElement);
            const lgaSelect = document.getElementById(lgaSelectElement);
            const selectedState = stateSelect.value;
            
            lgaSelect.innerHTML = '<option value="">Select LGA</option>';
            
            if (selectedState && nigeriaData[selectedState]) {
                nigeriaData[selectedState].forEach(lga => {
                    const option = document.createElement('option');
                    option.value = lga;
                    option.textContent = lga;
                    lgaSelect.appendChild(option);
                });
            }
        }

        // Populate programs based on category
        programCategorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            programSelect.innerHTML = '<option value="">Select Program</option>';
            programSelect.disabled = !selectedCategory;
            
            if (selectedCategory && programsByCategory[selectedCategory]) {
                programsByCategory[selectedCategory].forEach(program => {
                    const option = document.createElement('option');
                    option.value = program;
                    option.textContent = program;
                    programSelect.appendChild(option);
                });
                
                // Show/hide school selection based on category
                if (selectedCategory === 'secondary') {
                    schoolContainer.classList.remove('hidden');
                } else {
                    schoolContainer.classList.add('hidden');
                }
            }
        });

        // School registration modal
        registerSchoolBtn.addEventListener('click', function() {
            schoolModal.classList.remove('hidden');
            populateStates('schoolState');
        });

        closeModal.addEventListener('click', function() {
            schoolModal.classList.add('hidden');
        });

        window.addEventListener('click', function(event) {
            if (event.target === schoolModal) {
                schoolModal.classList.add('hidden');
            }
        });

        // School registration form submission
        document.getElementById('schoolRegistrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('register_school.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('School registered successfully!');
                    
                    // Add the new school to the select options
                    const option = document.createElement('option');
                    option.value = data.schoolName;
                    option.textContent = data.schoolName;
                    schoolSelect.appendChild(option);
                    
                    // Select the new school
                    schoolSelect.value = data.schoolName;
                    
                    // Close the modal
                    schoolModal.classList.add('hidden');
                    this.reset();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            // For Nigeria by default
            if (countrySelect.value === 'Nigeria') {
                populateStates('state');
            }
            
            // Handle country change
            countrySelect.addEventListener('change', function() {
                if (this.value === 'Nigeria') {
                    populateStates('state');
                } else {
                    stateSelect.innerHTML = '<option value="">Select State/Region</option>';
                    lgaSelect.innerHTML = '<option value="">Select District</option>';
                }
            });
            
            // State change handler for main form
            stateSelect.addEventListener('change', function() {
                populateLgas('state', 'lga');
            });
            
            // State change handler for school registration
            schoolStateSelect.addEventListener('change', function() {
                populateLgas('schoolState', 'schoolLga');
            });
        });

        // Form submission
        document.getElementById('enrollmentForm').addEventListener('submit', function(e) {
            // Client-side validation can be added here
            // The actual processing will be done by process_enrollment.php
        });
    </script>
</body>
</html>
