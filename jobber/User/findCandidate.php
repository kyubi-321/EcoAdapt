<?php
// Start session to track logged-in status
session_start();

// Include header
include '../header.php';

// Initialize filter variables
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$locationFilters = isset($_GET['locationDetail']) ? $_GET['locationDetail'] : '';
$skillsFilters = isset($_GET['skills']) ? (array) $_GET['skills'] : [];
$experienceFilters = isset($_GET['experienceDetail']) ? (array) $_GET['experienceDetail'] : [];
$budget_max = isset($_GET['budget_max']) ? $_GET['budget_max'] : '';


// Base query to fetch candidates
$query = "SELECT * FROM candidate WHERE 1=1";

// Adding keyword filter using prepared statements
if (!empty($keyword)) {
    $escapedKeyword = "%" . $mysqli->real_escape_string($keyword) . "%";
    $query .= " AND (candidate_name LIKE ? OR skill LIKE ? OR candidate_email LIKE ?)";
}

// Adding budget max filter using prepared statements
if (!empty($budget_max)) {
    $escapedBudget = $mysqli->real_escape_string($budget_max);
    $query .= " AND budget_max <= ?";
}

// Location filter (only if provided)
if (!empty($locationFilters)) {
    $escapedLocation = $mysqli->real_escape_string($locationFilters);
    $query .= " AND location = ?";
}

// Skills filter
if (!empty($skillsFilters)) {
    foreach ($skillsFilters as $skill) {
        $escapedSkill = "%" . $mysqli->real_escape_string($skill) . "%";
        $query .= " AND skill LIKE ?";
    }
}

// Experience filter
if (!empty($experienceFilters)) {
    foreach ($experienceFilters as $exp) {
        $query .= " AND exp_max >= ?";
    }
}

// Pagination logic
$limit = 20; // Number of candidates per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure page is at least 1
$offset = ($page - 1) * $limit;
$query .= " ORDER BY candidate_created_date DESC LIMIT ?, ?";

// Prepare the query
$stmt = $mysqli->prepare($query);

// Bind parameters dynamically based on the filters
$bindParams = [];
$types = '';  // Placeholder for the types of the variables

// Adding keyword filter parameters
if (!empty($keyword)) {
    $bindParams[] = $escapedKeyword;
    $bindParams[] = $escapedKeyword;
    $bindParams[] = $escapedKeyword;
    $types .= 'sss';
}

// Adding keyword filter parameters
if (!empty($budget_max)) {
    $bindParams[] = (int)$escapedBudget;
    $types .= 'i';
}

// Adding location filter parameter
if (!empty($locationFilters)) {
    $bindParams[] = $escapedLocation;
    $types .= 's';
}

// Adding skills filter parameters
foreach ($skillsFilters as $skill) {
    $bindParams[] = "%" . $mysqli->real_escape_string($skill) . "%";
    $types .= 's';
}

// Adding experience filter parameters
foreach ($experienceFilters as $exp) {
    $bindParams[] = (int)$exp;
    $types .= 'i';
}

// Bind pagination parameters
$bindParams[] = $offset;
$bindParams[] = $limit;
$types .= 'ii';

// Bind the parameters to the statement
$stmt->bind_param($types, ...$bindParams);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Function to generate candidate cards
function generateCandidateCards($result)
{
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="candidate-item card p-4 mb-4 shadow-sm border-0">';
            echo '<div class="row g-4">';

            // Profile image at the start (assuming image URL is in 'profile_image' field or placeholder)
            echo '<div class="col-md-3 col-sm-12">';
            echo '<img src="../' . htmlspecialchars($row['profile_picture'] ?? 'path_to_default_image.jpg') . '" alt="Profile Image" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">';
            echo '</div>';

            // Candidate details
            echo '<div class="col-md-9 col-sm-12">';

            // Candidate name as a heading
            echo '<h4 class="mb-3 font-weight-bold">' . htmlspecialchars($row['candidate_name']) . '</h4>';

            // Create a div for all candidate details
            echo '<div class="candidate-details">';

            // Email section
            echo '<div class="row mb-2">';
            echo '<div class="col-4"><strong>Email:</strong></div>';
            echo '<div class="col-8">' . htmlspecialchars($row['candidate_email']) . '</div>';
            echo '</div>';

            // Mobile section
            echo '<div class="row mb-2">';
            echo '<div class="col-4"><strong>Mobile:</strong></div>';
            echo '<div class="col-8">' . htmlspecialchars($row['candidate_mobile']) . '</div>';
            echo '</div>';

            // Skills section
            echo '<div class="row mb-2">';
            echo '<div class="col-4"><strong>Skills:</strong></div>';
            echo '<div class="col-8">' . htmlspecialchars($row['skill']) . '</div>';
            echo '</div>';

            // Location section
            echo '<div class="row mb-2">';
            echo '<div class="col-4"><strong>Location:</strong></div>';
            echo '<div class="col-8">' . htmlspecialchars($row['location']) . '</div>';
            echo '<div class="col-4"><strong>Maximum Budget:</strong></div>';
            echo '<div class="col-8">' . htmlspecialchars($row['budget_max']) . '</div>';

            echo '</div>';

            echo '</div>';  // End candidate details div

            echo '</div>';  // End col-md-9
            echo '</div>';  // End row
            echo '</div>';  // End card
        }
    } else {
        echo '<p>No candidates found.</p>';
    }
}

// Get total number of candidates for pagination
$totalQuery = "SELECT COUNT(*) AS total_candidates FROM candidate WHERE 1=1";

// Execute the total query
$totalResult = $mysqli->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalCandidates = $totalRow['total_candidates'];
$totalPages = ceil($totalCandidates / $limit);

// Check for AJAX request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    generateCandidateCards($result);
} else {
    include '../header.php';
}
?>


<link rel="stylesheet" href="../css/style.css">

<div class="container-xxl bg-white p-0">
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <?php
        if (isset($_SESSION['user_id'])) {
            // Candidate is logged in
            include 'top_menu.php';
        } else {
            // Candidate is not logged in
            include 'topmenu.php';
        }
        ?>
    </nav>
    <!-- Navbar End -->

    <div class="content-wrapper d-flex">


        <!-- Main Content Start -->
        <div id="candidates_desc" class="col-md-12 main-panel" style="margin-top: 20px;">
            <?php include 'filterCandidate.php'; ?> <!-- Include filter sidebar -->

            <div class="container card-container" style="margin-top: 20px;">
                <div class="row" id="candidate-listings-row">
                    <?php generateCandidateCards($result); ?>
                </div>
            </div>

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
</div>