<?php
// Start session to track logged-in status
session_start();
include "dbconnect.php";

// Check if the user is logged in
if (!isset($_SESSION['candidate_id'])) {
    // If not logged in, redirect to login page
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';


// Check if the job ID is set in the URL and is valid
if (isset($_GET['jobid']) && is_numeric($_GET['jobid'])) {
    $jobId = (int) $_GET['jobid']; // Cast to integer to prevent SQL injection

    // Fetching the job details from the database
    $query = "
        SELECT 
            jobs.*, 
            company.company_name
        FROM 
            jobs
        INNER JOIN 
            company
        ON 
            jobs.company_id = company.company_id
        WHERE 
            jobs.job_status = 'open' AND jobs.job_id = ?;
    ";

    // Prepare the statement to prevent SQL injection
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $jobId); // Bind the jobId as an integer
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the job exists
        if ($result && $result->num_rows > 0) {
            $job = $result->fetch_assoc();
        } else {
            // If no job found, show a message
            echo "<p>Job not found or it is no longer available.</p>";
            exit;
        }
    } else {
        echo "<p>Failed to prepare the query: " . $mysqli->error . "</p>";
        exit;
    }
} else {
    echo "<p>Invalid job ID.</p>";
    exit;
}
?>
<style>
    h4 {
        font-weight: bold;
    }
</style>
<div class="container-xxl bg-white p-0">
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <?php include 'top_menu.php'; ?>
    </nav>
    <!-- Navbar End -->
    <div class="container-xxl py-5 bg-dark page-header mb-5">
        <div class="container my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Job Detail</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Job Detail</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Job Detail Section Start -->

    <!-- Job Detail Section End -->


    <!-- Job Detail Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gy-5 gx-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-5">
                        <!-- <img class="flex-shrink-0 img-fluid border rounded" src="img/com-logo-2.jpg" alt="" style="width: 80px; height: 80px;"> -->
                        <div class="text-start ps-4">
                            <h3 class="mb-3 font-weight-bold"> <?php echo htmlspecialchars($job['job_title']); ?></h3>
                            <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                            <!-- <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>Full Time</span> -->
                            <span class="text-truncate me-0"><i class="far fa-money-bill-alt text-primary me-2"></i> INR <?php echo htmlspecialchars($job['budget_min']) . ' - ' . htmlspecialchars($job['budget_max']); ?> Lakhs/year</span>
                        </div>
                    </div>

                    <div class="mb-5 gp-4">
                        <h4 class="mb-3 font-weight-bold">Job description</h4>
                        <p><?php echo htmlspecialchars($job['job_des']); ?>
                            We are seeking a Software Developer to contribute to the development and maintenance of high-quality software applications. The ideal candidate will have a strong foundation in software engineering principles and a passion for problem-solving. In this role, you will collaborate with cross-functional teams to design, develop, and implement software solutions that meet business and technical requirements.</p>


                        <h4 class="mb-3 font-weight-bold">Qualifications</h4>
                        <p> <?php echo htmlspecialchars($job['education']); ?></p>

                        <h4 class="mb-3 font-weight-bold">Responsibility</h4>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Design, develop, test, and deploy software applications in a fast-paced, agile environment.</li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Collaborate with product managers, designers, and other developers to understand user requirements and implement technical solutions.</li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Write clean, maintainable, and efficient code using best practices and standards.</li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Conduct code reviews and provide constructive feedback to other team members.</li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Troubleshoot and resolve software defects, ensuring smooth application functionality.</li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Maintain and improve existing applications by analyzing performance, identifying bottlenecks, and refactoring code when necessary.</li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Stay updated with the latest industry trends, tools, and technologies to improve software development processes.</li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Participate in team meetings, brainstorming sessions, and contribute ideas for new product features or improvements.
                            </li>
                        </ul>


                        <h4 class="mb-3 font-weight-bold">Experience Required</h4>
                        <p><?php echo htmlspecialchars($job['exp_min']) . '-' . htmlspecialchars($job['exp_max']) . ' years'; ?></p>

                        <h4 class="mb-3 font-weight-bold">Skills Required</h4>
                        <?php echo htmlspecialchars($job['skills']); ?>
                    </div>

                    <div class="">
                        <h4 class="mb-4 font-weight-bold">Apply For The Job</h4>

                        <form action="apply.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['job_id']); ?>" />
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="form-control" id="candidate_name" name="candidate_name" placeholder="Your Name" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="email" class="form-control" id="candidate_mail" name="candidate_mail" placeholder="Your Email" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="phone" class="form-control" id="contact_no" name="contact_no" placeholder="Your Contact Number" required>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <input type="file" class="form-control" id="resume" name="resume" required />
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" id="cover_letter" name="cover_letter" rows="4"></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit">Apply Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light rounded p-5 mb-4 wow slideInUp" data-wow-delay="0.1s">
                        <h4 class="mb-4">Job Summary</h4>
                        <p><i class="fa fa-angle-right text-primary me-2"></i>Published On: <?php
                                                                                            $date = new DateTime($job['posted_date_time']);
                                                                                            echo $date->format('m-d-Y');
                                                                                            ?></p>
                        <p><i class="fa fa-angle-right text-primary me-2"></i>Salary: INR <?php echo htmlspecialchars($job['budget_min']) . ' - ' . htmlspecialchars($job['budget_max']); ?> Lakhs/year</p>
                        <p><i class="fa fa-angle-right text-primary me-2"></i>Location: <?php echo htmlspecialchars($job['location']); ?></p>
                    </div>
                    <div class="bg-light rounded p-5 wow slideInUp" data-wow-delay="0.1s">
                        <h4 class="mb-4">Company Detail</h4>
                        <p class="m-0"><?php echo htmlspecialchars($job['company_name']); ?> Consulting was founded in 2024 with a vision to provide extensive and stable hiring solutions for middle and top management positions across industries and verticals PAN India & Globally. At EcoAdopt, we are dedicated to connecting exceptional talent with great opportunities. Whether you are a candidate seeking a new career path or an organization looking to find the perfect addition to your team, we are here to assist you every step of the way.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Job Detail End -->

</div>

<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <?php
    include 'footer.php';
    ?>
</div>
<!-- Footer End -->