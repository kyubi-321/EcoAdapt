<div class="container-xxl py-5">
    <div class="container">
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Explore By Category</h1>
        <div class="row g-4">
            <?php
            include 'dbconnect.php';

            // Define the category-icon mapping
            $categoryIcons = [
                'Marketing' => 'fa-mail-bulk',
                'Education' => 'fa-book',
                'Human Resource' => 'fa-user-tie',
                'Project Management' => 'fa-tasks',
                'Business Development' => 'fa-chart-line',
                'Sales' => 'fa-hands-helping',
                'Teaching & Education' => 'fa-chalkboard-teacher',
                'Design & Creative' => 'fa-palette',
                'Uncategorized' => 'fa-folder-open', // Default icon for Uncategorized
                // Add more categories and icons as needed
            ];

            // Updated query to include "Uncategorized" jobs
            $query = "SELECT IF(category IS NULL OR category = '', 'Uncategorized', category) AS category, COUNT(*) AS vacancy_count FROM jobs GROUP BY category";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $delay = 0.1;
                while ($row = $result->fetch_assoc()) {
                    $category = $row['category']; // "Uncategorized" is already handled in the query

                    // Get the icon class for this category
                    $iconClass = isset($categoryIcons[$category]) ? $categoryIcons[$category] : 'fa-folder'; // Default icon

                    echo '
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="' . $delay . 's">
                        <a class="cat-item rounded p-4" href="jobs.php?category=' . urlencode($category) . '">
                            <i class="fa fa-3x ' . $iconClass . ' text-primary mb-4"></i>
                            <h6 class="mb-3">' . htmlspecialchars($category) . '</h6>
                            <p class="mb-0">' . $row['vacancy_count'] . ' Vacancy</p>
                        </a>
                    </div>';
                    $delay += 0.2;
                }
            } else {
                echo '<p class="text-center">No categories available.</p>';
            }
            ?>
        </div>
        <div class="text-center mt-4">
            <a href="categories.php" class="btn btn-primary">Explore All Categories</a>
        </div>
    </div>
</div>
