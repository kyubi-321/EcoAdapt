<?php  
session_start();
include 'dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['candidate_id'])) {
    // Return an empty JSON response or a silent response without a message
    echo json_encode(['success' => false, 'logged_in' => false]);
    exit();
}


$candidate_id = $_SESSION['candidate_id'];
$job_id = $_POST['job_id'] ?? null;
$is_bookmarked = $_POST['is_bookmarked'] ?? null;

// If job_id and is_bookmarked are provided, process save/remove bookmark request
if ($job_id !== null && $is_bookmarked !== null) {
    if ($is_bookmarked == 1) {
        // Insert bookmark
        $stmt = $mysqli->prepare("INSERT INTO save_job (job_id, candidate_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE job_id = job_id");
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
            exit();
        }
        $stmt->bind_param("ii", $job_id, $candidate_id);
    } else {
        // Remove bookmark
        $stmt = $mysqli->prepare("DELETE FROM save_job WHERE job_id = ? AND candidate_id = ?");
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
            exit();
        }
        $stmt->bind_param("ii", $job_id, $candidate_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $mysqli->error]);
    }

    $stmt->close();
} else {
    // If no job_id and is_bookmarked, fetch saved jobs for the candidate
    $query = "SELECT job_id FROM save_job WHERE candidate_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $candidate_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $savedJobIds = [];
        while ($row = $result->fetch_assoc()) {
            $savedJobIds[] = $row['job_id'];
        }
        echo json_encode(['success' => true, 'savedJobIds' => $savedJobIds]);
    } else {
        echo json_encode(['success' => false, 'message' => $mysqli->error]);
    }
}
?>
