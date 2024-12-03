<?php
// Ankit Badhani did work on 22/11/2024
// Database connection
$conn = new mysqli("localhost", "root", "", "boosttech");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct locations from the candidates table
$locationQuery = "SELECT DISTINCT location as locationDetail FROM candidate WHERE location IS NOT NULL AND location != ''";
$locationResult = $conn->query($locationQuery) or die("Query failed: " . $conn->error);
?>

<style>
    #filter_bar {
        padding: 15px;
        border-radius: 20px;
        margin-bottom: 20px;
    }

    .form-select,
    .form-control {
        margin-bottom: 0px;
    }

    #experienceDetail,
    #locationDetail {
        margin-top: 10px;
    }
</style>

<div class="container bg-info" id="filter_bar">
    <form action="findCandidate.php" method="GET">
        <div class="row g-2">
            <div class="col-md-10">
                <div class="d-flex  gap-2">
                    <div class="col-md-3">
                        <label for="skills" class="visually-hidden">Skills</label>
                        <input type="text" id="skills" name="skills" class="form-control border-0"
                            placeholder="Skills or Expertise"
                            value="<?php echo isset($_GET['skills']) ? htmlspecialchars($_GET['skills']) : ''; ?>" />
                    </div>
                    <div class="col-md-3">
                        <label for="experienceDetail" class="visually-hidden">Experience</label>
                        <select id="experienceDetail" name="experienceDetail" class="form-select border-0">
                            <option value="" <?php echo !isset($_GET['experienceDetail']) || $_GET['experienceDetail'] == '' ? 'selected' : ''; ?>>Minimum Experience</option>
                            <?php for ($i = 0; $i <= 50; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($_GET['experienceDetail']) && $_GET['experienceDetail'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> years
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="locationDetail" class="visually-hidden">Location</label>
                        <select id="locationDetail" name="locationDetail" class="form-select border-0">
                            <option value="" <?php echo !isset($_GET['locationDetail']) || $_GET['locationDetail'] == '' ? 'selected' : ''; ?>>Select location...</option>
                            <?php if ($locationResult->num_rows > 0): ?>
                                <?php while ($location = $locationResult->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($location['locationDetail']); ?>" <?php echo (isset($_GET['locationDetail']) && $_GET['locationDetail'] == $location['locationDetail']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($location['locationDetail']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="" disabled>No locations available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="budget_max" class="visually-hidden">Maximum Budget</label>
                        <input type="text" id="budget_max" name="budget_max" class="form-control border-0"
                            placeholder="Maximum Budget"
                            value="<?php echo isset($_GET['budget_max']) ? htmlspecialchars($_GET['budget_max']) : ''; ?>" />
                    </div>
                </div>

            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark border-0 w-50">Filter</button>
            </div>
        </div>
    </form>
</div>

<?php
$conn->close();
?>