<!-- job to be displayed on index page-->
<?php

    // SQL query to fetch active and published jobs
       $stmt = $conn->prepare("SELECT * FROM jobs WHERE job_status = 'open' AND published = 'Yes'");
       $stmt->execute();
       $result = $stmt->get_result(); // Fetch the result set
       $jobs = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
   
?>
<div class="container-xxl bg-white p-0">
    <!-- Jobs Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Job Listing</h1>
            <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <?php foreach ($jobs as $job): ?>
                            <div class="job-item p-4 mb-4">
                                <div class="row g-4">
                                    <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid border rounded" src="img/com-logo-<?= htmlspecialchars($job['job_auto_id']) ?>.jpg" alt="Company Logo" style="width: 80px; height: 80px;">
                                        <div class="text-start ps-4">
                                            <h5 class="mb-3"><?= htmlspecialchars($job['job_title']) ?></h5>
                                            <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i><?= htmlspecialchars($job['location']) ?></span>
                                            <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>Full Time</span>
                                            <span class="text-truncate me-0"><i class="far fa-money-bill-alt text-primary me-2"></i><?= htmlspecialchars($job['currency']) ?><?= htmlspecialchars($job['budget_min']) ?> - <?= htmlspecialchars($job['budget_max']) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                        <div class="d-flex mb-3">
                                            <a class="btn btn-light btn-square me-3" href=""><i class="far fa-heart text-primary"></i></a>
                                            <a class="btn btn-primary" href="apply.php?job_id=<?= htmlspecialchars($job['job_id']) ?>">Apply Now</a>
                                        </div>
                                        <small class="text-truncate"><i class="far fa-calendar-alt text-primary me-2"></i>Date Line: <?= date("d M, Y", strtotime($job['posted_date_time'] . " +30 days")) ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <a class="btn btn-primary py-3 px-5" href="jobs.php">Browse More Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Jobs End -->