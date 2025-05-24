<?php

// Function to start the session if not already started
if (!function_exists('start_session')) {
    function start_session() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}

// Function to check if the user is logged in
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}

// Function to get the user role
if (!function_exists('get_user_role')) {
    function get_user_role() {
        if (is_logged_in()) {
            return $_SESSION['role']; // 'laborer' or 'employer'
        }
        return null;
    }
}

// Function to check if the user is an employer
if (!function_exists('is_employer')) {
    function is_employer() {
        return get_user_role() === 'employer';
    }
}

// Function to check if the user is a laborer
if (!function_exists('is_laborer')) {
    function is_laborer() {
        return get_user_role() === 'laborer';
    }
}

// Function to redirect to a given page
if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url");
        exit();
    }
}

// Function to sanitize user input
if (!function_exists('sanitize_input')) {
    function sanitize_input($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

// Function to display flash messages (temporary session-based messages)
if (!function_exists('set_flash_message')) {
    function set_flash_message($message, $type = 'success') {
        $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    }
}

// Function to get and display flash messages
if (!function_exists('get_flash_message')) {
    function get_flash_message() {
        if (isset($_SESSION['flash_message'])) {
            $flash_message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return '<div class="alert alert-' . $flash_message['type'] . '">' . $flash_message['message'] . '</div>';
        }
        return '';
    }
}

// Function to hash a password securely
if (!function_exists('hash_password')) {
    function hash_password($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

// Function to verify a password
if (!function_exists('verify_password')) {
    function verify_password($password, $hashed_password) {
        return password_verify($password, $hashed_password);
    }
}

// Function to send a notification (e.g., email, SMS) - example placeholder function
if (!function_exists('send_notification')) {
    function send_notification($to, $subject, $message) {
        // For example, you can use PHP's mail() function or integrate a third-party service (like Mailgun or Twilio).
        // For now, it's a placeholder.
        mail($to, $subject, $message);
    }
}

// Function to get user details based on the user ID
if (!function_exists('get_user_by_id')) {
    function get_user_by_id($pdo, $user_id) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }
}

// Function to fetch all job postings
if (!function_exists('get_all_job_postings')) {
    function get_all_job_postings($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM job_postings ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

// Function to fetch job postings by employer
if (!function_exists('get_job_postings_by_employer')) {
    function get_job_postings_by_employer($pdo, $employer_id) {
        $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE employer_id = ?");
        $stmt->execute([$employer_id]);
        return $stmt->fetchAll();
    }
}

// Function to fetch job postings by skill (for matching)
if (!function_exists('get_job_postings_by_skill')) {
    function get_job_postings_by_skill($pdo, $skill) {
        $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE skills_required LIKE ?");
        $stmt->execute(['%' . $skill . '%']);
        return $stmt->fetchAll();
    }
}

// Function to apply for a job
if (!function_exists('apply_for_job')) {
    function apply_for_job($pdo, $laborer_id, $job_id) {
        $stmt = $pdo->prepare("INSERT INTO applications (laborer_id, job_id, application_status) VALUES (?, ?, 'pending')");
        return $stmt->execute([$laborer_id, $job_id]);
    }
}

// Function to check if the user has already applied for a job
if (!function_exists('has_applied_for_job')) {
    function has_applied_for_job($pdo, $laborer_id, $job_id) {
        $stmt = $pdo->prepare("SELECT * FROM applications WHERE laborer_id = ? AND job_id = ?");
        $stmt->execute([$laborer_id, $job_id]);
        return $stmt->fetch() !== false;
    }
}

// Function to leave a review for a job/worker
if (!function_exists('leave_review')) {
    function leave_review($pdo, $reviewer_id, $reviewee_id, $rating, $review_text) {
        $stmt = $pdo->prepare("INSERT INTO ratings_reviews (reviewer_id, reviewee_id, rating, review_text) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$reviewer_id, $reviewee_id, $rating, $review_text]);
    }
}
