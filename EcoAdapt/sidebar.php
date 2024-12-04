<div class="col-md-3">
    <div class="left-panel bg-light text-dark vh-100" id="sidebar">
        <h2 class="text-center py-3 fs-5">Profile Section</h2>
        <ul class="list-group list-unstyled">

            <!-- Profile Menu -->
            <li class="list-group-item bg-light py-1 px-2">
                <a href="javascript:void(0);"
                    class="nav-link text-dark d-flex justify-content-between align-items-center profile-button p-1"
                    onclick="toggleProfileMenuAndLoadContent()">
                    Profile
                    <i class="bi bi-caret-down-fill caret-icon"></i>
                </a>
                <ul class="list-unstyled ps-3 submenu" id="profileSubMenu" style="display: none;">
                    <li><a href="#profileDetails" class="nav-link text-dark load-submenu p-1">Profile Details</a></li>
                    <li><a href="#profileSummaryDetails" class="nav-link text-dark load-submenu p-1">Profile Summary</a></li>
                    <li><a href="#educationDetails" class="nav-link text-dark load-submenu p-1">Education</a></li>
                    <li><a href="#experienceDetails" class="nav-link text-dark load-submenu p-1">Experience</a></li>
                    <li><a href="#skillDetails" class="nav-link text-dark load-submenu p-1">Skills</a></li>
                    <li><a href="#projectDetails" class="nav-link text-dark load-submenu p-1">Projects</a></li>
                    <li><a href="#socialConnectDetails" class="nav-link text-dark load-submenu p-1">Social Connects</a></li>

                </ul>
            </li>

            <!-- Saved Jobs -->
            <li class="list-group-item bg-light py-1 px-2">
                <a href="saved_jobs.php" class="nav-link text-dark load-content p-1">Saved Jobs</a>
            </li>
            <!-- Applied Jobs -->
            <li class="list-group-item bg-light py-1 px-2">
                <a href="applied_jobs.php" class="nav-link text-dark load-content p-1">Applied Jobs</a>
            </li>
            <!-- Logout -->
            <li class="list-group-item bg-danger py-1 px-2">
                <a href="logout.php" class="nav-link text-light p-1">Logout</a>
            </li>
        </ul>
    </div>
</div>
<script>
    async function toggleProfileMenuAndLoadContent() {
        const parentLi = document.querySelector('.profile-button').parentElement;
        const caretIcon = parentLi.querySelector('.caret-icon');
        const submenu = parentLi.querySelector('.submenu');

        // Toggle submenu visibility
        if (parentLi.classList.contains('open')) {
            parentLi.classList.remove('open');
            caretIcon.classList.remove('bi-caret-up-fill');
            caretIcon.classList.add('bi-caret-down-fill');
            submenu.style.display = 'none';
        } else {
            // Close any other open submenus
            document.querySelectorAll('.open').forEach(openItem => {
                openItem.classList.remove('open');
                openItem.querySelector('.caret-icon').classList.remove('bi-caret-up-fill');
                openItem.querySelector('.caret-icon').classList.add('bi-caret-down-fill');
                openItem.querySelector('.submenu').style.display = 'none';
            });

            parentLi.classList.add('open');
            caretIcon.classList.remove('bi-caret-down-fill');
            caretIcon.classList.add('bi-caret-up-fill');
            submenu.style.display = 'block';
        }

        // Reset flag to allow reloading the profile content
        isProfileContentLoaded = false;

        // Always load content even if it has been loaded before
        await loadAllProfileContent();

        // Close sidebar on small screens
        closeSidebarOnSmallScreen();
    }

    async function loadAllProfileContent() {
        // List of all profile files (in the desired order)
        const profileFiles = [
            './profile/profile_des.php',
            './profile/profile_details.php',
            './profile/profile_summary.php',
            './profile/profile_education.php',
            './profile/profile_experience.php',
            './profile/profile_skill.php',
            './profile/profile_projects.php',
            './profile/profile_socialconnects.php'
        ];

        // Show loading indicator while content is being fetched
        const contentDiv = document.querySelector('#content');
        // contentDiv.innerHTML = '<div id="loading"></div>';

        try {
            // Fetch and append each file's content in the exact order
            const contentSections = []; // Array to hold all sections

            for (const filePath of profileFiles) {
                const response = await fetch(filePath);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.text();
                const section = document.createElement('div'); // Create a new div for each section
                section.innerHTML = data;
                contentSections.push(section); // Store the sections in the array
            }

            // Once all files are fetched, clear loading message and append the full profile content
            contentDiv.innerHTML = ''; // Clear the loading message
            contentSections.forEach(section => {
                contentDiv.appendChild(section); // Append each section sequentially
            });

        } catch (err) {
            console.error('Error loading profile content:', err);
            contentDiv.innerHTML = '<div class="error">Failed to load profile content. Please try again.</div>';
        }
    }

    // Function to close sidebar on small screens
    function closeSidebarOnSmallScreen() {
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.remove('visible');
            document.getElementById('sidebar').classList.add('hidden');
        }
    }

    // Add click event for menu items
    document.querySelectorAll('.load-content').forEach(item => {
        item.addEventListener('click', closeSidebarOnSmallScreen);
    });

    // Toggling sidebar visibility on hamburger icon click
    // document.querySelector('#toggle-sidebar').addEventListener('click', function() {
    //     document.getElementById('sidebar').classList.toggle('hidden');
    //     document.getElementById('content').classList.toggle('sidebar-open');
    // });
</script>