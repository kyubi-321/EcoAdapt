<?php
include('dbconnect.php');
include('header.php');

if (isset($_SESSION['candidate_id'])) {
    header("Location: index.php");
    exit();
}

$email = $password = "";
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $errors['email'] = "Email is required";
    } else {
        $email = $_POST["email"];
    }

    if (empty($_POST["password"])) {
        $errors['password'] = "Password is required";
    } else {
        $password = $_POST["password"];
    }

    // Check if credentials are correct
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT candidate_id, candidate_name, password FROM candidate WHERE candidate_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($candidate_id, $candidate_name, $db_password);
            $stmt->fetch();
            
            // Verify hashed password
            if (password_verify($password, $db_password)) {
                // Store user details in session and redirect to a protected page
                $_SESSION['candidate_id'] = $candidate_id;
                $_SESSION['candidate_name'] = $candidate_name;
                header("Location: jobs.php"); 
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



<div>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <?php
        include 'topmenu.php';
        ?>
    </nav>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
    <?php if (!empty($errors['general'])) echo "<p class='alert alert-danger'>{$errors['general']}</p>"; ?>

    <h2 class="text-center mt-4 fs-4">Login to EcoAdopt</h2>
    <div id="BigBag">

        <!-- LEFT***************** -->
        <div id="login-leftBox">
        <img src="https://static.naukimg.com/s/7/104/assets/images/green-boy.c8b59289.svg">

            <div id="leftRegister">

                <ul id="login-leftlist">
                    <li>
                        <i class="fa-solid fa-circle-check"></i>
                        <p>One click apply using EcoAdopt profile.</p>
                    </li>

                    <li>
                        <i class="fa-solid fa-circle-check"></i>
                        <p>Get job postings delivered right to your email</p>
                    </li>

                    <li>
                        <i class="fa-solid fa-circle-check"></i>
                        <p>Find a job and grow your career</p>
                    </li>
                </ul>


            </div>
        </div>


        <!-- RIGHT***************** -->
        <div id="login-rightBox">




            <form action="login.php" method="POST" id="LoginLeftForm">
                <div class="mb-3">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Type your email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <?php if (isset($errors['email'])) echo "<span class='text-danger'>{$errors['email']}</span>"; ?>
                </div>

                <div class="mb-3">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Type your password" required>
                    <?php if (isset($errors['password'])) echo "<span class='text-danger'>{$errors['password']}</span>"; ?>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Login</button>
                <p class="mt-3">Don't have an account? <a href="signup.php">Sign Up</a></p>
            </form>

            <!-- <div id="googleRight">
        <div id="minime">
          <span class="dot">OR</span>
          <div class="googleSignUpWrapper">
            <div class="google-sigup-block">
              <span class="signupwith main-2">Continue with</span>
              
                <span class="icon-holder">
                  <img src="//static.naukimg.com/s/7/104/assets/images/google-icon.9273ac87.svg" class="socialIcnImg">
                </span>
                <span class="google-text">Google</span>
              </button>
            </div>
          </div>
          </div> -->
        </div>
    </div>


</div>

<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <?php
    include 'footer.php';
    ?>
</div>
</div>