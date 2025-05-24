<?php
// Include necessary files
include('db.php');
include('functions.php');

// Check if the user is logged in and has the employer role
if (!is_logged_in() || $_SESSION['role'] !== 'employer') {
    redirect('login.php');
}

// Get the job ID from the GET request
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Get the job details from the database
    $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE job_id = ? AND employer_id = ?");
    $stmt->execute([$job_id, $_SESSION['user_id']]);
    $job = $stmt->fetch();

    // If the job doesn't exist or the employer doesn't have permission to view it
    if (!$job) {
        set_flash_message('You do not have permission to view applications for this job.', 'danger');
        header('Location: dashboard.php');
        exit();
    }

    // Get the applications for this job
    $stmt = $pdo->prepare("SELECT a.*, u.name AS laborer_name, u.email AS laborer_email FROM applications a 
                           JOIN users u ON a.laborer_id = u.user_id 
                           WHERE a.job_id = ?");
    $stmt->execute([$job_id]);
    $applications = $stmt->fetchAll();
} else {
    set_flash_message('Invalid job ID.', 'danger');
    header('Location: dashboard.php');
    exit();
}
?>

<?php include('header.php'); ?>

<h2>Applications for Job: <?php echo htmlspecialchars($job['job_title']); ?></h2>

<?php if (count($applications) > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Skills</th>
                <th>Application Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $application): ?>
                <tr>
                    <td><?php echo htmlspecialchars($application['laborer_name']); ?></td>
                    <td><?php echo htmlspecialchars($application['laborer_email']); ?></td>
                    <td><?php echo htmlspecialchars($application['skills']); ?></td>
                    <td><?php echo htmlspecialchars($application['application_status']); ?></td>
                    <td>
                        <!-- Action buttons: Accept/Reject Application -->
                        <?php if ($application['application_status'] == 'pending'): ?>
                            <a href="accept_application.php?application_id=<?php echo $application['application_id']; ?>" class="btn btn-success btn-sm">Accept</a>
                            <a href="reject_application.php?application_id=<?php echo $application['application_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                        <?php else: ?>
                            <span class="badge badge-secondary">Completed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No applications yet for this job.</p>
<?php endif; ?>

<?php include('footer.php'); ?>
