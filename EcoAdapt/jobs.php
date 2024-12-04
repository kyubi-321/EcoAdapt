<?php
//Ankit Badhani did work on 22/11/2024

// Start session to track logged-in status
session_start();


// Include header
include 'header.php';

// Initialize filter variables
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location = isset($_GET['locationDetail']) && $_GET['locationDetail'] != '' ? $_GET['locationDetail'] : null;
$experience = isset($_GET['experienceDetail']) && $_GET['experienceDetail'] != '' ? $_GET['experienceDetail'] : null;
// Initialize filter variables
$locationFilters = isset($_GET['location']) ? $_GET['location'] : [];
$educationFilters = isset($_GET['education']) ? $_GET['education'] : [];
$industryFilters = isset($_GET['industry']) ? $_GET['industry'] : [];
$skillsFilters = isset($_GET['skills']) ? $_GET['skills'] : [];
$experienceFilters = isset($_GET['experience']) ? $_GET['experience'] : [];
$postedDateFilters = isset($_GET['posted_date']) ? $_GET['posted_date'] : [];

// I am making the query with filters
$query = "SELECT * FROM jobs WHERE job_status = 'open'";


// Pagination logic
$limit = 20; // Number of jobs per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page, default is page 1
$offset = ($page - 1) * $limit; // Calculate the offset for the query

// Adding keyword filter which was not included here correctly at first(only if provided)
if (!empty($keyword)) {
    $escapedKeyword = $mysqli->real_escape_string($keyword);
    $query .= " AND (job_title LIKE '%$escapedKeyword%' 
                 OR skills LIKE '%$escapedKeyword%' 
                 OR company_name LIKE '%$escapedKeyword%')";
}

// Location filter (only if provided)
if ($location) {
    $escapedLocation = $mysqli->real_escape_string($location);
    $query .= " AND location = '$escapedLocation'";
}

// Experience filter (only if provided)
if ($experience !== null) {
    $query .= " AND (exp_min <= $experience AND exp_max >= $experience)";
}



// Location filter
if (!empty($locationFilters)) {
    $query .= " AND location IN ('" . implode("','", array_map([$mysqli, 'real_escape_string'], $locationFilters)) . "')";
}

// Education filter
if (!empty($educationFilters)) {
    $query .= " AND education IN ('" . implode("','", array_map([$mysqli, 'real_escape_string'], $educationFilters)) . "')";
}

// Industry filter
if (!empty($industryFilters)) {
    $query .= " AND industry IN ('" . implode("','", array_map([$mysqli, 'real_escape_string'], $industryFilters)) . "')";
}

// Skills filter
if (!empty($skillsFilters)) {
    foreach ($skillsFilters as $skill) {
        $query .= " AND skills LIKE '%" . $mysqli->real_escape_string($skill) . "%'";
    }
}

// Experience filter
if (!empty($experienceFilters)) {
    if (in_array('experienced', $experienceFilters)) {
        $query .= " AND exp_min > 2"; // Assuming experienced requires more than 2 years
    }
    if (in_array('entryLevel', $experienceFilters)) {
        $query .= " AND exp_max <= 2"; // Assuming entry level requires at most 2 years
    }
}

// Job Posting Date Filter
if (!empty($postedDateFilters)) {
    $dateConditions = [];
    if (in_array('7_days', $postedDateFilters)) {
        $dateConditions[] = "posted_date_time >= NOW() - INTERVAL 7 DAY";
    }
    if (in_array('15_days', $postedDateFilters)) {
        $dateConditions[] = "posted_date_time >= NOW() - INTERVAL 15 DAY";
    }
    if (!empty($dateConditions)) {
        $query .= " AND (" . implode(" OR ", $dateConditions) . ")"; // Use OR for multiple date filters
    }
}

// Add pagination to the query
$query .= " ORDER BY posted_date_time DESC LIMIT $offset, $limit";

// Execute the query
$result = $mysqli->query($query);

// Check for query errors
if (!$result) {
    die('Query Error: ' . $mysqli->error);
}


// Function to generate job cards
function generateJobCards($result)
{
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
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
            echo '<a class="btn btn-light btn-square me-3 save-job-btn" href="javascript:void(0);" data-job-id="' . htmlspecialchars($row['job_id']) . '"><i class="far fa-heart text-primary"></i></a>';
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
$totalQuery = "SELECT COUNT(*) AS total_jobs FROM jobs WHERE job_status = 'open'";
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
    <?php
        if (isset($_SESSION['candidate_id'])) {
            // If 'candidate_id' exists in session, include 'top_menu.php'
            include 'top_menu.php';
        } else {
            // If 'candidate_id' does not exist, include 'topmenu.php'
            include 'topmenu.php';
        }
        ?>
    </nav>
    <!-- Navbar End -->
    <!-- Jobs Section Start -->
    <div class="content-wrapper d-flex  " >
        <!-- Sidebar Start -->
        <div class="col-md-3">
            <?php include 'job_filter.php'; ?> <!-- Include the filter sidebar -->
        </div>
        <!-- Sidebar End -->

        <!-- Main Content Start -->
        <div id="jobs_desc" class="col-md-9 main-panel "style="margin-top: 20px;">
            <?php include 'jobsearchbar.php'   ?>
            <div class="container card-container"style="margin-top: 20px;">
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
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <?php
    include 'footer.php';
}
    ?>
    </div>
    <!-- Footer End -->