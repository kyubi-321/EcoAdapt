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

// Fetch experience details
$candidate_id = $_SESSION['candidate_id'];
$sql = "SELECT * FROM experience WHERE candidate_id = $candidate_id";
$result = $conn->query($sql);
$experienceDetails = $result->fetch_all(MYSQLI_ASSOC);

// Add experience details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_experience'])) {
    $company_name = $_POST['company_name'];
    $job_title = $_POST['job_title'];
    $joining_date = $_POST['joining_date'];
    $working_till = $_POST['working_till'];
    $work_experience = $_POST['work_experience'];
    $salary = $_POST['salary'];
    $skills_used = $_POST['skills_used'];
    $job_profile = $_POST['job_profile'];
    $company_location = $_POST['company_location'];


    $insertQuery = "INSERT INTO experience (candidate_id, CompanyName, JobTitle, JoiningDate, WorkingTill, Experience, Salary, SkillsUsed, JobProfile , companyLocation) 
                    VALUES ($candidate_id, '$company_name', '$job_title', '$joining_date', '$working_till', '$work_experience', '$salary', '$skills_used', '$job_profile' , '$company_location')";
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

// Update experience details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_experience'])) {
    $id = $_POST['id'];
    $company_name = $_POST['company_name'];
    $job_title = $_POST['job_title'];
    $joining_date = $_POST['joining_date'];
    $working_till = $_POST['working_till'];
    $work_experience = $_POST['work_experience'];
    $salary = $_POST['salary'];
    $skills_used = $_POST['skills_used'];
    $job_profile = $_POST['job_profile'];
    $company_location = $_POST['company_location'];

    // Update the record in the database
    $updateQuery = "UPDATE experience 
                    SET CompanyName='$company_name', JobTitle='$job_title', JoiningDate='$joining_date', 
                        WorkingTill='$working_till', Experience='$work_experience', 
                        Salary='$salary', SkillsUsed='$skills_used', JobProfile='$job_profile' , companyLocation='$company_location' 
                    WHERE companyID=$id";
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


// Delete experience record
if (isset($_GET['delete_experience'])) {
    $id = $_GET['delete_experience'];

    // Delete the record from the database
    $deleteQuery = "DELETE FROM experience WHERE companyID = $id";
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






<div id="experienceDetails" class="profile-summary">
    <h2>Experience
        <!-- Larger and rounded Add Button -->
        <button class="btn btn-success btn-sm rounded-pill edit-btn" onclick="openAddExperienceModal()">
            <i class="bi bi-plus-circle"></i> Add
        </button>

    </h2>
    <?php foreach ($experienceDetails as $experience): ?>
        <div class="experience-details">
            <div id="experience-head-edit-delete-btns">
                <h2>
                    <?= $experience['CompanyName'] ?>

                </h2>
                <div id="experience-edit-delete-btns">
                    <!-- Edit Button with Bootstrap Icon -->
                    <button class="btn btn-success btn-sm rounded-pill" onclick="openEditExperienceModal(<?= htmlspecialchars(json_encode($experience), ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                    <!-- Delete Button with Bootstrap Icon -->
                    <button class="btn btn-danger btn-sm rounded-pill" onclick="deleteExperience(<?= $experience['companyID'] ?>)">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
            <div class="experience-detail-row">
                <span><strong>Job Title:</strong></span>
                <span><?= $experience['JobTitle'] ?></span>
            </div>
            <div class="experience-detail-row">
                <span><strong>Joining Date:</strong></span>
                <span><?= $experience['JoiningDate'] ?></span>
            </div>
            <div class="experience-detail-row">
                <span><strong>Working Till:</strong></span>
                <span><?= $experience['WorkingTill'] ?></span>
            </div>
            <div class="experience-detail-row">
                <span><strong>Work Experience(in years):</strong></span>
                <span><?= $experience['Experience'] ?> years</span>
            </div>
            <div class="experience-detail-row">
                <span><strong>Salary:</strong></span>
                <span><?= $experience['Salary'] ?></span>
            </div>
            <div class="experience-detail-row">
                <span><strong>Skills:</strong></span>
                <span><?= $experience['SkillsUsed'] ?></span>
            </div>
            <div class="experience-detail-row">
                <span><strong>Job Description:</strong></span>
                <span><?= $experience['JobProfile'] ?></span>
            </div>
            <div class="experience-detail-row">
                <span><strong>Company Location:</strong></span>
                <span><?= $experience['companyLocation'] ?></span>
            </div>

        </div>
    <?php endforeach; ?>
</div>

<!-- Edit Modal -->
<div id="experience-overlay" onclick="setupCloseExperienceModalOnClick()"></div>
<?php foreach ($experienceDetails as $experience): ?>
    <div id="edit-experience-modal">
        <h1>Edit Experience</h1>
        <button class="close-btn" onclick="closeEditExperienceModal()">&times;</button>
        <form method="POST" action="">
            <input type="hidden" name="id" id="edit-exp-id">
            <input type="text" name="company_name" id="edit-company-name" placeholder="Company Name" value="<?= $experience['CompanyName'] ?>">
            <input type="text" name="job_title" id="edit-job-title" placeholder="Job Title" value="<?= $experience['JobTitle'] ?>">
            <input type="date" name="joining_date" id="edit-joining-date" placeholder="Job Title" value="<?= $experience['JobTitle'] ?>">
            <input type="date" name="working_till" id="edit-working-till" placeholder="Working Till" value="<?= $experience['WorkingTill'] ?>">
            <input type="text" name="work_experience" id="edit-work-experience" placeholder="Work Experience(in years)" value="<?= $experience['Experience'] ?>">
            <input type="text" name="salary" id="edit-salary" placeholder="Salary" value="<?= $experience['Salary'] ?>">
            <input type="text" name="skills_used" id="edit-skills" placeholder="Skills" value="<?= $experience['SkillsUsed'] ?>">
            <input type="text" name="job_profile" id="edit-job-profile" placeholder="Job Description" value="<?= $experience['JobProfile'] ?>">
            <input type="text" name="company_location" id="edit-company-location" placeholder="Company Location" value="<?= $experience['companyLocation'] ?>">

            <button type="submit" name="update_experience">Update</button>
        </form>
    </div>
<?php endforeach; ?>

<!-- Add Modal -->
<div id="add-experience-modal">
    <h1>Add Experience</h1>
    <button class="close-btn" onclick="closeAddExperienceModal()">&times;</button>
    <form method="POST" action="" id="experience-form">
        <input type="text" name="company_name" placeholder="Company Name">
        <input type="text" name="job_title" placeholder="Job Title">
        <input type="date" name="joining_date" id="joining_date" placeholder="Joining date" onchange="updateWorkingTillMinDate(); calculateWorkExperience();">
        <input type="date" name="working_till" id="working_till" placeholder="Working Till" onchange="calculateWorkExperience();">
        <input type="text" name="work_experience" id="work_experience" placeholder="Work Experience(in years)" readonly>
        <input type="number" name="salary" placeholder="Salary" min="0" step="0.001">
        <input type="text" name="skills_used" placeholder="Skills">
        <input type="text" name="job_profile" placeholder="Job Description">
        <input type="text" name="company_location" placeholder="Company Location">

        <button type="submit" name="add_experience">Add</button>
    </form>
</div>

