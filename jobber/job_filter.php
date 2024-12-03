<?php  
// include 'header.php';

// Fetch unique locations, education, industries, and skills from the database
$locations = $mysqli->query("SELECT DISTINCT location FROM jobs WHERE job_status = 'open'");
$educations = $mysqli->query("SELECT DISTINCT education FROM jobs WHERE job_status = 'open'");
$industries = $mysqli->query("SELECT DISTINCT industry FROM jobs WHERE job_status = 'open'");
$skills = $mysqli->query("SELECT DISTINCT skills FROM jobs WHERE job_status = 'open'");

// Fetch all jobs for the search functionality
$allJobs = $mysqli->query("SELECT job_title, location, education, industry, skills FROM jobs WHERE job_status = 'open'");
$jobTitles = [];
while ($row = $allJobs->fetch_assoc()) {
    $jobTitles[] = $row['job_title'];
   }
?>
<style>
    /* #filterForm{
        margin-left: 3vw;
        margin-right: 2vw;
        margin-top: 5vh;
        background-color: gainsboro;
        border-radius: 15px;
        padding-left: 3vw;
        padding-top: 2vh;
    }

    #filterForm li label{
        width: 90%;
    } */
    #filterForm {
    margin-left: 3vw;
    margin-right: 2vw;
    margin-top: 5vh;
    background-color: gainsboro;
    border-radius: 15px;
    padding: 20px;
}

#filterForm h2 {
    font-size: 18px;
    margin-bottom: 15px;
}

.filter-section1 {
    margin-bottom: 20px;
    padding: 10px 0;
    border-bottom: 1px solid #ccc;
}

.filter-title1 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.filter-title1 h3 {
    margin: 0;
    font-size: 16px;
}

.filter-title1 .app-icon {
    margin-left: 10px;
    transform: rotate(0deg);
    transition: transform 0.3s;
}

.filter-title1 .app-icon.close {
    transform: rotate(180deg);
}

.search-box {
    margin: 10px 0;
}

