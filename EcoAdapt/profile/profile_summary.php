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

// Fetch summary details
$candidate_id = $_SESSION['candidate_id'];
$sql = "SELECT * FROM profile_summary WHERE candidate_id = $candidate_id";
$result = $conn->query($sql);
$summaryDetails = $result->fetch_all(MYSQLI_ASSOC);

// Add summary details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_summary'])) {
    $pro_summary = $_POST['pro_summary'];





    $insertQuery = "INSERT INTO profile_summary (candidate_id, pro_summary) 
                    VALUES ($candidate_id, '$pro_summary')";
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

// Update summary details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_summary'])) {
    $id = $_POST['id'];
    $pro_summary = $_POST['pro_summary'];


    // Update the record in the database
    $updateQuery = "UPDATE profile_summary 
                    SET pro_summary='$pro_summary'
                        
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


// Delete summary record
if (isset($_GET['delete_summary'])) {
    $id = $_GET['delete_summary'];

    // Delete the record from the database
    $deleteQuery = "DELETE FROM candidate WHERE id = $id";
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


<div id="profileSummaryDetails" class="profile-summary">
    <?php if (!empty($summaryDetails)): // Check if there are any summary details 
    ?>
        <?php foreach ($summaryDetails as $summary): ?>
            <div id="summary-head-edit-delete-btns">
                <h2>Profile Summary</h2>

                <div id="summary-edit-delete-btns">
                    <!-- Edit Button with Bootstrap Icon -->
                    <button class="btn btn-success btn-sm rounded-pill" onclick="openEditSummaryModal(<?= htmlspecialchars(json_encode($summary), ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                </div>
            </div>

            <div class="summary-detail-row">
                <span><?= $summary['pro_summary'] ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: // If no summary details exist, display the "Add" button 
    ?>
        <div id="summary-head-edit-delete-btns">
            <h2>Profile Summary</h2>

            <div id="summary-edit-delete-btns">
                <!-- Larger and rounded Add Button -->
                <button class="btn btn-success btn-sm rounded-pill edit-btn" onclick="openAddSummaryModal()">
                    <i class="bi bi-plus-circle"></i> Add
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>


<!-- Edit Modal -->
<div id="summary-overlay" onclick="setupCloseSummaryModalOnClick()"></div>
<?php foreach ($summaryDetails as $summary): ?>
    <div id="edit-summary-modal">
        <h1>Edit summary</h1>
        <button class="close-btn" onclick="closeEditSummaryModal()">&times;</button>
        <form method="POST" action="">
            <input type="hidden" name="id" id="edit-summary-id">
            <textarea type="text" name="pro_summary" id="edit-profile-summary" placeholder="Profile Summary" value="<?= $summary['pro_summary'] ?>" maxlength="2500"></textarea>

            <button type="submit" name="update_summary">Update</button>
        </form>
    </div>
<?php endforeach; ?>


<!-- Add Modal -->
<div id="add-summary-modal">
    <h1>Add Profile Summary</h1>
    <button class="close-btn" onclick="closeAddSummaryModal()">&times;</button>
    <form method="POST" action="">
        <textarea name="pro_summary" placeholder="Profile Summary" maxlength="2500"></textarea>
        <button type="submit" name="add_summary">Add</button>
    </form>
</div>