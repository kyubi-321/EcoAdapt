<?php
// Start session to check user login
// session_start();

// Placeholder for profile data
$profileImage = '../img/default.jpeg'; // Default profile image
$userName = 'User';

// Check if the user is logged in
// if (isset($_SESSION['candidate_id'])) {
//     // Database connection
//     $conn = new mysqli('localhost', 'root', '', 'boosttech');

//     // Check connection
//     if ($conn->connect_error) {
//         die("Connection failed: " . $conn->connect_error);
//     }

// Get user details from the database
$userId = $_SESSION['user_id'];
$query = "SELECT user_name FROM user_details WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($userName);
$stmt->fetch();
$stmt->close();


?>
<?php
// Get the current page name from the URL
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Navbar HTML with dynamic profile image and name -->
<a href="../index.php" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
    <img src="../img/logoeco.jpg" alt="logo" />
    <h1 class="m-0 text-primary">EcoAdopt</h1>
</a>

<button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarCollapse">
    <div class="navbar-nav ms-auto p-4 p-lg-0">
        <div class="nav-item dropdown">
            <a href="findCandidate.php" class="nav-link">Candidates</a>

        </div>
    </div>

    <div class="d-flex align-items-center">
        <form action="findCandidate.php" method="GET" class="d-flex align-items-center">
            <label for="keyword" class="visually-hidden">Keyword</label>
            <input
                type="text"
                id="keyword"
                name="keyword"
                class="form-control me-2"
                placeholder="Job Title, Skills, or Company"
                aria-label="Search"
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>"
                style="flex-grow: 1; min-width: 200px;" />
            <button
                class="btn btn-outline-primary btn-sm px-3 mb-2"
                type="submit"
                style="height: 36px;">
                Search
            </button>
        </form>


        <div class="nav-item dropdown ms-3" id="tmlogin45">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <?php echo htmlspecialchars($userName); ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end rounded-0 m-0">
                <a href="logout.php" class="dropdown-item">Logout</a>
            </div>
        </div>
    </div>
</div>