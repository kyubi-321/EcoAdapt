<?php
include 'header.php';
?>
<div>
    <!-- Main Wrapper -->
    <?php
    // include 'sidebar.php'; // Uncomment if needed
    ?>
    <div>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
            <?php include 'topmenu.php'; ?>
        </nav>
        <!-- Navbar End -->

        <div class="container-xxl bg-white p-0">
            <!-- Spinner Start -->
            <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <!-- Spinner End -->

            <!-- Page Header -->
            <div class="container-xxl py-5 bg-dark page-header mb-5">
                <div class="container my-5 pt-5 pb-4 text-center">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Our Services</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb text-uppercase justify-content-center">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Services</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- Page Header End -->

            <!-- Services Section -->
            <div class="container-fluid py-5 px-5" id="services">
                <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                    <h1 class="display-5 mb-0">What We Offer</h1>
                    <p class="lead text-secondary mt-3">EcoAdapt NGO provides tailored solutions for sustainable development, training, and recruitment, empowering individuals and organizations to achieve their goals effectively.</p>
                    <hr class="w-25 mx-auto bg-primary">
                </div>
                <div class="row g-5">
                    <?php 
                    $services = [
                        ["icon" => "fa-seedling", "title" => "Sustainability Programs", "text" => "EcoAdapt promotes sustainable practices by collaborating with organizations to implement green solutions and foster environmental consciousness. Join us in our mission to make the world a better place."],
                        ["icon" => "fa-users", "title" => "Community Engagement", "text" => "We bring communities together to drive collective action toward addressing environmental challenges, promoting education, and fostering a spirit of collaboration."],
                        ["icon" => "fa-hands-helping", "title" => "Volunteer Opportunities", "text" => "EcoAdapt provides a platform for individuals passionate about the environment to contribute to meaningful projects and initiatives aimed at creating lasting change."],
                        ["icon" => "fa-cogs", "title" => "Skill Development", "text" => "Our training programs equip individuals with the skills needed to excel in sustainable industries, fostering career growth and contributing to economic development."],
                        ["icon" => "fa-briefcase", "title" => "Recruitment Services", "text" => "We connect organizations with talented individuals passionate about sustainability, ensuring the right people are in place to drive impactful change."],
                        ["icon" => "fa-leaf", "title" => "Eco-Friendly Solutions", "text" => "Our team specializes in designing and implementing eco-friendly projects tailored to meet organizational goals while reducing environmental impact."],
                    ];

                    foreach ($services as $service) {
                        echo '
                        <div class="col-lg-4 col-md-6">
                            <div class="service-item bg-light text-center px-4 py-5 shadow">
                                <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle mx-auto mb-4" style="width: 90px; height: 90px;">
                                    <i class="fa ' . $service['icon'] . ' fa-2x"></i>
                                </div>
                                <h4 class="mb-3">' . $service['title'] . '</h4>
                                <p class="short-text">' . implode(' ', array_slice(explode(' ', $service['text']), 0, 25)) . '...</p>
                                <p class="full-text d-none">' . $service['text'] . '</p>
                                <button class="btn btn-link text-primary read-more">Read More</button>
                            </div>
                        </div>
                        ';
                    }
                    ?>
                </div>
            </div>
            <!-- Services Section End -->

            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
        </div>

        <!-- Footer -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
            <?php include 'footer.php'; ?>
        </div>
    </div>
</div>

<script src="js/services.js"></script>
