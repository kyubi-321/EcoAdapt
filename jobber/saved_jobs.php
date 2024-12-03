<?php
// Start session to track logged-in status
session_start();

// Check if the user is logged in
if (!isset($_SESSION['candidate_id'])) {
    // If not logged in, redirect to login page
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Pagination logic
$limit = 20; // Number of jobs per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page, default is page 1
$offset = ($page - 1) * $limit; // Calculate the offset for the query

// Get candidate_id from session
$candidate_id = $_SESSION['candidate_id'];

// Start building the query with filters
$query = "SELECT s.*, j.posted_date_time, j.job_title, j.location, j.exp_min, j.exp_max, j.budget_min, j.budget_max
          FROM save_job s
          JOIN jobs j ON s.job_id = j.job_id
          WHERE s.candidate_id = $candidate_id
          ORDER BY j.posted_date_time DESC
          LIMIT $offset, $limit";

// Execute the query
$result = $mysqli->query($query);

// Check for query errors
if (!$result) {
    die('Query Error: ' . $mysqli->error);
}

// Function to generate job cards
function generateJobCards($result)
{
    echo "<h1>Saved Jobs</h1>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $saved = true; // The job is saved, since it's in the save_job table

            echo '<div class="job-item p-4 mb-4">';
            echo '<div class="row g-4">';

            // Job company logo (replace 'img/com-logo-2.jpg' with a dynamic path if necessary)
            echo '<div class="col-sm-12 col-md-8 d-flex align-items-center">';
            echo '<img class="flex-shrink-0 img-fluid border rounded" src="img/com-logo-2.jpg" alt="" style="width: 80px; height: 80px;">';
            echo '<div class="text-start ps-4">';

            // Job title
            echo '<h5 class="mb-3">' . htmlspecialchars($row['job_title']) . '</h5>';

            // Location, experience, and salary
            echo '<span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>' . htmlspecialchars($row['location']) . '</span>';
            echo '<span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>' . htmlspecialchars($row['exp_min'] . '-' . $row['exp_max']) . ' yr</span>';
            echo '<span class="text-truncate me-0"><i class="far fa-money-bill-alt text-primary me-2"></i>INR ' . htmlspecialchars($row['budget_min'] . '-' . $row['budget_max']) . '/ year</span>';

            echo '</div>';
            echo '</div>';

            // Buttons for job details and application
            echo '<div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">';
            echo '<div class="d-flex mb-3">';

            // If job is saved, show red heart, else show the default heart icon
            $heartClass = $saved ? 'fa-heart text-danger' : 'fa-heart-o text-primary';

            echo '<a class="btn btn-light btn-square me-3 save-job-btn" href="javascript:void(0);" data-job-id="' . htmlspecialchars($row['job_id']) . '"><i class="fa ' . $heartClass . '"></i></a>';
            echo '<a class="btn btn-primary" href="job-detail.php?jobid=' . htmlspecialchars($row['job_id']) . '">Apply Now</a>';
            echo '</div>';

            // Date formatting and posting info
            $originalDateTime = $row['posted_date_time'];
            $date = new DateTime($originalDateTime);
            $formattedDate = $date->format('m-d-Y');
            echo '<small class="text-truncate"><i class="far fa-calendar-alt text-primary me-2"></i>Posted on: ' . htmlspecialchars($formattedDate) . '</small>';
            echo '</div>';

            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No job listings available.</p>';
    }
}

// Get total number of jobs for pagination
$totalQuery = "SELECT COUNT(*) AS total_jobs
               FROM save_job s
               JOIN jobs j ON s.job_id = j.job_id
               WHERE s.candidate_id = $candidate_id";
$totalResult = $mysqli->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalJobs = $totalRow['total_jobs'];
$totalPages = ceil($totalJobs / $limit); // Calculate total pages

// Check if it's an AJAX request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // If AJAX request, only return job listings
    generateJobCards($result);
} else {
    // Else, include full page structure
    include 'header.php';

?>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
            <?php include 'top_menu.php'; ?>
        </nav>
        <!-- Navbar End -->
        <!-- Jobs Section Start -->
        <div class="content-wrapper d-flex  ">
            <!-- Sidebar Start -->
            <div class="col-md-3">
                <?php include 'sidebar.php'; ?> <!-- Include the filter sidebar -->
            </div>
            <!-- Sidebar End -->

            <!-- Main Content Start -->
            <div id="jobs_desc" class="col-md-9 main-panel " style="margin-top: 20px;">
                <?php //include 'jobsearchbar.php'   
                ?>
                <div class="container card-container" style="margin-top: 20px;">
                    <div class="row" id="job-listings-row"> <!-- Added id for dynamic updates -->
                        <?php generateJobCards($result); ?>
                    </div> <!-- Close row div -->
                </div> <!-- Close container -->

                <!-- Pagination -->
                <div class="pagination">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <!-- Main Content End -->
        </div>
        <!-- Jobs Section End -->
    </div>

    <!-- Footer Start -->
<?php

}
?>
<!-- Footer End -->