<?php

include 'header.php';
include 'dbconnect.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$submissionSuccess = false; // Flag to check if the query submission was successful

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!$name || !$email || !$subject || !$message) {
        echo json_encode(['success' => false, 'error' => 'Invalid input. Please fill all fields correctly.']);
        exit;
    }


    $stmt = $conn->prepare("INSERT INTO contact_queries (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Email configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ankitbadhani102@gmail.com';
            $mail->Password = 'khly nmjr chza fapc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email to Organization
            $mail->setFrom('ankitbadhani102@gmail.com', 'EcoAdopt');
            $mail->addAddress('ankitbadhani102@gmail.com'); // Organization's email address
            $mail->Subject = 'New Contact Query';
            $mail->Body = "You have received a new query:\n\nName: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message";
            $mail->send(); // Send the email to the organization

            // Email to User
            $mail->clearAddresses(); // Clear previous recipients
            $mail->addAddress($email); // Send confirmation to the user's email
            $mail->Subject = 'We Received Your Query';
            $mail->Body = "Dear $name,\n\nThank you for reaching out to us. We have received your query:\n\nSubject: $subject\nMessage:\n$message\n\nOur team will contact you shortly.\n\nBest regards,\nEcoAdopt";

            $mail->send(); // Send confirmation email to user
            echo '<script>alert("Mail successfully sent.");</script>';

            $submissionSuccess = true; // Set the success flag to true after successful submission
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Email sending failed: ' . $mail->ErrorInfo]); // Catch error and send message
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save the query to the database.']); // Error saving to database
    }


    $stmt->close();
    $conn->close();


    // Redirect after email is sent and alert is shown
    if ($submissionSuccess) {
        // Delay redirect to allow alert to show
        echo '<script>setTimeout(function() { window.location = "contact_us.php?success=true"; }, 100);</script>';
        exit;
    }

    exit;
}
?>


<div class="container-xxl bg-white p-0">
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

    <div class="container-xxl py-5 bg-dark page-header mb-5">
        <div class="container my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Contact Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Contact Us</li>
                </ol>
            </nav>
        </div>
    </div>


    <div id="spinner" class="bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center" style="display: none;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>




    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5">Contact For Any Query</h1>
            <div class="row g-4">
                <div class="col-md-6">
                    <iframe class="position-relative rounded w-100 h-100" src="https://www.google.com/maps/embed?..."></iframe>
                </div>
                <div class="col-md-6">
                    <form id="contactForm" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" style="height: 150px" required></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <?php
        include 'footer.php';
        ?>
    </div>
    <!-- Footer End -->
</div>