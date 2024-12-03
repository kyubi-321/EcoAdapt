(function ($) {
  "use strict";

  // Spinner
  var spinner = function () {
    setTimeout(function () {
      if ($("#spinner").length > 0) {
        $("#spinner").removeClass("show");
      }
    }, 1);
  };
  spinner();

  // Initiate the wowjs
  new WOW().init();

  // Sticky Navbar
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $(".sticky-top").css("top", "0px");
    } else {
      $(".sticky-top").css("top", "-100px");
    }
  });

  // Back to top button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $(".back-to-top").fadeIn("slow");
    } else {
      $(".back-to-top").fadeOut("slow");
    }
  });
  $(".back-to-top").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 1500, "easeInOutExpo");
    return false;
  });

  // Header carousel
  $(".header-carousel").owlCarousel({
    autoplay: true,
    smartSpeed: 1500,
    items: 1,
    dots: true,
    loop: true,
    nav: true,
    navText: [
      '<i class="bi bi-chevron-left"></i>',
      '<i class="bi bi-chevron-right"></i>',
    ],
  });

  // Testimonials carousel
  $(".testimonial-carousel").owlCarousel({
    autoplay: true,
    smartSpeed: 1000,
    center: true,
    margin: 24,
    dots: true,
    loop: true,
    nav: false,
    responsive: {
      0: {
        items: 1,
      },
      768: {
        items: 2,
      },
      992: {
        items: 3,
      },
    },
  });

  // Fetch saved jobs on page load
  $.ajax({
    url: "save_job.php",
    method: "POST",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const savedJobIds = response.savedJobIds.map((id) => parseInt(id)); // Ensure job IDs are integers
        $(".save-job-btn").each(function () {
          const button = $(this);
          const jobId = parseInt(button.data("job-id")); // Ensure the button job ID is also an integer
          if (savedJobIds.includes(jobId)) {
            button
              .find("i")
              .removeClass("far text-primary")
              .addClass("fas text-danger");
          } else {
            button
              .find("i")
              .removeClass("fas text-danger")
              .addClass("far text-primary");
          }
        });
      } else if (response.logged_in === false) {
        // Do nothing for logged_in = false
        console.warn("User not logged in");
      } else {
        console.error(response.message || "Failed to fetch saved jobs.");
      }
    },
    error: function () {
      console.error("Failed to load saved jobs.");
    },
  });

  // Save or unsave a job
  $(document).on("click", ".save-job-btn", function () {
    const button = $(this);
    const jobId = button.data("job-id");
    const isBookmarked = button.find("i").hasClass("fas"); // Check if already bookmarked

    $.ajax({
      url: "save_job.php",
      method: "POST",
      data: {
        job_id: jobId,
        is_bookmarked: isBookmarked ? 0 : 1, // Toggle bookmark state
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          if (isBookmarked) {
            // Change to unbookmarked state
            button
              .find("i")
              .removeClass("fas text-danger")
              .addClass("far text-primary");
          } else {
            // Change to bookmarked state
            button
              .find("i")
              .removeClass("far text-primary")
              .addClass("fas text-danger");
          }
        } else {
          alert(response.message || "An error occurred.");
        }
      },
      error: function () {
        alert("Failed to save the job. Please try again later.");
      },
    });
  });

  // rofile_details
  
  //profile summary
  //education
  //skill
  //roject
})(jQuery);






//Ankit Profile js starts here //


function openEditProfileModal(profile) {
    document.getElementById("profile-overlay").classList.add("profile-active");
    document.getElementById("edit-profile-modal").classList.add("profile-active");

    document.getElementById("edit-profile-id").value = profile.candidate_id;
    document.getElementById("edit-candidate-name").value = profile.candidate_name;
    document.getElementById("edit-candidate-mobile").value = profile.candidate_mobile;
    document.getElementById("edit-candidate-email").value = profile.candidate_email;
    document.getElementById("edit-location").value = profile.location;

    document.getElementById("edit-education").value = profile.education;

    document.getElementById("edit-industry").value = profile.industry;
    document.getElementById("edit-budget-min").value = profile.budget_min;
    document.getElementById("edit-budget-max").value = profile.budget_max;
    document.getElementById("edit-exp-max").value = profile.exp_max;

    
}

function closeEditProfileModal() {
    document.getElementById("profile-overlay").classList.remove("profile-active");
    document
        .getElementById("edit-profile-modal")
        .classList.remove("profile-active");
}



function closeProfileModalsIfClickedOutside(event) {
    const profile_overlay = document.getElementById("profile-overlay");
    if (event.target === profile_overlay) {
        closeEditProfileModal(); // Close edit modal
    }
}


function setupCloseProfileModalOnClick() {
    const profile_overlay = document.getElementById("profile-overlay");
    profile_overlay.onclick = (event) => closeProfileModalsIfClickedOutside(event);
}



function deleteProfile(id) {
    if (confirm("Are you sure you want to delete this profile record?")) {
        window.location.href = "profile.php?delete_profile=" + id;
    }
}

