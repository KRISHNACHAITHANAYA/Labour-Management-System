<?php
// Include necessary files
include('db.php');
include('functions.php');

// Check if the user is logged in and has the employer role
if (!is_logged_in() || $_SESSION['role'] !== 'employer') {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Fetch all job postings for the logged-in employer
$stmt = $pdo->prepare("SELECT * FROM job_postings WHERE employer_id = ?");
$stmt->execute([$user_id]);
$jobs = $stmt->fetchAll();
?>

<?php include('header.php'); ?>

<!-- Display flash message -->
<?php echo get_flash_message(); ?>

<h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
<p>Here you can manage your posted jobs.</p>

<!-- Button to post a new job -->
<a href="post_job.php" class="btn btn-success mb-3">Post New Job</a>

<!-- Table to display all the employer's job postings -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Job Title</th>
            <th>Location</th>
            <th>Salary</th>
            <th>Skills Required</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($jobs) > 0) { ?>
            <?php foreach ($jobs as $job) { ?>
                <tr>
                    <td><?php echo $job['job_title']; ?></td>
                    <td><?php echo $job['location']; ?></td>
                    <td><?php echo '$' . $job['salary']; ?></td>
                    <td><?php echo $job['skills_required']; ?></td>
                    <td>
                        <a href="edit_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="delete_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5" class="text-center">You have not posted any jobs yet.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include('footer.php'); ?>
