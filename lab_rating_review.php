<?php
// Include necessary files
include('db.php');
include('functions.php');

// Check if the user is logged in and has the laborer role
if (!is_logged_in() || $_SESSION['role'] !== 'laborer') {
    redirect('login.php');
}

$laborer_id = $_SESSION['user_id'];
$review_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reviewee_id = $_POST['reviewee_id'];  // ID of the employer or job
    $rating = $_POST['rating'];  // Rating from 1 to 5
    $review_text = $_POST['review_text'];  // Review content

    // Validate the review data
    if (empty($reviewee_id) || empty($rating) || empty($review_text)) {
        $review_error = 'Please fill in all fields.';
    } elseif ($rating < 1 || $rating > 5) {
        $review_error = 'Please provide a rating between 1 and 5.';
    } else {
        // Insert the review into the database
        $stmt = $pdo->prepare("INSERT INTO ratings_reviews (reviewer_id, reviewee_id, rating, review_text) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$laborer_id, $reviewee_id, $rating, $review_text]);

        // Redirect to a confirmation page or a success message
        echo "Thank you for your feedback! Your review has been submitted.";
        exit;
    }
}

?>

<?php include('header.php'); ?>

<h2>Leave a Rating and Review</h2>

<!-- Review Form -->
<form method="POST">
    <div class="form-group">
        <label for="reviewee_id">Review for (Employer/Job ID):</label>
        <input type="text" name="reviewee_id" class="form-control" placeholder="Enter Employer or Job ID" required>
    </div>

    <div class="form-group">
        <label for="rating">Rating (1-5):</label>
        <input type="number" name="rating" class="form-control" placeholder="Enter Rating" min="1" max="5" required>
    </div>

    <div class="form-group">
        <label for="review_text">Review Text:</label>
        <textarea name="review_text" class="form-control" placeholder="Write your review" rows="4" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit Review</button>

    <?php if ($review_error): ?>
        <div class="alert alert-danger mt-3"><?php echo $review_error; ?></div>
    <?php endif; ?>
</form>

<?php include('footer.php'); ?>