.search-box input[type="text"] {
    width: 90%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

.filter-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.filter-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.filter-item label {
    display: flex;
    align-items: center;
    font-size: 14px;
    cursor: pointer;
    width: 100%;
}

.filter-item input[type="checkbox"] {
    margin-right: 10px;
    width: 16px;
    height: 16px;
    accent-color: #007bff; /* Customizes checkbox color */
}

.show-more {
    display: inline-block;
    margin-top: 10px;
    font-size: 14px;
    color: #007bff;
    cursor: pointer;
    text-decoration: none;
}

.show-more:hover {
    text-decoration: underline;
}

.no-results {
    text-align: center;
    color: red;
    font-size: 14px;
    margin-top: 10px;
}

.experience-options label,
.chip-container label {
    display: flex;
    align-items: center;
    font-size: 14px;
    cursor: pointer;
    margin-bottom: 8px;
}

.chip-container label input[type="checkbox"],
.experience-options label input[type="checkbox"] {
    margin-right: 10px;
    width: 16px;
    height: 16px;
    accent-color: #007bff;
}


</style>
<form id="filterForm" method="GET" action="jobs.php" > <!-- Add form for filter submission -->
    <div class="filter-sidebar">
        <div class="filter-heading">
            <h2>All Filters</h2>
        </div>
        
        <!-- Location Filter -->
        <div class="filter-section1">
            <div class="filter-title1">
                <h3>Location</h3>
                <i class="app-icon app-icon-arrow close" style="cursor: pointer; font-size: 16px;" onclick="toggleFilterSection(this)"></i>
            </div>
            <div class="search-box">
                <input type="text" id="locationSearch" placeholder="Search Location" onkeyup="filterOptions('location')">
            </div>
            <ul class="filter-list" id="locationList">
                <li class="no-results" style="display:none; color: red;">Not Available</li> <!-- Add this line -->
                <?php $locationCount = 0; ?>
                <?php while ($row = $locations->fetch_assoc()): ?>
                    <li class="filter-item" style="<?php echo $locationCount >= 6 ? 'display:none;' : ''; ?>">
                        <label>
                            <input type="checkbox" name="location[]" value="<?php echo htmlspecialchars($row['location']); ?>" onchange="updateFilters()"> 
                            <?php echo htmlspecialchars($row['location']); ?>
                        </label>
                    </li>
                    <?php $locationCount++; ?>
                <?php endwhile; ?>
            </ul>
            <?php if ($locationCount > 6): ?>
                <a href="javascript:void(0);" class="show-more" id="moreLocations" onclick="toggleFilterOptions('location', this)">
                    <i class="app-icon app-icon-arrow" style="font-size: 14px;"></i> Show More (<?php echo $locationCount - 6; ?> more)
                </a>
            <?php endif; ?>
        </div>

        <!-- Education Filter -->
        <div class="filter-section1">
            <div class="filter-title1">
                <h3>Education</h3>
                <i class="app-icon app-icon-arrow close" style="cursor: pointer; font-size: 16px;" onclick="toggleFilterSection(this)"></i>
            </div>
            <div class="search-box">
                <input type="text" id="educationSearch" placeholder="Search Education" onkeyup="filterOptions('education')">
            </div>
            <ul class="filter-list" id="educationList">
                <?php $educationCount = 0; ?>
                <?php while ($row = $educations->fetch_assoc()): ?>
                    <li class="filter-item" style="<?php echo $educationCount >= 6 ? 'display:none;' : ''; ?>">
                        <label>
                            <input type="checkbox" name="education[]" value="<?php echo htmlspecialchars($row['education']); ?>" onchange="updateFilters()"> 
                            <?php echo htmlspecialchars($row['education']); ?>
                        </label>
                    </li>
                    <?php $educationCount++; ?>
                <?php endwhile; ?>
            </ul>
            <?php if ($educationCount > 6): ?>
                <a href="javascript:void(0);" class="show-more" id="moreEducations" onclick="toggleFilterOptions('education', this)">
                    <i class="app-icon app-icon-arrow" style="font-size: 14px;"></i> Show More (<?php echo $educationCount - 6; ?> more)
                </a>
            <?php endif; ?>
        </div>

        <!-- Industry Filter -->
        <div class="filter-section1">
            <div class="filter-title1">
                <h3>Industry</h3>
                <i class="app-icon app-icon-arrow close" style="cursor: pointer; font-size: 16px;" onclick="toggleFilterSection(this)"></i>
            </div>
            <div class="search-box">
                <input type="text" id="industrySearch" placeholder="Search Industry" onkeyup="filterOptions('industry')">
            </div>
            <ul class="filter-list" id="industryList">
                <?php $industryCount = 0; ?>
                <?php while ($row = $industries->fetch_assoc()): ?>
                    <li class="filter-item" style="<?php echo $industryCount >= 6 ? 'display:none;' : ''; ?>">
                        <label>
                            <input type="checkbox" name="industry[]" value="<?php echo htmlspecialchars($row['industry']); ?>" onchange="updateFilters()"> 
                            <?php echo htmlspecialchars($row['industry']); ?>
                        </label>
                    </li>
                    <?php $industryCount++; ?>
                <?php endwhile; ?>
            </ul>
            <?php if ($industryCount > 6): ?>
                <a href="javascript:void(0);" class="show-more" id="moreIndustries" onclick="toggleFilterOptions('industry', this)">
                    <i class="app-icon app-icon-arrow" style="font-size: 14px;"></i> Show More (<?php echo $industryCount - 6; ?> more)
                </a>
            <?php endif; ?>
        </div>

        <!-- Skill Filter -->
        <div class="filter-section1">
            <div class="filter-title1">
                <h3>Skill</h3>
                <i class="app-icon app-icon-arrow close" style="cursor: pointer; font-size: 16px;" onclick="toggleFilterSection(this)"></i>
            </div>
            <div class="search-box">
                <input type="text" id="skillSearch" placeholder="Search Skill" onkeyup="filterOptions('skills')">
            </div>
            <ul class="filter-list" id="skillsList">
                <?php $skillsCount = 0; ?>
                <?php while ($row = $skills->fetch_assoc()): ?>
                    <li class="filter-item" style="<?php echo $skillsCount >= 6 ? 'display:none;' : ''; ?>">
                        <label>
                            <input type="checkbox" name="skills[]" value="<?php echo htmlspecialchars($row['skills']); ?>" onchange="updateFilters()"> 
                            <?php echo htmlspecialchars($row['skills']); ?>
                        </label>
                    </li>
                    <?php $skillsCount++; ?>
                <?php endwhile; ?>
            </ul>
            <?php if ($skillsCount > 6): ?>
                <a href="javascript:void(0);" class="show-more" id="moreSkills" onclick="toggleFilterOptions('skills', this)">
                    <i class="app-icon app-icon-arrow" style="font-size: 14px;"></i> Show More (<?php echo $skillsCount - 6; ?> more)
                </a>
            <?php endif; ?>
        </div>

        <!-- Experience Filter -->
        <div class="filter-section1">
            <div class="filter-title1">
                <h3>Experience</h3>
            </div>
            <div class="experience-options">
                <label>
                    <input type="checkbox" name="experience[]" value="experienced" onchange="updateFilters()">
                    Experienced (> 2 years)
                </label>
                <br>
                <label>
                    <input type="checkbox" name="experience[]" value="entryLevel" onchange="updateFilters()">
                    Experienced (<= 2 years)
                </label>
            </div>
        </div>

        <!-- Job Posting Date Filter -->
        <div class="filter-section1">
            <div class="filter-title1">
                <h3>Job Posting Date</h3>
            </div>
            <div class="chip-container">
                <label>
                    <input type="checkbox" name="posted_date[]" value="" onchange="updateFilters()" checked>
                    All Dates
                </label>
                <br>
                <label>
                    <input type="checkbox" name="posted_date[]" value="7_days" onchange="updateFilters()">
                    Last 7 Days
                </label>
                <br>
                <label>
                    <input type="checkbox" name="posted_date[]" value="15_days" onchange="updateFilters()">
                    Last 15 Days
                </label>
            </div>
        </div>
    </div>
    <a href="jobs.php" class="link">Reset</a>

</form>

<!-- JavaScript for Filtering Options -->
<script>
function updateExperienceValue(value) {
    document.getElementById('experienceValue').textContent = value;
    updateFilters(); // Call updateFilters to update job listings
}

function updateFilters() {
    // Get form data and serialize it
    const formData = new URLSearchParams(new FormData(document.getElementById('filterForm'))).toString();

    // Fetch filtered results from jobs.php with the selected filters
    fetch('jobs.php?' + formData, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'  // Mark as AJAX request
        }
    })
    .then(response => response.text())
    .then(data => {
        // Replace only the job listings inside the #job-listings-row
        document.querySelector('#job-listings-row').innerHTML = data;
    })
    .catch(error => console.error('Error fetching filtered jobs:', error));
}

