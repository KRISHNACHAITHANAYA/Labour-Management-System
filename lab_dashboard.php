<?php
// Include necessary files
include('db.php');
include('functions.php');

// Check if the user is logged in and has the laborer role
if (!is_logged_in() || $_SESSION['role'] !== 'laborer') {
    redirect('login.php');
}

// Get the laborer's ID
$laborer_id = $_SESSION['user_id'];

// Fetch all job postings from the database
$stmt = $pdo->prepare("SELECT * FROM job_postings WHERE status = 'active'");
$stmt->execute();
$jobs = $stmt->fetchAll();
?>

<?php include('header.php'); ?>

<h2>Welcome to your Dashboard</h2>

<h3>Available Jobs</h3>
<?php if ($jobs): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                    <td><?php echo htmlspecialchars($job['location']); ?></td>
                    <td>$<?php echo htmlspecialchars($job['salary']); ?></td>
                    <td>
                        <a href="apply_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-primary">Apply</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No jobs available at the moment. Please check back later.</p>
<?php endif; ?>

<?php include('footer.php'); ?>
