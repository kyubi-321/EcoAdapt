<?php
session_start();
if (!isset($_SESSION['candidate_id'])) {
    header("Location: login.php");
    exit;
}
include 'header.php';
?>

<div class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <!-- Hamburger Icon used here of font awesome -->
    <button id="toggle-sidebar" class="hamburger-icon">
        <i class="fas fa-bars"></i>
    </button>
    <?php include 'top_menu.php'; ?>
</div>

<div class="sidebar">
    <?php include 'sidebar.php'; ?>
</div>

<div id="content">
    <!-- Default content loaded here -->
    <?php include './profile/profile_des.php'; 
    //Ankit?>
    <?php include './profile/profile_details.php'; 
    //ANkit?>
    <?php include './profile/profile_summary.php'; 
    //Ankit?>
    <?php include './profile/profile_education.php';
    //Ankit
    ?>
    <?php include './profile/profile_experience.php';
    //Ankit
    ?>
    <?php include './profile/profile_skill.php';
    //ritika 
    ?>

    <?php include './profile/profile_projects.php'; 
    //Ankit?>

    <?php include './profile/profile_socialconnects.php'; 
    //Ankit?>


</div>

<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <?php
    include 'footer.php';
    ?>
</div>
<!-- Footer End -->

<script>
    $(document).ready(function() {
        // Handle sidebar link clicks
        $('.load-content').click(function(e) {
            e.preventDefault();
            const url = $(this).attr('href'); 
            // Show loading indicator while content is being fetched
            const contentDiv = document.querySelector('#content');
            contentDiv.innerHTML = '<div id="loading"></div>';

            $('#content').load(url);

        });

        // Toggling here sidebar visibility on hamburger icon click
        $('#toggle-sidebar').click(function() {
            $('#sidebar').toggleClass('hidden');
            $('#content').toggleClass('sidebar-open');
        });
    });
</script>
