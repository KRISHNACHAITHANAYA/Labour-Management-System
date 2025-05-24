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

    // Fetch the job posting to be edited
    $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE job_id = ? AND employer_id = ?");
    $stmt->execute([$job_id, $user_id]);
    $job = $stmt->fetch();

    // If the job exists, display the edit form
    if ($job) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the new values from the form submission
            $job_title = $_POST['job_title'];
            $job_description = $_POST['job_description'];
            $skills_required = $_POST['skills_required'];
            $location = $_POST['location'];
            $salary = $_POST['salary'];
            $job_type = $_POST['job_type'];

            // Update the job posting in the database
            $stmt = $pdo->prepare("UPDATE job_postings SET 
                job_title = ?, 
                job_description = ?, 
                skills_required = ?, 
                location = ?, 
                salary = ?, 
                job_type = ? 
                WHERE job_id = ?");
            $stmt->execute([$job_title, $job_description, $skills_required, $location, $salary, $job_type, $job_id]);

            // Set a flash message for success
            set_flash_message('Job updated successfully!', 'success');

            // Redirect back to the employer dashboard
            header('Location: dashboard.php');
            exit();
        }
    } else {
        // If the job doesn't exist or doesn't belong to the employer, show an error
        set_flash_message('Error: You cannot edit this job!', 'error');
        header('Location: dashboard.php');
        exit();
    }
} else {
    // If no job_id is passed, redirect to the dashboard
    set_flash_message('No job ID provided!', 'error');
    header('Location: dashboard.php');
    exit();
}
?>

<?php include('header.php'); ?>

<h2>Edit Job Posting</h2>

<?php if (isset($job)): ?>
    <form method="POST">
        <div class="form-group">
            <label for="job_title">Job Title</label>
            <input type="text" class="form-control" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="job_description">Job Description</label>
            <textarea class="form-control" name="job_description" required><?php echo htmlspecialchars($job['job_description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="skills_required">Skills Required (comma separated)</label>
            <input type="text" class="form-control" name="skills_required" value="<?php echo htmlspecialchars($job['skills_required']); ?>" required>
        </div>

        <div class="form-group">
            <label for="location">Job Location</label>
            <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>
        </div>

        <div class="form-group">
            <label for="salary">Salary</label>
            <input type="number" class="form-control" name="salary" value="<?php echo htmlspecialchars($job['salary']); ?>" required>
        </div>

        <div class="form-group">
            <label for="job_type">Job Type</label>
            <select class="form-control" name="job_type" required>
                <option value="full-time" <?php echo ($job['job_type'] == 'full-time') ? 'selected' : ''; ?>>Full-Time</option>
                <option value="part-time" <?php echo ($job['job_type'] == 'part-time') ? 'selected' : ''; ?>>Part-Time</option>
                <option value="freelance" <?php echo ($job['job_type'] == 'freelance') ? 'selected' : ''; ?>>Freelance</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Job</button>
    </form>
<?php endif; ?>

<?php include('footer.php'); ?>
