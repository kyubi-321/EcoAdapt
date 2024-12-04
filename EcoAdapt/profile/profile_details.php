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

// Fetch profile details
$candidate_id = $_SESSION['candidate_id'];
$sql = "SELECT * FROM candidate WHERE candidate_id = $candidate_id";
$result = $conn->query($sql);
$profileDetails = $result->fetch_all(MYSQLI_ASSOC);

// Add profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_profile'])) {
    $candidate_name = $_POST['candidate_name'];
    $candidate_mobile = $_POST['candidate_mobile'];
    $candidate_email = $_POST['candidate_email'];
    $skill = $_POST['skill'];
    $location = $_POST['location'];
    $education = $_POST['education'];
    $industry = $_POST['industry'];
    $budget_min = $_POST['budget_min'];
    $budget_max = $_POST['budget_max'];
    $exp_max = $_POST['exp_max'];




    $insertQuery = "INSERT INTO candidate (candidate_id, candidate_name, candidate_mobile, candidate__email , skill , location , education , industry , budget_min , budget_max , exp_max) 
                    VALUES ($candidate_id, '$candidate_name', '$candidate_mobile', '$candidate__email' , '$skill','$location','$education','$indsutry','$budget_min','$budget_max','$exp_max')";
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

// Update profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $id = $_POST['id'];
    $candidate_name = $_POST['candidate_name'];
    $candidate_mobile = $_POST['candidate_mobile'];
    $candidate_email = $_POST['candidate_email'];
    $skill = $_POST['skill'];
    $location = $_POST['location'];
    $education = $_POST['education'];
    $industry = $_POST['industry'];
    $budget_min = $_POST['budget_min'];
    $budget_max = $_POST['budget_max'];
    $exp_max = $_POST['exp_max'];

    // Update the record in the database
    $updateQuery = "UPDATE candidate 
                    SET candidate_name='$candidate_name', candidate_mobile='$candidate_mobile', candidate_email='$candidate_email' ,skill='$skill',location='$location',education='$education',industry='$industry',budget_min='$budget_min',budget_max='$budget_max',exp_max='$exp_max'
                        
                    WHERE candidate_id=$id";
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


// Delete profile record
if (isset($_GET['delete_profile'])) {
    $id = $_GET['delete_profile'];

    // Delete the record from the database
    $deleteQuery = "DELETE FROM candidate WHERE candidate_id = $id";
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





<div id="profileDetails" class="profile-summary">

    <?php foreach ($profileDetails as $profile): ?>

        <div id="profile-head-edit-delete-btns">
            <h2>Profile</h2>
            <div id="profile-edit-delete-btns">
                <!-- Edit Button with Bootstrap Icon -->
                <button class="btn btn-success btn-sm rounded-pill" onclick="openEditProfileModal(<?= htmlspecialchars(json_encode($profile), ENT_QUOTES, 'UTF-8') ?>)">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>

            </div>
        </div>

        <div class="profile-detail-row">
            <span><strong>Candidate Name:</strong></span>
            <span><?= $profile['candidate_name'] ?></span>
        </div>
        <div class="profile-detail-row">
            <span><strong>candidate Mobile:</strong></span>
            <span><?= $profile['candidate_mobile'] ?></span>
        </div>
        <div class="profile-detail-row">
            <span><strong>Candidate Email:</strong></span>
            <span><?= $profile['candidate_email'] ?></span>
        </div>
        <div class="profile-detail-row">
            <span><strong>Skill:</strong></span>
            <span><?= $profile['skill'] ?></span>
        </div>
        <div class="profile-detail-row">
            <span><strong>Location:</strong></span>
            <span><?= $profile['location'] ?></span>
        </div>
        <div class="profile-detail-row">
            <span><strong>Education:</strong></span>
            <span><?= $profile['education'] ?></span>
        </div>
        <div class="profile-detail-row">
            <span><strong>Industry:</strong></span>
            <span><?= $profile['industry'] ?></span>
        </div>
        <div class="profile-detail-row">
            <span><strong>Budget Min.:</strong></span>
            <span><?= $profile['budget_min'] ?></span>
        </div>
        <div class="profile-detail-row">
            <span><strong>Budget Max.:</strong></span>
            <span><?= $profile['budget_max'] ?></span>
        </div>
        
        <div class="profile-detail-row">
            <span><strong>Exp max.:</strong></span>
            <span><?= $profile['exp_max'] ?></span>
        </div>


    <?php endforeach; ?>
</div>

<!-- Edit Modal -->
<div id="profile-overlay" onclick="setupCloseProfileModalOnClick()"></div>
<?php foreach ($profileDetails as $profile): ?>
    <div id="edit-profile-modal">
        <h1>Edit profile</h1>
        <button class="close-btn" onclick="closeEditProfileModal()">&times;</button>
        <form method="POST" action="">
            <input type="hidden" name="id" id="edit-profile-id">
            <input type="text" name="candidate_name" id="edit-candidate-name" placeholder="Candidate Name" value="<?= $profile['candidate_name'] ?>">
            <input type="text" name="candidate_mobile" id="edit-candidate-mobile" placeholder="Candidate Mobile" value="<?= $profile['candidate_mobile'] ?>" readonly>

            <input type="text" name="candidate_email" id="edit-candidate-email" placeholder="candidate Email" value="<?= $profile['candidate_email'] ?>" readonly>

            <input type="text" name="skill" id="edit-skill" placeholder="Candidate Skill" value="<?= $profile['skill'] ?>">


            <input type="text" name="location" id="edit-location" placeholder="Candidate Location" value="<?= $profile['location'] ?>">

            <input type="text" name="education" id="edit-education" placeholder="Candidate Education" value="<?= $profile['education'] ?>">


            <input type="text" name="industry" id="edit-industry" placeholder="Candidate Industry" value="<?= $profile['industry'] ?>">


            <input type="number" name="budget_min" id="edit-budget-min" placeholder="Candidate Budget Min." value="<?= $profile['budget_min'] ?>" min="0">

            <input type="number" name="budget_max" id="edit-budget-max" placeholder="Candidate Budget Max." value="<?= $profile['budget_max'] ?>" min="0">

            <input type="number" name="exp_max" id="edit-exp-max" placeholder="Candidate Exp Max." value="<?= $profile['exp_max'] ?>" min="0">
            <button type="submit" name="update_profile">Update</button>
        </form>
    </div>
<?php endforeach; ?>