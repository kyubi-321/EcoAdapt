<?php
// Start the session
session_start();

// Mock session data (for testing, replace with actual session management in production)
if (!isset($_SESSION['candidate_id'])) {
    echo "No candidate logged in.";
    exit;
}

// Get the candidate_id from the session
$candidate_id = $_SESSION['candidate_id'];

// Database connection (replace with your database credentials)
include "dbconnect.php";

// Fetch applied jobs with company_name and job_title for the logged-in candidate
$sql = "SELECT 
            ja.job_id, 
            ja.candidate_name, 
            ja.candidate_mail, 
            ja.resume_path, 
            ja.cover_letter, 
            ja.created_time, 
            ja.contact_no,
            j.company_name, 
            j.job_title
        FROM 
            job_applications AS ja
        INNER JOIN 
            jobs AS j 
        ON 
            ja.job_id = j.job_id
        WHERE 
            ja.candidate_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $candidate_id);
$stmt->execute();
$result = $stmt->get_result();



// Check if the candidate has applied for any jobs
if ($result->num_rows > 0) {
    echo "<h1>Applied Jobs</h1>";


    // Display each job application
    while ($row = $result->fetch_assoc()) {

        echo '<div class="job-item p-4 mb-4">';
        echo '<div class="row g-4">';
        echo '<div class="col-sm-12 col-md-8 d-flex align-items-center">';
        echo '<img class="flex-shrink-0 img-fluid border rounded" src="img/com-logo-2.jpg" alt="" style="width: 80px; height: 80px;">';
        echo '<div class="text-start ps-4">';
        // Job title
        echo '<h5 class="mb-3">' . htmlspecialchars($row['job_title']) . '</h5>';

        // Location, experience, and salary
        echo '<span class="text-truncate me-3"><strong>Name</strong> - ' . htmlspecialchars($row['candidate_name']) . '</span>';
        echo '<span class="text-truncate me-3"><strong>Gmail</strong> - ' . htmlspecialchars($row['candidate_mail']) . '</span>';
        echo '<span class="text-truncate me-0"><a href="' . htmlspecialchars($row["resume_path"]) . '" target="_blank">Resume Link</a></span>';

        echo '</div>';
        echo '</div>';

        // Buttons for job details and application
        echo '<div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">';
        echo '<div class="d-flex mb-3">';








        echo '<a class="btn btn-primary" href="job-detail.php?jobid=' . htmlspecialchars($row['job_id']) . '" target="_blank">View Job</a>';
        echo '</div>';

        echo '<small class="text-truncate"><i class="far fa-calendar-alt text-primary me-2"></i>Applied on: ' . htmlspecialchars($row['created_time']) . '</small>';
        echo '</div>';

        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<h1>Applied Jobs</h1>";
    echo "<p>No jobs found for this candidate.</p>";
}



// Close the statement and connection
$stmt->close();
$conn->close();