function toggleFilterSection(icon) {
    const filterList = icon.closest('.filter-section1').querySelector('.filter-list');
    const items = filterList.querySelectorAll('.filter-item');

    // Toggle visibility of the filter items
    items.forEach(item => {
        item.style.display = (item.style.display === 'none' || item.style.display === '') ? 'block' : 'none';
    });
}

function toggleFilterOptions(filter, link) {
    const filterList = document.getElementById(filter + 'List');
    const items = filterList.querySelectorAll('.filter-item');

    // Toggle display of items
    items.forEach((item, index) => {
        if (index >= 6) {
            item.style.display = (item.style.display === 'none') ? 'block' : 'none';
        }
    });

    // Change the link text accordingly
    if (items[6].style.display === 'block') {
        link.innerHTML = `<i class="app-icon app-icon-arrow close" style="font-size: 14px;"></i> Hide`;
    } else {
        link.innerHTML = `<i class="app-icon app-icon-arrow" style="font-size: 14px;"></i> Show More (${items.length - 6} more)`;
    }
}

function filterOptions(filter) {
    const searchBox = document.getElementById(filter + 'Search');
    const filterList = document.getElementById(filter + 'List');
    const items = filterList.querySelectorAll('.filter-item');
    const noResults = filterList.querySelector('.no-results');

    const searchTerm = searchBox.value.toLowerCase();
    let hasResults = false;

    items.forEach(item => {
        const label = item.textContent.toLowerCase();
        if (label.includes(searchTerm)) {
            item.style.display = 'block';
            hasResults = true; // Found a matching item
        } else {
            item.style.display = 'none';
        }
    });

    // Show or hide the no results message
    noResults.style.display = hasResults ? 'none' : 'block';
}
</script>
