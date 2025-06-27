document.addEventListener('DOMContentLoaded', function() {
    // School registration modal
    const schoolModal = document.getElementById('schoolModal');
    const registerSchoolBtn = document.getElementById('registerSchoolBtn');
    const closeModal = document.querySelector('.close');
    
    registerSchoolBtn.addEventListener('click', function() {
        schoolModal.style.display = 'block';
    });
    
    closeModal.addEventListener('click', function() {
        schoolModal.style.display = 'none';
    });
    
    window.addEventListener('click', function(event) {
        if (event.target === schoolModal) {
            schoolModal.style.display = 'none';
        }
    });
    
    // School registration form
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
                alert(data.message);
                // Add new school to select
                const option = document.createElement('option');
                option.value = data.schoolName;
                option.textContent = data.schoolName;
                document.getElementById('school').appendChild(option);
                // Select the new school
                document.getElementById('school').value = data.schoolName;
                // Close modal
                schoolModal.style.display = 'none';
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
    
    // Your existing JavaScript for form functionality
});
