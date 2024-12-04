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

// Fetch social details
$candidate_id = $_SESSION['candidate_id'];
$sql = "SELECT * FROM candidate WHERE candidate_id = $candidate_id";
$result = $conn->query($sql);
$socialDetails = $result->fetch_all(MYSQLI_ASSOC);

// Add social details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_social'])) {
    $github = $_POST['Github'];
    $linkedin = $_POST['LinkedIn'];
    $other = $_POST['Other'];


    $insertQuery = "INSERT INTO candidate (candidate_id, Github, LinkedIn, Other) 
                    VALUES ($candidate_id, '$github', '$linkedin', '$other')";
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

// Update social details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_social'])) {
    $id = $_POST['id'];
    $github = $_POST['Github'];
    $linkedin = $_POST['LinkedIn'];
    $other = $_POST['Other'];

    // Update the record in the database
    $updateQuery = "UPDATE candidate 
                    SET Github='$github', LinkedIn='$linkedin', Other='$other' 
                        
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


// Delete social record
if (isset($_GET['delete_social'])) {
    $id = $_GET['delete_social'];

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





<div id="socialConnectDetails" class="profile-summary">
    <?php foreach ($socialDetails as $social): ?>

        <div id="social-head-edit-delete-btns">
            <h2>Social</h2>

            <div id="social-edit-delete-btns">
                <!-- Edit Button with Bootstrap Icon -->
                <button class="btn btn-success btn-sm rounded-pill" onclick="openEditSocialModal(<?= htmlspecialchars(json_encode($social), ENT_QUOTES, 'UTF-8') ?>)">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>

            </div>
        </div>
        <div class="social-detail-row">
            <span><strong>Github Profile Link:</strong></span>
            <span>
                <?php if (!empty($social['Github']) && filter_var($social['Github'], FILTER_VALIDATE_URL)): ?>
                    <a href="<?= $social['Github'] ?>" target="_blank" rel="noopener noreferrer"><?= $social['Github'] ?></a>
                <?php else: ?>
                    <em>Not Provided</em>
                <?php endif; ?>
            </span>
        </div>
        <div class="social-detail-row">
            <span><strong>LinkedIn Profile Link:</strong></span>
            <span>
                <?php if (!empty($social['LinkedIn']) && filter_var($social['LinkedIn'], FILTER_VALIDATE_URL)): ?>
                    <a href="<?= $social['LinkedIn'] ?>" target="_blank" rel="noopener noreferrer"><?= $social['LinkedIn'] ?></a>
                <?php else: ?>
                    <em>Not Provided</em>
                <?php endif; ?>
            </span>
        </div>
        <div class="social-detail-row">
            <span><strong>Other Profile Link:</strong></span>
            <span>
                <?php if (!empty($social['Other']) && filter_var($social['Other'], FILTER_VALIDATE_URL)): ?>
                    <a href="<?= $social['Other'] ?>" target="_blank" rel="noopener noreferrer"><?= $social['Other'] ?></a>
                <?php else: ?>
                    <em>Not Provided</em>
                <?php endif; ?>
            </span>
        </div>



    <?php endforeach; ?>
</div>

<!-- Edit Modal -->
<div id="social-overlay" onclick="setupCloseSocialModalOnClick()"></div>
<?php foreach ($socialDetails as $social): ?>
    <div id="edit-social-modal">
        <h1>Edit Social</h1>
        <button class="close-btn" onclick="closeEditSocialModal()">&times;</button>
        <form method="POST" action="">
            <input type="hidden" name="id" id="edit-social-id">
            <input type="text" name="Github" id="edit-github" placeholder="Github  profile link" value="<?= $social['Github'] ?>">
            <input type="text" name="LinkedIn" id="edit-linkedin" placeholder="LinkedIn profile link" value="<?= $social['LinkedIn'] ?>">

            <input type="text" name="Other" id="edit-other" placeholder="Other profile link" value="<?= $social['Other'] ?>">

            <button type="submit" name="update_social">Update</button>
        </form>
    </div>
<?php endforeach; ?>