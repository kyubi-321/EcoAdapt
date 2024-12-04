<?php
// Include necessary files
include 'header.php';  // Include header


// Assuming candidate_id is passed dynamically, e.g., from session or URL
$candidate_id = 1; // Replace with dynamic candidate_id

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $candidate_id = $_POST['candidate_id'];
    $education_level = $_POST['education_level'];
    $university_institute = $_POST['university_institute'];
    $course = $_POST['course'];
    $specialization = $_POST['specialization'];
    $course_type = $_POST['course_type'];
    $starting_year = $_POST['starting_year'];
    $ending_year = $_POST['ending_year'];

    // SQL query to insert new education details
    $query = "INSERT INTO candidate_education (candidate_id, education_level, university_institute, course, specialization, course_type, starting_year, ending_year) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and execute the SQL query
    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
        die('Error preparing SQL query: ' . $mysqli->error);
    }

    $stmt->bind_param("issssiiii", $candidate_id, $education_level, $university_institute, $course, $specialization, $course_type, $starting_year, $ending_year);
    $stmt->execute();

    // Redirect back to the profile page after submission
    header("Location: profile.php?candidate_id=" . $candidate_id);
    exit();
}

// SQL query to fetch education details for the specific candidate
$query = "SELECT education_level, university_institute, course, specialization, course_type, starting_year, ending_year 
          FROM candidate_education 
          WHERE candidate_id = ?";

// Prepare and execute the SQL query
$stmt = $mysqli->prepare($query);
if ($stmt === false) {
    die('Error preparing SQL query: ' . $mysqli->error); // Handle SQL error
}

$stmt->bind_param("i", $candidate_id); // Bind candidate_id parameter
$stmt->execute();  // Execute the query

// Get the result
$result = $stmt->get_result();

// Check if there is any education data
if ($result->num_rows > 0):
    // Loop through the result and display education details
    while ($education = $result->fetch_assoc()):
?>
        <!-- Education Details Section -->
        <div class="education-details">
            <h3>Education Information</h3>
            <!-- Button to Add More Education Details -->
            <button id="add-education-btn">Add More Education Details</button>
            
            <p><span class="label">Education Level:</span> <?= htmlspecialchars($education['education_level']) ?></p>
            <p><span class="label">University/Institute:</span> <?= htmlspecialchars($education['university_institute']) ?></p>
            <p><span class="label">Course:</span> <?= htmlspecialchars($education['course']) ?></p>
            <p><span class="label">Specialization:</span> <?= htmlspecialchars($education['specialization']) ?></p>
            <p><span class="label">Course Type:</span> <?= htmlspecialchars($education['course_type']) ?></p>
            <p><span class="label">Starting Year:</span> <?= htmlspecialchars($education['starting_year']) ?></p>
            <p><span class="label">Ending Year:</span> <?= htmlspecialchars($education['ending_year']) ?></p>
        </div>

<?php
    endwhile;
else:
    echo "<p>No education details available for this candidate.</p>";
endif;

// Close the prepared statement
$stmt->close();
?>

<!-- Modal for Adding Education Details -->
<div id="education-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Add Education Information</h3>
        <form action="profile.php" method="POST">
            <input type="hidden" name="candidate_id" value="<?= $candidate_id ?>">

            <label for="education_level">Education Level:</label>
            <input type="text" name="education_level" required><br>

            <label for="university_institute">University/Institute:</label>
            <input type="text" name="university_institute" required><br>

            <label for="course">Course:</label>
            <input type="text" name="course" required><br>

            <label for="specialization">Specialization:</label>
            <input type="text" name="specialization" required><br>

            <label for="course_type">Course Type:</label>
            <input type="text" name="course_type" required><br>

            <label for="starting_year">Starting Year:</label>
            <input type="number" name="starting_year" required><br>

            <label for="ending_year">Ending Year:</label>
            <input type="number" name="ending_year" required><br>

            <input type="submit" value="Add Education">
        </form>
    </div>
</div>

<?php
$mysqli->close();
?>

<!-- JavaScript to Toggle Modal -->
<script>
// Get modal and button
var modal = document.getElementById("education-modal");
var btn = document.getElementById("add-education-btn");
var span = document.getElementsByClassName("close")[0];

// Open the modal when the button is clicked
btn.onclick = function() {
    modal.style.display = "block";
}

// Close the modal when the user clicks on the 'x' (close) button
span.onclick = function() {
    modal.style.display = "none";
}

// Close the modal if the user clicks outside of the modal content
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
