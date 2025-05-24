<?php
// Include necessary files
include('db.php');
include('functions.php');

// Check if the user is logged in and has the employer role
if (!is_logged_in() || $_SESSION['role'] !== 'employer') {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form values from the POST request
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $skills_required = $_POST['skills_required'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $job_type = $_POST['job_type'];
    $employer_id = $_SESSION['user_id']; // Get the logged-in employer's user ID

    // Prepare and execute the SQL query to insert the job posting into the database
    $stmt = $pdo->prepare("INSERT INTO job_postings (job_title, job_description, skills_required, location, salary, job_type, employer_id) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$job_title, $job_description, $skills_required, $location, $salary, $job_type, $employer_id]);

    // Set a flash message for success
    set_flash_message('Job posted successfully!', 'success');

    // Redirect to the employer's dashboard
    header('Location: dashboard.php');
    exit();
}
?>

<?php include('header.php'); ?>

<h2>Post a New Job</h2>

<form method="POST">
    <div class="form-group">
        <label for="job_title">Job Title</label>
        <input type="text" class="form-control" name="job_title" placeholder="Enter job title" required>
    </div>

    <div class="form-group">
        <label for="job_description">Job Description</label>
        <textarea class="form-control" name="job_description" placeholder="Enter job description" required></textarea>
    </div>

    <div class="form-group">
        <label for="skills_required">Skills Required (comma separated)</label>
        <input type="text" class="form-control" name="skills_required" placeholder="Enter required skills" required>
    </div>

    <div class="form-group">
        <label for="location">Job Location</label>
        <input type="text" class="form-control" name="location" placeholder="Enter job location" required>
    </div>

    <div class="form-group">
        <label for="salary">Salary</label>
        <input type="number" class="form-control" name="salary" placeholder="Enter salary" required>
    </div>

    <div class="form-group">
        <label for="job_type">Job Type</label>
        <select class="form-control" name="job_type" required>
            <option value="full-time">Full-Time</option>
            <option value="part-time">Part-Time</option>
            <option value="freelance">Freelance</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Post Job</button>
</form>

<?php include('footer.php'); ?>
