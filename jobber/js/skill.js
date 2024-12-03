
// Get the modal and button elements
var modal = document.getElementById('edit-skills-modal');
var openModalBtn = document.getElementById('edit-skills-btn');
var closeModalBtn = document.getElementsByClassName('close-btn')[0];

// Open the modal when the "Edit Skills" button is clicked
openModalBtn.onclick = function() {
    modal.style.display = 'block';
}

// Close the modal when the close button (×) is clicked
closeModalBtn.onclick = function() {
    modal.style.display = 'none';
}

// Close the modal when the user clicks anywhere outside the modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Handle the deletion of skills
document.querySelectorAll('.delete-skill-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var skillItem = button.closest('.skill-item');
        skillItem.remove(); // Remove the skill item from the list
    });
});

// Add new skill input dynamically
document.getElementById('add-skill-btn').onclick = function() {
    var skillInputContainer = document.getElementById('skill-input-container');
    var newSkillInput = document.createElement('input');
    newSkillInput.type = 'text';
    newSkillInput.name = 'skill_names[]';
    newSkillInput.placeholder = 'Add your skills here...';
    skillInputContainer.appendChild(newSkillInput);
}

