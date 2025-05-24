<?php

// Include necessary files
include('db.php');
include('functions.php');

start_session(); // Now you can call it safely




// If the user is logged in, redirect them to the appropriate dashboard
if (is_logged_in()) {
    $role = $_SESSION['role'];
    if ($role == 'laborer') {
        header('Location: lab_dashboard.php');
        exit;
    } elseif ($role == 'employer') {
        header('Location: emp_dashboard.php');
        exit;
    }
}

// Job search form submission handling
$search_query = '';
$location = '';
$jobs = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
    $location = $_POST['location'];

    // Query the database for jobs based on search input
    $filters = [];
    if (!empty($search_query)) {
        $filters[] = "job_title LIKE '%" . htmlspecialchars($search_query) . "%'";
    }
    if (!empty($location)) {
        $filters[] = "location LIKE '%" . htmlspecialchars($location) . "%'";
    }

    $sql = "SELECT * FROM job_postings";
    if (!empty($filters)) {
        $sql .= " WHERE " . implode(' AND ', $filters);
    }

    // Execute the query and fetch the results
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $jobs = $stmt->fetchAll();
}

?>

<?php include('header.php'); ?>

<!-- Main Page Content -->
<div class="container mt-4">
    <h2>Welcome to the Labour Job Portal</h2>
    <p>Find skilled labor or job opportunities in your area.</p>

    <!-- Job Search Form -->
    <form method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search_query" class="form-control" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search job title" />
            </div>
            <div class="col-md-4">
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($location); ?>" placeholder="Location" />
            </div>
            <div class="col-md-2">
                <button type="submit" name="search" class="btn btn-primary btn-block">Search</button>
            </div>
        </div>
    </form>

    <h3>Job Listings</h3>
    <?php if (empty($jobs)): ?>
        <p>No jobs found matching your search criteria.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($jobs as $job): ?>
                <li class="list-group-item">
                    <h5><?php echo htmlspecialchars($job['job_title']); ?></h5>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?> | <strong>Salary:</strong> $<?php echo htmlspecialchars($job['salary']); ?></p>
                    <a href="apply_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-info btn-sm">Apply Now</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Call to action for users who are not logged in -->
    <div class="mt-4">
        <p>Don't have an account? <a href="register.php">Register here</a> | <a href="login.php">Login</a></p>
    </div>
</div>

<?php include('footer.php'); ?>
