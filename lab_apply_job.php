<?php
// Include necessary files
include('db.php');
include('functions.php');

// Check if the user is logged in and has the laborer role
if (!is_logged_in() || $_SESSION['role'] !== 'laborer') {
    redirect('login.php');
}

// Get the job ID from the GET request
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Get the job details from the database
    $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE job_id = ?");
    $stmt->execute([$job_id]);
    $job = $stmt->fetch();

    // If the job doesn't exist
    if (!$job) {
        set_flash_message('Job not found.', 'danger');
        header('Location: job_list.php');
        exit();
    }

    // Get the laborer's profile
    $stmt = $pdo->prepare("SELECT * FROM laborer_profiles WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $laborer = $stmt->fetch();

    // Skill matching
    $job_skills = explode(',', $job['skills_required']); // Skills required for the job
    $laborer_skills = explode(',', $laborer['skills']); // Skills of the laborer

    // Find matching skills
    $matching_skills = array_intersect($job_skills, $laborer_skills);

    // Check if there's a skill match
    if (count($matching_skills) > 0) {
        // Insert application into the database
        $stmt = $pdo->prepare("INSERT INTO applications (laborer_id, job_id, application_status) 
                               VALUES (?, ?, 'pending')");
        $stmt->execute([$_SESSION['user_id'], $job_id]);

        set_flash_message('You have successfully applied for the job!', 'success');
        header('Location: dashboard.php');
    } else {
        set_flash_message('Your skills do not match the job requirements.', 'danger');
    }
} else {
    set_flash_message('Invalid job ID.', 'danger');
    header('Location: job_list.php');
    exit();
}
?>

<?php include('header.php'); ?>

<h2>Apply for Job: <?php echo htmlspecialchars($job['job_title']); ?></h2>

<?php if (count($matching_skills) > 0): ?>
    <p>Your skills match the job requirements. You can apply for this job.</p>
    <form method="POST">
        <button type="submit" class="btn btn-primary">Apply Now</button>
    </form>
<?php else: ?>
    <p>Your skills do not match the job requirements. You cannot apply for this job.</p>
<?php endif; ?>

<?php include('footer.php'); ?>
