<?php
  // Database connection
  include('db.php');



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password for security
    $role = $_POST['role'];
    $contact_info = $_POST['contact_info'];
    $location = $_POST['location'];

    // Check if the email already exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $error = "Email already exists. Please try another one.";
    } else {
        // Insert new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, contact_info, location) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role, $contact_info, $location]);

        // Redirect user to login page after successful registration
        header('Location: login.php');
        exit();
    }
}

?>

<?php include('header.php'); ?>
<div class="container">
    <h2>Register</h2>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="contact_info">Contact Info:</label>
            <input type="text" class="form-control" name="contact_info" id="contact_info" placeholder="Contact Info" required>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" class="form-control" name="location" id="location" placeholder="Location" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select class="form-control" name="role" id="role" required>
                <option value="laborer">Laborer</option>
                <option value="employer">Employer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>


<?php include('footer.php'); ?>
