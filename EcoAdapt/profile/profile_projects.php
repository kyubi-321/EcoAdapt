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

// Fetch project details
$candidate_id = $_SESSION['candidate_id'];
$sql = "SELECT * FROM project_details WHERE candidate_id = $candidate_id";
$result = $conn->query($sql);
$projectDetails = $result->fetch_all(MYSQLI_ASSOC);

// Add project details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_project'])) {
    $project_title = $_POST['project_title'];
    $project_summary = $_POST['project_summary'];
    $project_status = $_POST['project_status'];
    $project_duration = $_POST['project_duration'];


    $insertQuery = "INSERT INTO project_details (candidate_id, project_title, project_summary, project_status, project_duration) 
                    VALUES ($candidate_id, '$project_title', '$project_summary', '$project_status', '$project_duration')";
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

// Update project details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_project'])) {
    $id = $_POST['id'];
    $project_title = $_POST['project_title'];
    $project_summary = $_POST['project_summary'];
    $project_status = $_POST['project_status'];
    $project_duration = $_POST['project_duration'];

    // Update the record in the database
    $updateQuery = "UPDATE project_details 
                    SET project_title='$project_title', project_summary='$project_summary', project_status='$project_status', 
                        project_duration='$project_duration' 
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


// Delete project record
if (isset($_GET['delete_project'])) {
    $id = $_GET['delete_project'];

    // Delete the record from the database
    $deleteQuery = "DELETE FROM project_details WHERE id = $id";
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






<div id="projectDetails" class="profile-summary">
    <h2>Projects
        <!-- Larger and rounded Add Button -->
        <button class="btn btn-success btn-sm rounded-pill edit-btn" onclick="openAddProjectModal()">
            <i class="bi bi-plus-circle"></i> Add
        </button>

    </h2>
    <?php foreach ($projectDetails as $project): ?>
        <div class="project-details">
            <div id="project-head-edit-delete-btns">
                <h2>
                    <?= $project['project_title'] ?>

                </h2>
                <div id="project-edit-delete-btns">
                    <!-- Edit Button with Bootstrap Icon -->
                    <button class="btn btn-success btn-sm rounded-pill" onclick="openEditProjectModal(<?= htmlspecialchars(json_encode($project), ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                    <!-- Delete Button with Bootstrap Icon -->
                    <button class="btn btn-danger btn-sm rounded-pill" onclick="deleteProject(<?= $project['id'] ?>)">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
           
            <div class="project-detail-row">
                <span><strong>Project Summary:</strong></span>
                <span><?= $project['project_summary'] ?></span>
            </div>
            <div class="project-detail-row">
                <span><strong>Project Status:</strong></span>
                <span><?= $project['project_status'] ?></span>
            </div>
            <div class="project-detail-row">
                <span><strong>Project Duration(in months):</strong></span>
                <span><?= $project['project_duration'] ?> months</span>
            </div>

        </div>
    <?php endforeach; ?>
</div>

<!-- Edit Modal -->
<div id="project-overlay" onclick="setupCloseProjectModalOnClick()"></div>
<?php foreach ($projectDetails as $project): ?>
    <div id="edit-project-modal">
        <h1>Edit Project</h1>
        <button class="close-btn" onclick="closeEditProjectModal()">&times;</button>
        <form method="POST" action="">
            <input type="hidden" name="id" id="edit-proj-id">
            <input type="text" name="project_title" id="edit-project-title" placeholder="Project Title" value="<?= $project['project_title'] ?>">
            <textarea type="text" name="project_summary" id="edit-summary" placeholder="Project Summary" value="<?= $project['project_summary'] ?>" maxlength="2000"></textarea>
            <select name="project_status" id="edit-status" placeholder="Project Status">
                <option value="" disabled>Select Project Status</option>
                <option value="pending" <?= $project['project_status'] == 'pending' ? 'selected' : '' ?>>pending</option>
                <option value="ongoing" <?= $project['project_status'] == 'ongoing' ? 'selected' : '' ?>>ongoing</option>
                <option value="completed" <?= $project['project_status'] == 'completed' ? 'selected' : '' ?>>completed</option>

            </select>
            <input type="number" name="project_duration" id="edit-duration" placeholder="Project Duration(in months)" value="<?= $project['project_duration'] ?>" min="0">

            <button type="submit" name="update_project">Update</button>
        </form>
    </div>
<?php endforeach; ?>

<!-- Add Modal -->
<div id="add-project-modal">
    <h1>Add Project</h1>
    <button class="close-btn" onclick="closeAddProjectModal()">&times;</button>
    <form method="POST" action="">
        <input type="text" name="project_title" placeholder="Project Title">
        <textarea name="project_summary" placeholder="Project Summary"></textarea>
        <select name="project_status" placeholder="Project Status">
            <option value="" disabled>Select Project Status</option>
            <option value="pending">pending</option>
            <option value="ongoing">ongoing</option>
            <option value="completed">completed</option>

        </select>
        <input type="number" name="project_duration" placeholder="Project Duration(in months)" min="0">
        <button type="submit" name="add_project">Add</button>
    </form>
</div>

