<?php
// Start output buffering
ob_start();

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
if (!isset($conn)) {
    include_once "../dbconnect.php";
}

$endYear = 2100; 
$startYear = 1900;
// Fetch education details
$candidate_id = $_SESSION['candidate_id'];
$sql = "SELECT * FROM candidate_education WHERE candidate_id = $candidate_id";
$result = $conn->query($sql);
$educationDetails = $result->fetch_all(MYSQLI_ASSOC);

// Add education details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_education'])) {
    $education_level = $_POST['education_level'];
    $university = $_POST['university_institute'];
    $course = $_POST['course'];
    $specialization = $_POST['specialization'];
    $course_type = $_POST['course_type'];
    $starting_year = $_POST['starting_year'];
    $ending_year = $_POST['ending_year'];
    $grading_system = $_POST['grading_system'];
    $grade = $_POST['grade'];

    $insertQuery = "INSERT INTO candidate_education (candidate_id, education_level, university_institute, course, specialization, course_type, starting_year, ending_year, grading_system,grade) 
                    VALUES ($candidate_id, '$education_level', '$university', '$course', '$specialization', '$course_type', '$starting_year', '$ending_year', '$grading_system','$grade')";
    if ($conn->query($insertQuery)) {
        // Check if headers are already sent, and if not, perform redirection
        if (!headers_sent()) {
            header("Location: profile.php");
            exit();
        } else {

            echo "<script>window.location = 'profile.php';</script>";
            exit();
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

// Update education details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_education'])) {
    $id = $_POST['id'];
    $education_level = $_POST['education_level'];
    $university = $_POST['university_institute'];
    $course = $_POST['course'];
    $specialization = $_POST['specialization'];
    $course_type = $_POST['course_type'];
    $starting_year = $_POST['starting_year'];
    $ending_year = $_POST['ending_year'];
    $grading_system = $_POST['grading_system'];
    $grade = $_POST['grade'];

    // Update the record in the database
    $updateQuery = "UPDATE candidate_education 
                    SET education_level='$education_level', university_institute='$university', course='$course', 
                        specialization='$specialization', course_type='$course_type', 
                        starting_year='$starting_year', ending_year='$ending_year', grading_system='$grading_system',grade='$grade'
                    WHERE id=$id";
    if ($conn->query($updateQuery)) {
        // Check if headers are already sent, and if not, perform redirection
        if (!headers_sent()) {
            header("Location: profile.php");
            exit();
        } else {
            echo "<script>window.location = 'profile.php';</script>";
            exit();
        }
    } else {
        echo "Error: " . $conn->error;
    }
}


// Delete education record
if (isset($_GET['delete_education'])) {
    $id = $_GET['delete_education'];

    // Delete the record from the database
    $deleteQuery = "DELETE FROM candidate_education WHERE id = $id";
    if ($conn->query($deleteQuery)) {
        if (!headers_sent()) {
            header("Location: profile.php");
            exit();
        } else {
            echo "<script>window.location = 'profile.php';</script>";
            exit();
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

// End output buffering and send the output
ob_end_flush();
?>




<div id="educationDetails" class="profile-summary">
    <h2>Education
        <!-- Larger and rounded Add Button -->
        <button class="btn btn-success btn-sm rounded-pill edit-btn" onclick="openAddEducationModal()">
            <i class="bi bi-plus-circle"></i> Add
        </button>

    </h2>
    <?php foreach ($educationDetails as $education): ?>
        <div class="education-details">
            <div id="education-head-edit-delete-btns">
                <h2>
                    <?= $education['education_level'] ?>

                </h2>
                <div id="education-edit-delete-btns">
                    <!-- Edit Button with Bootstrap Icon -->
                    <button class="btn btn-success btn-sm rounded-pill" onclick="openEditModal(<?= htmlspecialchars(json_encode($education), ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                    <!-- Delete Button with Bootstrap Icon -->
                    <button class="btn btn-danger btn-sm rounded-pill" onclick="deleteEducation(<?= $education['id'] ?>)">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>

            </div>

            <div class="education-detail-row">
                <span><strong>Institute:</strong></span>
                <span><?= $education['university_institute'] ?></span>
            </div>
            <div class="education-detail-row">
                <span><strong>Course:</strong></span>
                <span><?= $education['course'] ?></span>
            </div>
            <div class="education-detail-row">
                <span><strong>Specialization:</strong></span>
                <span><?= $education['specialization'] ?></span>
            </div>
            <div class="education-detail-row">
                <span><strong>Type:</strong></span>
                <span><?= $education['course_type'] ?></span>
            </div>
            <div class="education-detail-row">
                <span><strong>Years:</strong></span>
                <span><?= $education['starting_year'] ?> - <?= $education['ending_year'] ?></span>
            </div>
            <div class="education-detail-row">
                <span><strong>Grading:</strong></span>
                <span><?= $education['grading_system'] ?></span>
            </div>
            <div class="education-detail-row">
                <span><strong><?php if ($education['grading_system'] === "CGPA") {
                                    echo "CGPA";
                                } else {
                                    echo "Percentage";
                                } ?>:</strong></span>
                <span><?= $education['grade'] ?><?php if ($education['grading_system'] === "CGPA") {
                                                    echo "";
                                                } else {
                                                    echo "%";
                                                } ?></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Edit Modal -->
<div id="overlay" onclick="setupCloseEducationModalOnClick()"></div>
<?php foreach ($educationDetails as $education): ?>
    <div id="edit-education-modal">
        <h1>Edit Education</h1>
        <button class="close-btn" onclick="closeEditEducationModal()">&times;</button>
        <form method="POST" action="">
            <input type="hidden" name="id" id="edit-id">
            <select name="education_level" id="edit-education_level">
                <option value="" disabled>Select Education Level</option>
                <option value="10th" <?= $education['education_level'] == '10th' ? 'selected' : '' ?>>10th</option>
                <option value="12th" <?= $education['education_level'] == "12th" ? 'selected' : '' ?>>12th</option>
                <option value="Diploma" <?= $education['education_level'] == "Diploma" ? 'selected' : '' ?>>Diploma</option>
                <option value="Graduation" <?= $education['education_level'] == 'Graduation' ? 'selected' : '' ?>>Graduation</option>
                <option value="Post Graduation" <?= $education['education_level'] == 'Post Graduation' ? 'selected' : '' ?>>Post Graduation</option>
                <option value="PHD" <?= $education['education_level'] == 'PHD' ? 'selected' : '' ?>>PHD</option>
            </select>
            <input type="text" name="university_institute" id="edit-university" placeholder="University/Institute" value="<?= $education['university_institute'] ?>">
            <input type="text" name="course" id="edit-course" placeholder="Course" value="<?= $education['course'] ?>">
            <input type="text" name="specialization" id="edit-specialization" placeholder="Specialization" value="<?= $education['specialization'] ?>">
            <input type="text" name="course_type" id="edit-course_type" placeholder="Course Type" value="<?= $education['course_type'] ?>">
            <?php
            // Generate years dynamically
            $endYear = 2100; // Set future year limit
            $startYear = 1900; // Adjust this as per your requirement
            ?>

            <label for="edit-starting-year">Starting Year</label>
            <select name="starting_year" id="edit-starting_year">
                <option value="">Select Starting Year</option>
                <?php for ($year = $startYear; $year <= $endYear; $year++): ?>
                    <option value="<?= $year ?>" <?= $education['starting_year'] == $year ? 'selected' : '' ?>><?= $year ?></option>
                <?php endfor; ?>
            </select>

            <label for="edit-ending_year">Ending Year</label>
            <select name="ending_year" id="edit-ending_year">
                <option value="">Select Ending Year</option>
                <?php for ($year = $startYear; $year <= $endYear; $year++): ?>
                    <option value="<?= $year ?>" <?= $education['ending_year'] == $year ? 'selected' : '' ?>><?= $year ?></option>
                <?php endfor; ?>
            </select>

            <select name="grading_system" id="edit-grading_system" placeholder="Grading System">
                <option value="" disabled>Select Grading System</option>
                <option value="CGPA" <?= $education['grading_system'] == 'CGPA' ? 'selected' : '' ?>>CGPA</option>
                <option value="Percentage" <?= $education['grading_system'] == 'Percentage' ? 'selected' : '' ?>>Percentage</option>

            </select>
            <input type="number" name="grade" id="edit-grade" placeholder="Grade" min="0" step="0.01" value="<?= $education['grade'] ?>">
            <button type="submit" name="update_education">Update</button>
        </form>
    </div>
<?php endforeach; ?>

<!-- Add Modal -->
<div id="add-modal">
    <h1>Add Education</h1>
    <button class="close-btn" onclick="closeAddEducationModal()">&times;</button>
    <form method="POST" action="">
        <select name="education_level" placeholder="Education Level">
            <option value="" disabled selected>Select Education Level</option>
            <option value="10th">10th</option>
            <option value="12th">12th</option>
            <option value="Diploma">Diploma</option>
            <option value="Graduation">Graduation</option>
            <option value="Post Graduation">Post Graduation</option>
            <option value="PHD">PHD</option>
        </select>

        <input type="text" name="university_institute" placeholder="University/Institute">
        <input type="text" name="course" placeholder="Course">
        <input type="text" name="specialization" placeholder="Specialization">
        <input type="text" name="course_type" placeholder="Course Type">
        <input type="number" name="starting_year" placeholder="Starting Year">
        <input type="number" name="ending_year" placeholder="Ending Year">
        <select name="grading_system" placeholder="Grading System">
            <option value="" disabled selected>Select Grading System</option>
            <option value="CGPA">CGPA</option>
            <option value="Percentage">Percentage</option>
        </select>
        <input type="number" name="grade" placeholder="Grade" min="0" step="0.01">


        <button type="submit" name="add_education">Add</button>
    </form>
</div>