<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($conn)) {
    include_once "../dbconnect.php"; // Adjust the path as necessary
}
$candidate_id = $_SESSION['candidate_id'];
// Fetch candidate details
$query = "SELECT * FROM candidate WHERE candidate_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $candidate_id);
$stmt->execute();
$result = $stmt->get_result();
$candidate = $result->fetch_assoc();




?>
<div class="profile-container" id="profileSubMenu">
    <div class="profile-card">
        <div class="profile-header">
            <!-- Profile Image -->
            <div class="profile-image">
                <img src="<?= htmlspecialchars(isset($candidate['profile_picture']) && !empty($candidate['profile_picture']) ? $candidate['profile_picture'] : 'img/PR10.jpg') ?>" alt="EcoAdopt">
            </div>
            <!-- Profile Info -->
            <div class="profile-info">
                <h3>Welcome, <?= htmlspecialchars($candidate['candidate_name']) ?>!</h3>
                <p><span class="label">Mobile:</span> <span class="data"><?= htmlspecialchars($candidate['candidate_mobile']) ?></span></p>
            </div>
        </div>
    </div>
</div>