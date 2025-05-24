<?php
// Include necessary files
include('db.php');
include('functions.php');

// Check if the user is logged in and has the laborer role
if (!is_logged_in() || $_SESSION['role'] !== 'laborer') {
    redirect('login.php');
}

// Fetch search and filter data from POST request
$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';
$location = isset($_POST['location']) ? $_POST['location'] : '';
$salary = isset($_POST['salary']) ? $_POST['salary'] : '';

// Build the SQL query based on the filters
$sql = "SELECT * FROM job_postings WHERE status = 'active'";

if ($search_query || $location || $salary) {
    $sql .= " AND (job_title LIKE :search_query OR location LIKE :location)";
    if ($salary) {
        $sql .= " AND salary <= :salary";
    }
}

// Prepare and execute the query
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
$stmt->bindValue(':location', "%$location%", PDO::PARAM_STR);
if ($salary) {
    $stmt->bindValue(':salary', $salary, PDO::PARAM_INT);
}
$stmt->execute();
$jobs = $stmt->fetchAll();
?>

<?php include('header.php'); ?>

<h2>Search for Jobs</h2>

<!-- Job Search Form -->
<form method="POST" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="search_query" class="form-control" placeholder="Search Job Title" value="<?php echo htmlspecialchars($search_query); ?>" />
        </div>
        <div class="col-md-4">
            <input type="text" name="location" class="form-control" placeholder="Location" value="<?php echo htmlspecialchars($location); ?>" />
        </div>
        <div class="col-md-2">
            <input type="number" name="salary" class="form-control" placeholder="Max Salary" value="<?php echo htmlspecialchars($salary); ?>" />
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-block">Search</button>
        </div>
    </div>
</form>

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
    <p>No jobs found based on your search criteria.</p>
<?php endif; ?>

<?php include('footer.php'); ?>
