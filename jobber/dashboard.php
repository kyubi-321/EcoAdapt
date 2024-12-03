<?php
session_start();
include('dbconnect.php');
include('header.php');

// Assuming the candidate is logged in and their candidate_id is stored in the session
if (!isset($_SESSION['candidate_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$candidate_id = $_SESSION['candidate_id'];

// Fetch candidate data from the database
$stmt = $conn->prepare("SELECT * FROM candidate WHERE candidate_id = ?");
$stmt->bind_param("s", $candidate_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if candidate exists
if ($result->num_rows > 0) {
    $candidate = $result->fetch_assoc();
} else {
    // If candidate not found, log them out and redirect to login page
    unset($_SESSION['candidate_id']);
    header("Location: login.php");
    exit();
}

$stmt->close();
?>

<!-- Custom CSS for Dashboard Styling -->
<style>
    .dashboard-container {
        max-width: 1000px;
        margin: 40px auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dashboard-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .dashboard-header h2 {
        font-weight: bold;
        color: #343a40;
    }

    .dashboard-table td,
    .dashboard-table th {
        padding: 12px;
        text-align: left;
    }

    .dashboard-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .dashboard-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .btn-logout {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
    }

    .btn-logout:hover {
        background-color: #c82333;
    }
</style>

 <!-- Navbar Start -->
 <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <?php
        include 'toPmenu_login.php';
        ?>
    </nav>
    <!-- Navbar End -->

<div class="dashboard-container">
    <div class="dashboard-header">
        <h2>Welcome, <?php echo htmlspecialchars($candidate['candidate_name']); ?>!</h2>
        <img src="<?php echo htmlspecialchars($candidate['profile_picture']); ?>" alt="Profile Picture" style="width: 150px; height: 150px; border-radius: 50%;">

        <p>Here are your registration details:</p>
    </div>

    <table class="table dashboard-table">
        <tr>
            <th>Full Name</th>
            <td><?php echo htmlspecialchars($candidate['candidate_name']); ?></td>
        </tr>
        <tr>
            <th>Mobile Number</th>
            <td><?php echo htmlspecialchars($candidate['candidate_mobile']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($candidate['candidate_email']); ?></td>
        </tr>
        <tr>
            <th>Skills</th>
            <td><?php echo htmlspecialchars($candidate['skill']); ?></td>
        </tr>
        <tr>
            <th>Location</th>
            <td><?php echo htmlspecialchars($candidate['location']); ?></td>
        </tr>
        <tr>
            <th>Education</th>
            <td><?php echo htmlspecialchars($candidate['education']); ?></td>
        </tr>
        <tr>
            <th>Industry</th>
            <td><?php echo htmlspecialchars($candidate['industry']); ?></td>
        </tr>
        <tr>
            <th>Minimum Budget</th>
            <td><?php echo htmlspecialchars($candidate['budget_min']); ?></td>
        </tr>
        <tr>
            <th>Maximum Budget</th>
            <td><?php echo htmlspecialchars($candidate['budget_max']); ?></td>
        </tr>
        <tr>
            <th>Minimum Experience</th>
            <td><?php echo htmlspecialchars($candidate['exp_min']); ?></td>
        </tr>
        <tr>
            <th>Maximum Experience</th>
            <td><?php echo htmlspecialchars($candidate['exp_max']); ?></td>
        </tr>
    </table>

    <!-- Logout Button -->
    <div class="text-center">
        <form action="logout.php" method="POST">
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</div>