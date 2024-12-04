<?php
include 'header.php';
?>
<div class="container-xxl bg-white p-0">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <?php
        include 'topmenu.php';
        ?>
    </nav>
    <!-- Navbar End -->


    <!-- Header End -->
    <div class="container-xxl py-5 bg-dark page-header mb-5">
        <div class="container my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">About Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">About</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Header End -->


    <!-- About Start -->
    <div class="container-fluid bg-white p-0">
    <div class="container-fluid py-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="row g-0 about-bg rounded overflow-hidden">
                    <div class="col-6 text-start">
                        <img class="img-fluid w-100" src="img/about-1.jpg" alt="About Image 1">
                    </div>
                    <div class="col-6 text-start">
                        <img class="img-fluid" src="img/about-2.jpg" style="width: 85%; margin-top: 15%;" alt="About Image 2">
                    </div>
                    <div class="col-6 text-end">
                        <img class="img-fluid" src="img/about-3.jpg" style="width: 85%;" alt="About Image 3">
                    </div>
                    <div class="col-6 text-end">
                        <img class="img-fluid w-100" src="img/about-4.jpg" alt="About Image 4">
                    </div>
                </div>
            </div>

            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <h1 class="mb-4">Founded in 2024, EcoAdapt NGO</h1>
                <p class="mb-4">EcoAdapt NGO was established to inspire environmental awareness, sustainable living, and active community engagement for a greener and healthier planet. Our initiatives focus on conservation, education, and empowering communities to adapt and thrive in a changing world.</p>
                <p><i class="fa fa-check text-primary me-3"></i>Promoting environmental conservation</p>
                <p><i class="fa fa-check text-primary me-3"></i>Fostering sustainable practices</p>
                <p><i class="fa fa-check text-primary me-3"></i>Empowering communities for a better future</p>
                
                <!-- Read More Button -->
                <a class="btn btn-primary py-3 px-5 mt-3" href="javascript:void(0);" onclick="toggleReadMore()">Read More</a>

                <!-- Read More Content (Initially Hidden) -->
                <div id="moreInfo" style="display: none; margin-top: 20px;">
                    <h5>Our Vision</h5>
                    <p>We envision a world where humanity thrives in harmony with nature. EcoAdapt strives to be a catalyst for environmental transformation by mobilizing communities, fostering education, and driving impactful change that protects ecosystems and improves quality of life.</p>

                    <h5>What We Do</h5>
                    <p>EcoAdapt's projects span diverse areas, including reforestation, clean water initiatives, waste management, and renewable energy adoption. By collaborating with local communities and global organizations, we aim to create scalable solutions to pressing environmental challenges.</p>

                    <h5>Commitment to Sustainability</h5>
                    <p>Sustainability is at the core of everything we do. From promoting green technologies to organizing awareness campaigns, our work is rooted in a commitment to ensuring that today's actions benefit future generations. Through partnerships and grassroots engagement, we aim to leave a lasting positive impact.</p>

                    <p>Join us in building a sustainable and resilient world where nature and humanity coexist in harmony. Together, we can create a brighter and greener future for all.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleReadMore() {
        var moreInfo = document.getElementById("moreInfo");
        moreInfo.style.display = "block";  // Show the "Read More" content
        // Hide the "Read More" button
        event.target.style.display = "none";
    }
</script>


    <!-- About End -->




    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <?php
        include 'footer.php';
        ?>
    </div>
    <!-- Footer End -->
</div>