// // Ankit profile js ends here //


// Ankit profile summary js starts here//

function openAddSummaryModal() {
  document.getElementById("summary-overlay").classList.add("summary-active");
  document.getElementById("add-summary-modal").classList.add("summary-active");
}

function openEditSummaryModal(summary) {
  document.getElementById("summary-overlay").classList.add("summary-active");
  document.getElementById("edit-summary-modal").classList.add("summary-active");

  document.getElementById("edit-summary-id").value = summary.id;
  document.getElementById("edit-profile-summary").value = summary.pro_summary;

}

function closeEditSummaryModal() {
  document.getElementById("summary-overlay").classList.remove("summary-active");
  document.getElementById("edit-summary-modal").classList.remove("summary-active");
}

function closeAddSummaryModal() {
  document.getElementById("summary-overlay").classList.remove("summary-active");
  document.getElementById("add-summary-modal").classList.remove("summary-active");
}


function closeSummaryModalsIfClickedOutside(event) {
  const summary_overlay = document.getElementById("summary-overlay");
  if (event.target === summary_overlay) {
      closeEditSummaryModal(); // Close edit modal
      closeAddSummaryModal();
  }
}


function setupCloseSummaryModalOnClick() {
  const summary_overlay = document.getElementById("summary-overlay");
  summary_overlay.onclick = (event) => closeSummaryModalsIfClickedOutside(event);
}



function deleteSummary(id) {
  if (confirm("Are you sure you want to delete this summary record?")) {
      window.location.href = "profile.php?delete_summary=" + id;
  }
}
// Ankit profile summary js ends here //





// Ankit education section js is inluded start here//

function openAddEducationModal() {
  document.getElementById("overlay").classList.add("active");
  document.getElementById("add-modal").classList.add("active");
}

function openEditModal(education) {
  document.getElementById("overlay").classList.add("active");
  document.getElementById("edit-education-modal").classList.add("active");

  document.getElementById("edit-id").value = education.id;
  document.getElementById("edit-education_level").value =
    education.education_level;
  document.getElementById("edit-university").value =
    education.university_institute;
  document.getElementById("edit-course").value = education.course;
  document.getElementById("edit-specialization").value =
    education.specialization;
  document.getElementById("edit-course_type").value = education.course_type;
  document.getElementById("edit-starting_year").value = education.starting_year;
  document.getElementById("edit-ending_year").value = education.ending_year;
  document.getElementById("edit-grading_system").value =
    education.grading_system;
  document.getElementById("edit-grade").value = education.grade;
}

function closeEditEducationModal() {
  document.getElementById("overlay").classList.remove("active");
  document.getElementById("edit-education-modal").classList.remove("active");
}

function closeAddEducationModal() {
  document.getElementById("overlay").classList.remove("active");
  document.getElementById("add-modal").classList.remove("active");
}

function closeEducationModalsIfClickedOutside(event) {
    const education_overlay = document.getElementById("overlay");
    if (event.target === education_overlay) {
      closeEditEducationModal(); // Close edit modal
      closeAddEducationModal(); // Close add modal
    }
  }
  
  
  function setupCloseEducationModalOnClick() {
    const education_overlay = document.getElementById("overlay");
    education_overlay.onclick = (event) => closeEducationModalsIfClickedOutside(event);
  }


function deleteEducation(id) {
  if (confirm("Are you sure you want to delete this education record?")) {
    window.location.href = "profile.php?delete_education=" + id;
  }
}

// Ankit education section js ends here//

// Ankit experience section js start here//
function openAddExperienceModal() {
  document
    .getElementById("experience-overlay")
    .classList.add("experience-active");
  document
    .getElementById("add-experience-modal")
    .classList.add("experience-active");
}
// Update the minimum date for 'Working Till' based on 'Joining Date'
function updateWorkingTillMinDate() {
  const joiningDate = document.getElementById("joining_date").value;
  const workingTillField = document.getElementById("working_till");

  if (joiningDate) {
    workingTillField.min = joiningDate; // Set the minimum date
  }
}

// Calculate work experience duration
function calculateWorkExperience() {
  const joiningDate = document.getElementById("joining_date").value;
  const workingTillDate = document.getElementById("working_till").value;
  const workExperienceField = document.getElementById("work_experience");

  if (joiningDate && workingTillDate) {
    const startDate = new Date(joiningDate);
    const endDate = new Date(workingTillDate);

    if (endDate >= startDate) {
      const totalDays = Math.floor(
        (endDate - startDate) / (1000 * 60 * 60 * 24)
      );
      const years = Math.floor(totalDays / 365);
      const months = Math.floor((totalDays % 365) / 30);
      const days = totalDays % 30;

      workExperienceField.value = `${years} years, ${months} months, ${days} days`;
    } else {
      workExperienceField.value = "Invalid dates";
    }
  }
}

