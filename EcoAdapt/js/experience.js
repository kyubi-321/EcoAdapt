document.addEventListener("DOMContentLoaded", function() {
    // Get all edit buttons
    const editButtons = document.querySelectorAll(".edit-experience-btn");

    // Add click event listener to each edit button
    editButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            // Get the data attributes from the clicked button
            // const candidate_id = this.getAttribute("data-id");
            const companyName = this.getAttribute("data-company");
            const jobTitle = this.getAttribute("data-job");
            const joiningDate = this.getAttribute("data-joining");
            const workingTill = this.getAttribute("data-working");
            const experience = this.getAttribute("data-experience");
            const salary = this.getAttribute("data-salary");
            const skillsUsed = this.getAttribute("data-skills");
            const jobProfile = this.getAttribute("data-profile");
            const companyLocation = this.getAttribute("data-location");

            // Populate the form fields with the data
            // document.getElementById("candidate_id").value = candidate_id;
            document.getElementById("companyName").value = companyName;
            document.getElementById("jobTitle").value = jobTitle;
            document.getElementById("joiningDate").value = joiningDate;
            document.getElementById("workingTill").value = workingTill;
            document.getElementById("experience").value = experience;
            document.getElementById("salary").value = salary;
            document.getElementById("skillsUsed").value = skillsUsed;
            document.getElementById("jobProfile").value = jobProfile;
            document.getElementById("companyLocation").value = companyLocation;

            // Show the modal
            document.getElementById("experienceModal").style.display = "block";
        });
    });

    // Close the modal when the close button is clicked
    document.getElementById("closeModal").addEventListener("click", function() {
        document.getElementById("experienceModal").style.display = "none";
    });

    // Close the modal if the user clicks outside of the modal content
    window.addEventListener("click", function(event) {
        if (event.target == document.getElementById("experienceModal")) {
            document.getElementById("experienceModal").style.display = "none";
        }
    });
});
