<?php
// Database connection
include 'dbconnect.php';

// Create the feedback table if it doesn't exist
$tableCreationQuery = "
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    user_profession VARCHAR(255) NOT NULL,
    user_message TEXT NOT NULL,
    user_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($tableCreationQuery)) {
    die("Error creating table: " . $conn->error);
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $user_profession = $_POST['user_profession'];
    $user_message = $_POST['user_message'];
    $user_image = null;

    // Handle image upload
    if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === 0) {
        $image_dir = __DIR__ . "/assets/uploads/";
        if (!is_dir($image_dir)) mkdir($image_dir, 0777, true);
        $user_image_path = $image_dir . basename($_FILES['user_image']['name']);
        
        if (move_uploaded_file($_FILES['user_image']['tmp_name'], $user_image_path)) {
            $user_image = "assets/uploads/" . basename($_FILES['user_image']['name']);
        } else {
            echo "Error: Unable to upload image.";
            exit;
        }
    }

    $sql = "INSERT INTO feedback (user_name, user_profession, user_message, user_image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $user_name, $user_profession, $user_message, $user_image);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
    exit;
}

include 'header.php';
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">We Value Your Feedback</h2>

    <form id="feedbackForm" enctype="multipart/form-data" method="POST" class="mt-4 custom-spacing">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="form-group mb-1">
                    <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter your name" required>
                </div>
                <div class="form-group mb-1">
                    <input type="text" name="user_profession" id="user_profession" class="form-control" placeholder="Enter your profession" required>
                </div>
                <div class="form-group mb-1">
                    <textarea name="user_message" id="user_message" class="form-control" placeholder="Share your thoughts" rows="4" required></textarea>
                </div>
                <div class="form-group mb-1">
                    <input type="file" name="user_image" id="user_image" class="form-control" accept="image/*">
                </div>
                <!-- Green Button -->
                <button type="submit" class="btn btn-success w-100">Submit Feedback</button>
            </div>
        </div>
    </form>
</div>