// Ensure the readonly field gets submitted with the form
document.querySelector("#experience-form").addEventListener("submit", (e) => {
  calculateWorkExperience(); // Recalculate work experience just before submission
});

function openEditExperienceModal(experience) {
  document
    .getElementById("experience-overlay")
    .classList.add("experience-active");
  document
    .getElementById("edit-experience-modal")
    .classList.add("experience-active");

  document.getElementById("edit-exp-id").value = experience.companyID;
  document.getElementById("edit-company-name").value = experience.CompanyName;
  document.getElementById("edit-job-title").value = experience.JobTitle;
  document.getElementById("edit-joining-date").value = experience.JoiningDate;
  document.getElementById("edit-working-till").value = experience.WorkingTill;
  document.getElementById("edit-experience").value = experience.Experience;
  document.getElementById("edit-salary").value = experience.Salary;
  document.getElementById("edit-skills").value = experience.SkillsUsed;
  document.getElementById("edit-job-profile").value = experience.JobProfile;
  document.getElementById("edit-company-location").value =
    experience.companyLocation;
}

function closeEditExperienceModal() {
  document
    .getElementById("experience-overlay")
    .classList.remove("experience-active");
  document
    .getElementById("edit-experience-modal")
    .classList.remove("experience-active");
}

function closeAddExperienceModal() {
  document
    .getElementById("experience-overlay")
    .classList.remove("experience-active");
  document
    .getElementById("add-experience-modal")
    .classList.remove("experience-active");
}

function closeExperienceModalsIfClickedOutside(event) {
  const experience_overlay = document.getElementById("experience-overlay");
  if (event.target === experience_overlay) {
    closeEditExperienceModal(); // Close edit modal
    closeAddExperienceModal(); // Close add modal
  }
}


function setupCloseExperienceModalOnClick() {
  const experience_overlay = document.getElementById("experience-overlay");
  experience_overlay.onclick = (event) => closeExperienceModalsIfClickedOutside(event);
}



function deleteExperience(id) {
  if (confirm("Are you sure you want to delete this experience record?")) {
    window.location.href = "profile.php?delete_experience=" + id;
  }
}
// Ankit experience section js ends here//

//Ankit project section js start here//

function openAddProjectModal() {
  document.getElementById("project-overlay").classList.add("project-active");
  document.getElementById("add-project-modal").classList.add("project-active");
}

function openEditProjectModal(project) {
  document.getElementById("project-overlay").classList.add("project-active");
  document.getElementById("edit-project-modal").classList.add("project-active");

  document.getElementById("edit-proj-id").value = project.id;
  document.getElementById("edit-project-title").value = project.project_title;
  document.getElementById("edit-summary").value = project.project_summary;
  document.getElementById("edit-status").value = project.project_status;
  document.getElementById("edit-duration").value = project.project_duration;
}

function closeEditProjectModal() {
  document.getElementById("project-overlay").classList.remove("project-active");
  document
    .getElementById("edit-project-modal")
    .classList.remove("project-active");
}

function closeAddProjectModal() {
  document.getElementById("project-overlay").classList.remove("project-active");
  document
    .getElementById("add-project-modal")
    .classList.remove("project-active");
}

function closeProjectModalsIfClickedOutside(event) {
  const overlay = document.getElementById("project-overlay");
  if (event.target === overlay) {
    closeEditProjectModal(); // Close edit modal
    closeAddProjectModal(); // Close add modal
  }
}


function setupCloseProjectModalOnClick() {
  const overlay = document.getElementById("project-overlay");
  overlay.onclick = (event) => closeProjectModalsIfClickedOutside(event);
}



function deleteProject(id) {
  if (confirm("Are you sure you want to delete this project record?")) {
    window.location.href = "profile.php?delete_project=" + id;
  }
}
//Ankit project section js ends here//



// Ankit social connects js starts here //



function openEditSocialModal(social) {
    document.getElementById("social-overlay").classList.add("social-active");
    document.getElementById("edit-social-modal").classList.add("social-active");

    document.getElementById("edit-social-id").value = social.candidate_id;
    document.getElementById("edit-github").value = social.Github;
    document.getElementById("edit-linkedin").value = social.LinkedIn;
    document.getElementById("edit-other").value = social.Other;
}

function closeEditSocialModal() {
    document.getElementById("social-overlay").classList.remove("social-active");
    document
        .getElementById("edit-social-modal")
        .classList.remove("social-active");
}


function closeSocialModalsIfClickedOutside(event) {
    const social_overlay = document.getElementById("social-overlay");
    if (event.target === social_overlay) {
        closeEditSocialModal(); // Close edit modal
    }
}


function setupCloseSocialModalOnClick() {
    const social_overlay = document.getElementById("social-overlay");
    social_overlay.onclick = (event) => closeSocialModalsIfClickedOutside(event);
}



function deleteSocial(id) {
    if (confirm("Are you sure you want to delete this social record?")) {
        window.location.href = "profile.php?delete_social=" + id;
    }
}

// Ankit social connects js ends here //
