<?php
// Include necessary files
include('db.php');
include('functions.php');

// Check if the user is logged in and has the admin role
if (!is_logged_in() || !is_admin()) {
    redirect('login.php');
}

// Fetch all job postings from the database
$stmt = $pdo->prepare("SELECT * FROM job_postings");
$stmt->execute();
$jobs = $stmt->fetchAll();

// Handle the deletion of a job
if (isset($_GET['delete_job_id'])) {
    $job_id = $_GET['delete_job_id'];

    // Delete the job posting from the database
    $stmt = $pdo->prepare("DELETE FROM job_postings WHERE job_id = ?");
    $stmt->execute([$job_id]);

    // Set a flash message for successful deletion
    set_flash_message("Job posting deleted successfully.");
    redirect('manage_jobs.php');
}
?>

<?php include('header.php'); ?>

<!-- Display flash message -->
<?php echo get_flash_message(); ?>

<h2>Manage Job Postings</h2>
<p>Here you can manage job postings on the portal.</p>

<!-- Table to list all job postings -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Job Title</th>
            <th>Employer</th>
            <th>Location</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($jobs as $job) { ?>
            <tr>
                <td><?php echo $job['job_title']; ?></td>
                <td><?php
                    // Fetch the employer's name using their user_id
                    $stmt = $pdo->prepare("SELECT name FROM users WHERE user_id = ?");
                    $stmt->execute([$job['employer_id']]);
                    $employer = $stmt->fetch();
                    echo $employer ? $employer['name'] : 'N/A';
                ?></td>
                <td><?php echo $job['location']; ?></td>
                <td>$<?php echo number_format($job['salary'], 2); ?></td>
                <td>
                    <a href="edit_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-primary">Edit</a>
                    <a href="?delete_job_id=<?php echo $job['job_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include('footer.php'); ?>
