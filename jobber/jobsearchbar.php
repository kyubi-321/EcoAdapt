<?php
//Ankit Badhani did work on 22/11/2024
// Fetch distinct locations from the jobs table
$locationQuery = "SELECT DISTINCT location as locationDetail FROM jobs WHERE location IS NOT NULL AND location != ''";
$locationResult = $conn->query($locationQuery) or die("Query failed: " . $conn->error);
?>

<style>
    #search_bar {
        padding: 15px;
        border-radius: 20px;
        margin-bottom: 20px;
    }

    .form-select,
    .form-control {
        margin-bottom: 0px;
    }
    #experienceDetail , #locationDetail{
        margin-top: 10px;
    }

    
</style>

<div class="container bg-primary" id="search_bar">
    <form action="jobs.php" method="GET">
        <div class="row g-2">
            <div class="col-md-10">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="keyword" class="visually-hidden">Keyword</label>
                        <input type="text" id="keyword" name="keyword" class="form-control border-0"
                            placeholder="Job Title, Skills, or Company"
                            value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" />
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                </div>
            </div>
            <div class="col-md-2 ">
                <button type="submit" class="btn btn-dark border-0 w-50">Search</button>
            </div>
        </div>
    </form>
</div>

<?php
$conn->close();
?>
