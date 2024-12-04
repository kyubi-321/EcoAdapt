<?php
// Start session to track logged-in status
session_start();
include "dbconnect.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the user is logged in
if (!isset($_SESSION['candidate_id'])) {
    header('Location: login.php');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['job_id'], $_POST['cover_letter'])) {
        $jobId = $_POST['job_id'];
        $candidate_name = $_POST['candidate_name'];
        $candidate_mail = $_POST['candidate_mail'];
        $contact_no = $_POST['contact_no'];
        $coverLetter = $_POST['cover_letter'];

        // Handle file upload (resume)
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            $resumeName = $_FILES['resume']['name'];
            $resumeTmp = $_FILES['resume']['tmp_name'];
            $resumePath = 'assets/uploads/' . uniqid('resume_', true) . '_' . basename($_FILES["resume"]["name"]);

            // Move the uploaded file to the "uploads" directory
            if (move_uploaded_file($resumeTmp, $resumePath)) {
                // Insert application into database
                $query = "INSERT INTO Job_applications (candidate_id, job_id, candidate_name, candidate_mail, cover_letter, resume_path, contact_no) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("iisssss", $_SESSION['candidate_id'], $jobId, $candidate_name, $candidate_mail, $coverLetter, $resumePath, $contact_no);
                $stmt->execute();

                // Check if the application was successful
                if ($stmt->affected_rows > 0) {
                    // Fetch job title for email
                    $jobQuery = $mysqli->prepare("SELECT job_title FROM jobs WHERE job_id = ?");
                    $jobQuery->bind_param("i", $jobId);
                    $jobQuery->execute();
                    $jobResult = $jobQuery->get_result();
                    $jobData = $jobResult->fetch_assoc();
                    $jobTitle = $jobData['job_title'];

                    // Send email notifications
                    $mail = new PHPMailer(true);

                    try {
                        // SMTP configuration
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                        $mail->SMTPAuth = true;
                        $mail->Username = '{{USER_GMAIL}}'; // Replace with your email
                        $mail->Password = '{{USER_GOOGLE_SECRET}}'; // Replace with your email password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Applicant email
                        $mail->setFrom('{{USER_GMAIL}}', 'EcoAdopt Job Portal');
                        $mail->addAddress($candidate_mail);
                        $mail->isHTML(true);
                        $mail->Subject = "Application Received for $jobTitle";
                        $mail->Body = "
                            <p>Dear $candidate_name,</p>
                            <p>Thank you for applying for the <b>$jobTitle</b> position.</p>
                            <p>We have successfully received your application.</p>
                            <p>Best regards,</p>
                            <p>EcoAdopt Job Portal Team</p>
                        ";
                        $mail->send();

                        // Admin email
                        $mail->clearAddresses();
                        $mail->addAddress('{{USER_GMAIL}}'); // Replace with admin email
                        $mail->Subject = "New Application for $jobTitle";
                        $mail->Body = "
                            <p>A new application has been received:</p>
                            <p><b>Candidate Name:</b> $candidate_name</p>
                            <p><b>Email:</b> $candidate_mail</p>
                            <p><b>Contact Number:</b> $contact_no</p>
                            <p><b>Cover Letter:</b> $coverLetter</p>
                            <p><b>Resume:</b> <a href='http://yourwebsite.com/$resumePath'>Download</a></p>
                            <p>Regards,</p>
                            <p>Bost Tech Job Portal System</p>
                        ";
                        $mail->send();

                        echo "Application submitted successfully! An email has been sent to you.";
                        header("Refresh:3; url=jobs.php");
                    } catch (Exception $e) {
                        echo "Application submitted successfully, but email notifications failed: {$mail->ErrorInfo}";
                    }
                } else {
                    echo "Error submitting application.";
                }
            } else {
                echo "Error uploading the resume.";
            }
        } else {
            echo "Please upload your resume.";
        }
    } else {
        echo "Invalid form data.";
    }
} else {
    echo "Invalid request.";
}
