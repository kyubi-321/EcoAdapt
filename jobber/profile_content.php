//profile_content
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconnect.php';


// Retrieve candidate_id from session
$candidate_id = $_SESSION['candidate_id'];

// Include database connection
// include 'db_connection.php'; 


// Fetch candidate education details
$education_query = "SELECT education_level, university_institute, course, specialization, course_type, starting_year, ending_year 
                    FROM candidate_education WHERE candidate_id = ?";
$education_stmt = $conn->prepare($education_query);
$education_stmt->bind_param("i", $candidate_id);
$education_stmt->execute();
$education_result = $education_stmt->get_result();
$education = $education_result->fetch_assoc();

// Fetch experience details
$experience_query = "SELECT CompanyName, JobTitle, JoiningDate, WorkingTill, Experience, Salary, SkillsUsed, JobProfile, companyLocation 
                     FROM experience WHERE candidate_id = ?";
$experience_stmt = $conn->prepare($experience_query);
$experience_stmt->bind_param("i", $candidate_id);
$experience_stmt->execute();
$experience_result = $experience_stmt->get_result();


// Fetch candidate project details
$project_query = "SELECT project_title, project_summary, project_status, project_duration, created_at, updated_at 
                  FROM project_details WHERE candidate_id = ?";
$project_stmt = $mysqli->prepare($project_query);
$project_stmt->bind_param("i", $candidate_id);
$project_stmt->execute();
$project_result = $project_stmt->get_result();

if ($candidate):
?>
    <!-- START Profile Details Section -->
    <div class="profile-summary" id="profileDetails">
        <h2>Personal Details <button id="editProfileButton" class="profile-edit-btn">
                <i class="fas fa-pencil-alt"></i>
            </button></h2>


        <p><span class="label"><i class="fas fa-user"></i></span> <span class="data"><?= htmlspecialchars($candidate['candidate_name']) ?></span></p>
        <p><span class="label"><i class="fas fa-envelope"></i></span> <span class="data"><?= htmlspecialchars($candidate['candidate_email']) ?></span></p>
        <p><span class="label"><i class="fas fa-phone-alt"></i></span> <span class="data"><?= htmlspecialchars($candidate['candidate_mobile']) ?></span></p>
        <p><span class="label"><i class="fas fa-cogs"></i></span> <span class="data"><?= htmlspecialchars($candidate['skill']) ?></span></p>
        <p><span class="label"><i class="fas fa-graduation-cap"></i></span> <span class="data"><?= htmlspecialchars($candidate['education']) ?></span></p>
        <p><span class="label"><i class="fas fa-briefcase"></i></span> <span class="data"><?= htmlspecialchars($candidate['industry']) ?></span></p>
        <p><span class="label"><i class="fas fa-clock"></i></span> <span class="data"><?= htmlspecialchars($candidate['exp_min']) ?> - <?= htmlspecialchars($candidate['exp_max']) ?> years</span></p>
    </div>

    <!-- Modal (This will be hidden by default and shown when the Edit button is clicked) -->
    <div id="editModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn" id="closeBtn">&times;</span>
            <h2>Edit Profile</h2>
            <form action="update_profile.php" method="POST">
                <p>
                    <label for="candidate_name"><i class="fas fa-user"></i>:</label>
                    <input type="text" id="candidate_name" name="candidate_name" value="<?= htmlspecialchars($candidate['candidate_name']) ?>" required>
                </p>

                <p>
                    <label for="candidate_email"><i class="fas fa-envelope"></i>:</label>
                    <input type="email" id="candidate_email" name="candidate_email" value="<?= htmlspecialchars($candidate['candidate_email']) ?>" required readonly>
                </p>

                <p>
                    <label for="candidate_mobile"><i class="fas fa-phone-alt"></i>:</label>
                    <input type="text" id="candidate_mobile" name="candidate_mobile" value="<?= htmlspecialchars($candidate['candidate_mobile']) ?>" required readonly>
                </p>

                <p>
                    <label for="skill"><i class="fas fa-cogs"></i>:</label>
                    <input type="text" id="skill" name="skill" value="<?= htmlspecialchars($candidate['skill']) ?>" required>
                </p>

                <p>
                    <label for="education"><i class="fas fa-graduation-cap"></i>:</label>
                    <input type="text" id="education" name="education" value="<?= htmlspecialchars($candidate['education']) ?>" required>
                </p>

                <p>
                    <label for="industry"><i class="fas fa-briefcase"></i>:</label>
                    <input type="text" id="industry" name="industry" value="<?= htmlspecialchars($candidate['industry']) ?>" required>
                </p>

                <p>
                    <label for="exp_min"><i class="fas fa-clock"></i>:</label>
                    <input type="number" id="exp_min" name="exp_min" value="<?= htmlspecialchars($candidate['exp_min']) ?>" required>
                    <input type="number" id="exp_max" name="exp_max" value="<?= htmlspecialchars($candidate['exp_max']) ?>" required>
                </p>

                <p>
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </p>
            </form>
        </div>
    </div>
    <!-- END Profile Details Section -->

