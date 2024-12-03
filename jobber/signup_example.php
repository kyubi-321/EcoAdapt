<?php
session_start();
include('dbconnect.php');
include('header.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_SESSION['candidate_id'])) {
    header("Location: index.php");
    exit();
}

// Initialize variables
$candidate_name = $candidate_mobile = $candidate_email = $password = $skill = $location = $education = $industry = $budget_min = $budget_max  = $exp_max = $profile_picture = "";
$errors = [];
$successMsg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Validate and set form fields
    $candidate_name = $_POST["candidate_name"] ?? "";
    $candidate_mobile = $_POST["candidate_mobile"] ?? "";
    $candidate_email = $_POST["candidate_email"] ?? "";
    $password = $_POST["password"] ?? "";
    $skill = $_POST["skill"] ?? "";
    $location = $_POST["location"] ?? "";
    $education = $_POST["education"] ?? "";
    $industry = $_POST["industry"] ?? "";
    $budget_min = $_POST["budget_min"] ?? "";
    $budget_max = $_POST["budget_max"] ?? "";
    // $exp_min = $_POST["exp_min"] ?? "";
    $exp_max = $_POST["exp_max"] ?? "";

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "assets/uploads/";
        $unique_filename = uniqid('profile_', true) . '_' . basename($_FILES["profile_picture"]["name"]);
        $profile_picture = $target_dir . $unique_filename;

        if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture)) {
            $errors['profile_picture'] = "Failed to upload profile picture.";
        }
    }

    // Validating mandatory fields
    if (empty($candidate_name)) $errors['candidate_name'] = "Name is required";
    if (empty($candidate_mobile)) $errors['candidate_mobile'] = "Mobile number is required";
    if (empty($candidate_email) || !filter_var($candidate_email, FILTER_VALIDATE_EMAIL)) {
        $errors['candidate_email'] = "Valid email is required";
    }
    if (empty($password)) $errors['password'] = "Password is required";

    //proceed with OTP generation
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Securely hash the password

        $_SESSION['otp'] = rand(100000, 999999); // Generate a 6-digit OTP
        $_SESSION['candidate_data'] = [
            'candidate_id' => uniqid(),
            'candidate_name' => $candidate_name,
            'candidate_mobile' => $candidate_mobile,
            'candidate_email' => $candidate_email,
            'password' => $hashed_password, 
            'skill' => $skill,
            'location' => $location,
            'education' => $education,
            'industry' => $industry,
            'budget_min' => $budget_min,
            'budget_max' => $budget_max,
            'exp_max' => $exp_max,
            'profile_picture' => $profile_picture
        ];

        // Send OTP to the candidate's email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '{{USER_GMAIL}}';
            $mail->Password = '{{USER_GOOGLE_SECRET}}';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('{{USER_GMAIL}}', 'EcoAdopt');
            $mail->addAddress($candidate_email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for Verification';
            $mail->Body = "<p>Your OTP is: <strong>{$_SESSION['otp']}</strong></p>";

            $mail->send();
            $successMsg = "An OTP has been sent to your email. Please verify to complete registration.";
        } catch (Exception $e) {
            $errors['general'] = "Could not send OTP. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

// Handle OTP Verification and Save Data to DB
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $user_otp = $_POST['otp'] ?? '';
    if ($user_otp == $_SESSION['otp']) {
        $data = $_SESSION['candidate_data'];
        $stmt = $conn->prepare("INSERT INTO candidate (candidate_id, candidate_name, candidate_mobile, candidate_email, password, skill, location, education, industry, budget_min, budget_max, exp_max, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssss", $data['candidate_id'], $data['candidate_name'], $data['candidate_mobile'], $data['candidate_email'], $data['password'], $data['skill'], $data['location'], $data['education'], $data['industry'], $data['budget_min'], $data['budget_max'],  $data['exp_max'], $data['profile_picture']);

        if ($stmt->execute()) {
            $successMsg = "Sign-up successful! You can now <a href='login.php'>login</a>.";
            unset($_SESSION['otp']);
        } else {
            $errors['general'] = "An error occurred while saving data.";
        }
        $stmt->close();
    } else {
        $errors['otp'] = "Invalid OTP. Please try again.";
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
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>



    <?php if (!empty($successMsg)) echo "<p class='alert alert-success'>$successMsg</p>"; ?>
    <?php if (!empty($errors['general'])) echo "<p class='alert alert-danger'>{$errors['general']}</p>"; ?>
    <!-- OTP Verification Modal -->
    <?php if (isset($_SESSION['otp'])): ?>
        <div id="otpModal" style="display:block;">
            <form method="POST" action="">
                <h4>Enter OTP</h4>
                <input type=" text" name="otp" placeholder="Enter OTP" required>
                <?php if (isset($errors['otp'])) echo "<span class='text-danger'>{$errors['otp']}</span>"; ?>
                <button type="submit" name="verify_otp">Verify</button>
            </form>
        </div>
    <?php endif; ?>
    <div id="BigBag">

        <!-- LEFT***************** -->
        <div id="signup-leftBox">
            <img src="https://static.naukimg.com/s/7/104/assets/images/green-boy.c8b59289.svg">

            <div id="leftRegister">

                <h5>On registering, you can </h5>
                <ul id="leftlist">
                    <li>
                        <i class="fa-solid fa-circle-check"></i>
                        <p>Build your profile and let recruiters find you</p>
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
        <div id="signup-rightBox">




            <form action="signup.php" method="POST" enctype="multipart/form-data" id="LoginLeftForm">
                <h2 id="find">Find a job & grow your career</h2>

                <div class="mb-3">
                    <label for="candidate_name" class="form-label">Full Name<span class="text-danger">*</span></label>
                    <input type="text" name="candidate_name" class="form-control" placeholder="What is your name?" value="<?php echo htmlspecialchars($candidate_name); ?>" required>
                    <?php if (isset($errors['candidate_name'])) echo "<span class='text-danger'>{$errors['candidate_name']}</span>"; ?>
                </div>

                <div class="mb-3">
                    <label for="candidate_email" class="form-label">Email ID <span class="text-danger">*</span></label>
                    <input type="email" name="candidate_email" class="form-control" placeholder="Tell us your Email ID" value="<?php echo htmlspecialchars($candidate_email); ?>" required>
                    <div id="emailHelp" class="form-text">We'll send you relevant jobs in your mail</div>
                    <?php if (isset($errors['candidate_email'])) echo "<span class='text-danger'>{$errors['candidate_email']}</span>"; ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Create a password for your account" required>
                    <div id="emailHelp" class="form-text">Minimum 6 characters required</div>
                    <?php if (isset($errors['password'])) echo "<span class='text-danger'>{$errors['password']}</span>"; ?>
                </div>

                <div class="mb-3">
                    <label for="candidate_mobile" class="form-label">
                        Mobile Number <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="candidate_mobile"
                        class="form-control"
                        placeholder="Mobile Number"
                        value="<?php echo htmlspecialchars($candidate_mobile); ?>"
                        required>
                    <div id="emailHelp" class="form-text">Recruiters will call you on this number</div>
                    <?php if (isset($errors['candidate_mobile'])) echo "<span class='text-danger'>{$errors['candidate_mobile']}</span>"; ?>
                </div>



                <!-- Skills, location, education, industry, budget, and experience fields -->
                <div class="mb-3">
                    <label for="skill">Skills</label>
                    <input type="text" name="skill" class="form-control" placeholder="Write your skills" value="<?php echo htmlspecialchars($skill); ?>">
                </div>

                <div class="mb-3">
                    <label for="location">Location</label>
                    <input type="text" name="location" class="form-control" placeholder="What is your location?" value="<?php echo htmlspecialchars($location); ?>">
                </div>

                <div class="mb-3">
                    <label for="education">Education</label>
                    <input type="text" name="education" class="form-control" placeholder="Educational Qualification" value="<?php echo htmlspecialchars($education); ?>">
                </div>

                <div class="mb-3">
                    <label for="industry">Industry</label>
                    <input type="text" name="industry" class="form-control" placeholder="Industry" value="<?php echo htmlspecialchars($industry); ?>">
                </div>

                <div class="mb-3">
                    <label for="budget_min">Minimum Budget</label>
                    <input type="text" name="budget_min" class="form-control" placeholder="Min. Budget" value="<?php echo htmlspecialchars($budget_min); ?>">
                </div>

                <div class="mb-3">
                    <label for="budget_max">Maximum Budget</label>
                    <input type="text" name="budget_max" class="form-control" placeholder="Max. Budget" value="<?php echo htmlspecialchars($budget_max); ?>">
                </div>

                

                <div class="mb-3">
                    <label for="exp_max">Maximum Experience</label>
                    <input type="text" name="exp_max" class="form-control" placeholder="Max. Exp." value="<?php echo htmlspecialchars($exp_max); ?>">
                </div>

                <!-- Profile Picture Upload -->
                <div class="mb-3">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control">
                    <?php if (isset($errors['profile_picture'])) echo "<span class='text-danger'>{$errors['profile_picture']}</span>"; ?>
                </div>

                <p class="terms">By clicking Sign Up, you agree to the Terms and Conditions & Privacy Policy of EcoAdopt.com</p>
                <button type="submit" name="submit" class="btn btn-primary mt-3 w-100">Sign Up</button>
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


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <?php
        include 'footer.php';
        ?>
    </div>


</div>