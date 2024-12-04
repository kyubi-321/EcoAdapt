<?php
// Include database connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
if(!isset($mysqli) || !isset($conn)){
    include "../dbconnect.php";

}


// Check if the skills data is sent via POST (for saving skills)
if (isset($_POST['skills'])) {
    header('Content-Type: application/json');
    // Decode the JSON-encoded skills array
    $skills = json_decode($_POST['skills'], true);
    $candidate_id = $_SESSION['candidate_id']; // Get the candidate ID from the session

    // Convert the skills array back into a comma-separated string
    $skillsString = implode(',', $skills);

    // Update the candidate's skills in the database
    $sql = "UPDATE candidate_it_skills SET skill_name = ? WHERE candidate_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("si", $skillsString, $candidate_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Skills updated successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating skills: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing SQL query: ' . $mysqli->error]);
    }
    exit();
}

// Query to fetch skills for the given candidate ID
$candidate_id = $_SESSION['candidate_id']; // Replace with session candidate ID
$sql = "SELECT skill_name FROM candidate_it_skills WHERE candidate_id = ?";

$skills = [];
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $candidate_id); // "i" for integer parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $skills = explode(',', $row['skill_name']); // Split skills into an array
    }
    $stmt->close();
}

?>

<div class="skills-container profile-summary" id="skillDetails">
    <h2>Skills
        <button id="edit-skills-btn" class="btn"><i class="fas fa-pencil-alt"></i></button>
    </h2>
    <ul class="skills-list" id="skills-list">
        <?php foreach ($skills as $skill) : ?>
            <li><?= htmlspecialchars(trim($skill)); ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Modal HTML -->
<div id="skills-modal" class="modal">
    <div class="modal-content">
        <!-- Use the new close-icon -->
        <span class="close-icon" onclick="closeModal()">&times;</span>
        <h2 class="modal-heading">Skills</h2>
        <ul id="skills-list-edit" class="skills-list">
            <!-- Skills will be populated by JavaScript -->
        </ul>
        <input type="text" id="new-skill-input" placeholder="Add a new skill" />
        <button id="add-skill-btn">Add</button>
    </div>
</div>


<script>

document.addEventListener('DOMContentLoaded', function () {
    var modalSkill = document.getElementById("skills-modal");
    var btn = document.getElementById("edit-skills-btn");
    var skillsList = <?= json_encode($skills); ?>;

        // Open modal when 'Edit Skills' button is clicked
        btn.onclick = function() {
            modalSkill.style.display = "block";
            loadSkills(); // Load current skills into the modal
        };


    // Function to close the modal
    function closeModal() {
        modalSkill.style.display = "none";
        window.location.reload("profile.php");
    }

    // Close modal if user clicks outside of it
    window.onclick = function (event) {
        if (event.target == modalSkill) {
            closeModal();
        }
    };

    // Expose closeModal globally for use in inline onclick
    window.closeModal = closeModal;

    // Load skills into the modal
    function loadSkills() {
        var skillsListEdit = document.getElementById("skills-list-edit");
        skillsListEdit.innerHTML = ""; // Clear existing list
        skillsList.forEach(function (skill, index) {
            var li = document.createElement("li");
            li.textContent = skill;

            // Add "X" button for each skill
            var deleteBtn = document.createElement("button");
            deleteBtn.textContent = "X";
            deleteBtn.classList.add("delete-skill-btn");

            // Handle skill deletion
            deleteBtn.onclick = function () {
                deleteSkill(index);
            };

            li.appendChild(deleteBtn);
            skillsListEdit.appendChild(li);
        });
    }

    // Function to delete a skill
    function deleteSkill(index) {
        skillsList.splice(index, 1);
        updateSkillsInDatabase();
        loadSkills();
    }

    // Add new skill when button is clicked
    document.getElementById("add-skill-btn").onclick = function () {
        var newSkill = document.getElementById("new-skill-input").value;
        if (newSkill) {
            skillsList.push(newSkill);
            updateSkillsInDatabase();
            document.getElementById("new-skill-input").value = "";
            loadSkills();
        }
    };

    // Function to update skills in the database using AJAX
    function updateSkillsInDatabase() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        console.log(response.message);
                    } else {
                        console.error(response.message);
                    }
                } catch (e) {
                    console.error("Invalid JSON response");
                }
            } else {
                console.error("Error updating skills");
            }
        };

        xhr.send("skills=" + encodeURIComponent(JSON.stringify(skillsList)));
    }
});

</script>

