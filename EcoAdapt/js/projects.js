document.getElementById("projectButton").addEventListener("click", function() {
    // Open the modal
    document.getElementById("edit-project-modal").style.display = "block";
});

// Close the modal when the close button is clicked
document.querySelector(".close-btn").addEventListener("click", function() {
    document.getElementById("edit-project-modal").style.display = "none";
});

// Close the modal if user clicks outside of it
window.addEventListener("click", function(event) {
    if (event.target == document.getElementById("edit-project-modal")) {
        document.getElementById("edit-project-modal").style.display = "none";
    }
});
document.getElementById('delete-project-btn').addEventListener('click', function() {
    if (confirm('Are you sure you want to delete this project?')) {
        // Redirect to delete_project.php or send AJAX request
        window.location.href = `delete_project.php?candidate_id=<?= $row['candidate_id'] ?>`;
    }
});
