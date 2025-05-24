<?php
// Include necessary files
include('db.php');
include('functions.php');

// Check if the user is logged in and has the employer role
if (!is_logged_in() || $_SESSION['role'] !== 'employer') {
    redirect('login.php');
}

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Check if the job posting belongs to the logged-in employer
    $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE job_id = ? AND employer_id = ?");
    $stmt->execute([$job_id, $user_id]);
    $job = $stmt->fetch();

    // If the job exists and belongs to the logged-in employer, delete it
    if ($job) {
        // Delete the job from the database
        $stmt = $pdo->prepare("DELETE FROM job_postings WHERE job_id = ?");
        $stmt->execute([$job_id]);

        // Set a flash message indicating success
        set_flash_message('Job deleted successfully!', 'success');
    } else {
        // If the job does not exist or doesn't belong to the employer, show an error
        set_flash_message('Error: You cannot delete this job!', 'error');
    }

    // Redirect back to the employer dashboard
    header('Location: dashboard.php');
    exit();
} else {
    // If no job_id is passed, redirect to the dashboard
    set_flash_message('No job ID provided!', 'error');
    header('Location: dashboard.php');
    exit();
}
?>
