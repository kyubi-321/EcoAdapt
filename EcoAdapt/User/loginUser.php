<?php
include('../dbconnect.php');
include('../header.php');

if (isset($_SESSION['user_id'])) {
    header("Location: findCandidate.php");
    exit();
}

$email = $password = "";
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["user_email"])) {
        $errors['user_email'] = "Email is required";
    } else {
        $email = $_POST["user_email"];
    }

    if (empty($_POST["user_password"])) {
        $errors['user_password'] = "Password is required";
    } else {
        $password = $_POST["user_password"];
    }

    // Check if credentials are correct
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id, user_name, user_password FROM user_details WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $user_name, $db_password);
            $stmt->fetch();
            
            // Verify plain-text password
            if ($password === $db_password) {
                // Store user details in session and redirect to a protected page
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;
                $_SESSION['user_email'] = $email;

                header("Location: findCandidate.php"); 
                exit;
            } else {
                $errors['general'] = "Invalid email or password.";
            }
        } else {
            $errors['general'] = "Invalid email or password.";
        }

        $stmt->close();
    }
}
?>
<link rel="stylesheet" href="../css/style.css">
<div>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <?php
        include '../topmenu.php';
        ?>
    </nav>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <?php if (!empty($errors['general'])) echo "<p class='alert alert-danger'>{$errors['general']}</p>"; ?>

    <h2 class="text-center mt-4 fs-4">Login to EcoAdopt</h2>
    <div id="BigBag">
        <!-- LEFT Section -->
        <div id="login-leftBox">
            <img src="https://static.naukimg.com/s/7/104/assets/images/green-boy.c8b59289.svg">
            <div id="leftRegister">
                <ul id="login-leftlist">
                    <li><i class="fa-solid fa-circle-check"></i><p>One click apply using EcoAdopt profile.</p></li>
                    <li><i class="fa-solid fa-circle-check"></i><p>Get Candidates details delivered right to your email</p></li>
                    <li><i class="fa-solid fa-circle-check"></i><p>Find candidates and grow your project</p></li>
                </ul>
            </div>
        </div>

        <!-- RIGHT Section -->
        <div id="login-rightBox">
            <form action="loginUser.php" method="POST" id="LoginLeftForm">
                <div class="mb-3">
                    <label for="user_email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="user_email" class="form-control" placeholder="Type your email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <?php if (isset($errors['user_email'])) echo "<span class='text-danger'>{$errors['user_email']}</span>"; ?>
                </div>

                <div class="mb-3">
                    <label for="user_password">Password <span class="text-danger">*</span></label>
                    <input type="password" name="user_password" class="form-control" placeholder="Type your password" required>
                    <?php if (isset($errors['user_password'])) echo "<span class='text-danger'>{$errors['user_password']}</span>"; ?>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Login</button>
            </form>
        </div>
    </div>
</div>

<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <?php
    include '../footer.php';
    ?>
</div>
