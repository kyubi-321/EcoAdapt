document.addEventListener('DOMContentLoaded', function() {
    // Get modal and button elements
    const modal = document.getElementById('edit-education-modal');
    const editButton = document.getElementById('editeducationButton');
    const closeBtn = document.querySelector('.close-btn');

    // Ensure elements exist before adding event listeners
    if (editButton && modal && closeBtn) {
        // Open the modal when the edit button is clicked
        editButton.addEventListener('click', function() {
            modal.style.display = 'block';
        });

        // Close the modal when the close button (×) is clicked
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Close the modal when clicking outside the modal content
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    } else {
        console.log("Modal or Button elements not found.");
    }
});
