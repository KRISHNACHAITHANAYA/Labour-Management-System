<?php
session_start();
include('db.php');

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); // Redirect if user is already logged in
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Prepare SQL query to fetch the user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Check if user exists and verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<?php include('header.php'); ?>

<!-- Full Page Background -->
<div style="height: 100vh; display: flex; flex-direction: column; background: url(' https://as2.ftcdn.net/v2/jpg/09/09/10/05/1000_F_909100583_37eobQVEqkci9JnlJIcj6se4Y6oBFU6g.jpg') no-repeat center center/cover; font-family: Arial, sans-serif;">
    <!-- Login Form Section -->
    <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
        <div style="width: 100%; max-width: 400px; padding: 20px; background: transparent; border-radius: 10px; box-shadow: 0 4px 40px rgba(0, 0, 0, 0.2);">
            <h2 style="text-align: center; color: #333; margin-bottom: 20px;">Login</h2>

            <?php if ($error) { ?>
                <div style="margin-bottom: 15px; padding: 10px; color: #721c24; background-color: transparent; border: 1px solid #f5c6cb; border-radius: 5px;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <form method="POST" action="login.php">
                <div style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email address</label>
                    <input type="email" name="email" id="email" placeholder="Enter email" 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" 
                           value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" 
                           required>
                </div>
                <button type="submit" 
                        style="width: 100%; padding: 10px; background-color: #2575fc; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 16px; transition: background 0.3s;">
                    Login
                </button>
            </form>
            <p style="text-align: center; margin-top: 15px; font-size: 14px; color: black;">Don't have an account? 
                <a href="register.php" style="color: #2575fc; text-decoration: none; font-weight: bold;">Register here</a>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <?php include('footer.php');?>
</div>