<!-- Start Profile Summary Section -->
 <div>
    <?php
    include 'profile_summary.php'?>
 </div>

<!-- END Profile Summary Section -->





    <!--  Education Details Section -->
    <!-- <div class="education-details" id="educationDetails">
        <h2>Education <button id="editeducationButton" class="education-edit-btn">
                <i class="fas fa-pencil-alt"></i>
            </button></h2>
        <p><span class="label">Education Level:</span> <?= htmlspecialchars($education['education_level']) ?></p>
        <p><span class="label">University/Institute:</span> <?= htmlspecialchars($education['university_institute']) ?></p>
        <p><span class="label">Course:</span> <?= htmlspecialchars($education['course']) ?></p>
        <p><span class="label">Specialization:</span> <?= htmlspecialchars($education['specialization']) ?></p>
        <p><span class="label">Course Type:</span> <?= htmlspecialchars($education['course_type']) ?></p>
        <p><span class="label">Starting:</span> <?= htmlspecialchars($education['starting_year']) ?></p>
        <p><span class="label">Ending:</span> <?= htmlspecialchars($education['ending_year']) ?></p>

        <!-- Edit Button -->
    <!-- <button id="editeducationButton" class="education-edit-btn">
                <i class="fas fa-pencil-alt"></i>
            </button> -->
    <!-- </div> --> -->

    <!-- Edit Education Modal -->
    <!-- <div id="edit-education-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Edit Education Details</h2>
            <form id="edit-education-form" method="POST" action="update_education.php">
                <input type="hidden" name="candidate_id" value="<?= $candidate_id ?>">

                <label for="education_level">Education Level:</label>
                <input type="text" id="education_level" name="education_level" value="<?= htmlspecialchars($education['education_level']) ?>" required><br>

                <label for="university_institute">University/Institute:</label>
                <input type="text" id="university_institute" name="university_institute" value="<?= htmlspecialchars($education['university_institute']) ?>" required><br>

                <label for="course">Course:</label>
                <input type="text" id="course" name="course" value="<?= htmlspecialchars($education['course']) ?>" required><br>

                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" value="<?= htmlspecialchars($education['specialization']) ?>" required><br>

                <label for="course_type">Course Type:</label>
                <input type="text" id="course_type" name="course_type" value="<?= htmlspecialchars($education['course_type']) ?>" required><br>

                <label for="starting_year">Starting Year:</label>
                <input type="number" id="starting_year" name="starting_year" value="<?= htmlspecialchars($education['starting_year']) ?>" required><br>

                <label for="ending_year">Ending Year:</label>
                <input type="number" id="ending_year" name="ending_year" value="<?= htmlspecialchars($education['ending_year']) ?>" required><br>

                <button type="submit" class="btn save-btn">Save Changes</button>
            </form>

            <!-- Delete Button -->
    <form method="POST" action="delete_education.php" class="delete-form">
        <input type="hidden" name="candidate_id" value="<?= $candidate_id ?>">
        <button type="submit" class="btn delete-btn">
            <i class="fas fa-trash"></i> Delete
        </button>
    </form>
    </div>
    <!-- </div> -->

    <!-- <script src="js/education.js"></script> --> -->

    <!-- Experience Details Section -->
    <!-- Experience Details Section -->
    <!-- <div class="experience-container" id="experienceDetails">
        <h3>Work Experience <button id="experienceButton" class="edit-experience-btn">
                <i class="fas fa-pencil-alt"></i>
            </button></h3>
        <?php if ($experience_result->num_rows > 0): ?>
            <?php while ($row = $experience_result->fetch_assoc()): ?>
                <div class="experience-card">
                    <h4><?= htmlspecialchars($row['CompanyName']) ?> - <?= htmlspecialchars($row['JobTitle']) ?></h4>
                    <p><span class="label">Joining Date:</span> <?= htmlspecialchars($row['JoiningDate']) ?></p>
                    <p><span class="label">Working Till:</span> <?= htmlspecialchars($row['WorkingTill'] ?? 'Present') ?></p>
                    <p><span class="label">Experience:</span> <?= htmlspecialchars($row['Experience']) ?> years</p>
                    <p><span class="label">Salary:</span> Rs.<?= number_format($row['Salary'], 2) ?></p>
                    <p><span class="label">Skills Used:</span> <?= htmlspecialchars($row['SkillsUsed']) ?></p>
                    <p><span class="label">Job Profile:</span> <?= htmlspecialchars($row['JobProfile']) ?></p>
                    <p><span class="label">Company Location:</span> <?= htmlspecialchars($row['companyLocation']) ?></p>


                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No work experience found for the candidate.</p>
        <?php endif; ?>
    </div> -->


    <!-- Modal Structure -->
    <!-- <div id="experienceModal" class="modal">
        <div class="modal-content">
            <span id="closeModal" class="close-btn">&times;</span>
            <h2>Edit Experience</h2>
            <form action="update_experience.php" method="POST">
                <label for="companyName">Company Name:</label>
                <input type="text" id="companyName" name="companyName"
                    value="<?= htmlspecialchars($experience['companyName']) ?>" required><br>

                <label for="jobTitle">Job Title:</label>
                <input type="text" id="jobTitle" name="jobTitle"
                    value="<?= htmlspecialchars($experience['jobTitle']) ?>" required><br>

                <label for="joiningDate">Joining Date:</label>
                <input type="date" id="joiningDate" name="joiningDate"
                    value="<?= htmlspecialchars($experience['joiningDate']) ?>" required><br>

                <label for="workingTill">Working Till:</label>
                <input type="date" id="workingTill" name="workingTill"
                    value="<?= htmlspecialchars($experience['workingTill']) ?>" required><br>

                <label for="experience">Experience (in years):</label>
                <input type="number" id="experience" name="experience"
                    value="<?= htmlspecialchars($experience['experience']) ?>" required><br>

                <label for="salary">Salary:</label>
                <input type="number" id="salary" name="salary"
                    value="<?= htmlspecialchars($experience['salary']) ?>" required><br>

                <label for="skillsUsed">Skills Used:</label>
                <input type="text" id="skillsUsed" name="skillsUsed"
                    value="<?= htmlspecialchars($experience['skillsUsed']) ?>" required><br>

                <label for="jobProfile">Job Profile:</label>
                <input type="text" id="jobProfile" name="jobProfile"
                    value="<?= htmlspecialchars($experience['jobProfile']) ?>" required><br>

                <label for="companyLocation">Company Location:</label>
                <input type="text" id="companyLocation" name="companyLocation"
                    value="<?= htmlspecialchars($experience['companyLocation']) ?>" required><br>

                <button type="submit" name="update_experience" class="btn btn-primary">Update Experience</button>
            </form>
        </div>
    </div>

    <script src="js/experience.js"></script> -->

    <!-- Candidate Skills Section -->
    <!-- <div class="skills-container" id="skillDetails">
        <h2>Candidate Skills <button id='edit-skills-btn' class='edit-skills-btn'>
                <i class='fas fa-pencil-alt'></i>
            </button></h2>
        <?php
        // Query to fetch skills for the given candidate ID
        $sql = "SELECT skill_name FROM candidate_it_skills WHERE candidate_id = ?";

        if ($stmt_skills = $conn->prepare($sql)) {
            $stmt_skills->bind_param("i", $candidate_id); // Bind candidate_id
            $stmt_skills->execute();
            $result_skills = $stmt_skills->get_result();

            if ($result_skills->num_rows > 0) {
                // Display current skills in a list
                echo "<ul class='skills-list'>";
                while ($row = $result_skills->fetch_assoc()) {
                    $skills = explode(',', $row['skill_name']);
                    foreach ($skills as $skill) {
                        echo "<li>" . htmlspecialchars(trim($skill)) . "</li>";
                    }
                }
                echo "</ul>";

                // Button to open edit modal
                //         echo "<button id='edit-skills-btn' class='edit-skills-btn'>
                //     <i class='fas fa-pencil-alt'></i>
                //   </button>";



                // Modal for editing skills
                echo "<div id='edit-skills-modal' class='modal'>
                    <div class='modal-content'>
                        <span class='close-btn'>&times;</span>
                        <h2>Edit Candidate Skills</h2>
                        <p>Add skills that best define your expertise, e.g., Direct Marketing, Oracle, Java, etc. (Minimum 1)</p>
                        <form id='edit-skills-form' method='POST' action='update_skills.php'>
                            <input type='hidden' name='candidate_id' value='" . $candidate_id . "'>
                            <textarea id='skill-input' name='skill_names' required placeholder='Add your skills here...'>" . htmlspecialchars(implode(", ", $skills)) . "</textarea>
                            <button type='button' id='add-skill-btn' class='btn add-btn'>Add Skill</button>
                            
                           
                            <div class='skills-list-container'>
                                <ul id='edit-skills-list'></ul>
                            </div>
                            <button type='submit' class='btn save-btn'>Save Changes</button>
                        </form>
                    </div>
                </div>";
            } else {
                echo "<p>No skills found for this candidate.</p>";
            }

            $stmt_skills->close();
        }
        ?>
    </div> -->

    <!-- <script src="./js/skill.js"></script> -->

    <!-- Project Details -->
    <!-- <div class="project-details" id="projectDetails">
        <h3>Projects</h3>
        <?php if ($project_result->num_rows > 0): ?>
            <div class="project-cards">
                <?php while ($row = $project_result->fetch_assoc()): ?>
                    <div class="project-card">
                        <h4 class="project-card-title"><?= htmlspecialchars($row['project_title']) ?></h4>
                        <p class="project-summary">Summary: <?= htmlspecialchars($row['project_summary']) ?></p>
                        <p class="project-status">Status: <?= htmlspecialchars($row['project_status']) ?></p>
                        <p class="project-duration">Duration: <?= htmlspecialchars($row['project_duration']) ?></p>
                        <button id="projectButton" class="edit-project-btn">
                            <i class="fas fa-pencil-alt"></i>
                        </button>

                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No projects found.</p>
        <?php endif; ?>
    </div> -->

    <!-- Modal to Edit Project -->
    <!-- <div id="edit-project-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Edit Project</h2>
            <form id="edit-project-form" method="POST" action="update_project.php">
                <input type="hidden" name="candidate_id" value="<?= $row['candidate_id'] ?>"> <!-- Ensure the ID is passed here -->

    <input type="text" id="project-title" name="project_title" placeholder="Project Title" required>
    <textarea id="project-summary" name="project_summary" placeholder="Project Summary" required></textarea>
    <input type="text" id="project-status" name="project_status" placeholder="Project Status" required>
    <input type="text" id="project-duration" name="project_duration" placeholder="Project Duration" required>

    <!-- Save Button -->
    <button type="submit" class="btn save-btn">Save</button>

    <!-- Delete Icon -->
    <button type="button" id="delete-project-btn" class="btn delete-btn">
        <i class="fas fa-trash-alt"></i>
    </button>
    </form>
    </div>
    <!-- </div> -->

    <!-- <script src="js/projects.js"></script> -->





<?php else: ?>
    <!-- <p>Profile not found.</p> -->
<?php endif; ?> -->

<?php
$stmt->close();
$education_stmt->close();
$conn->close();
?>