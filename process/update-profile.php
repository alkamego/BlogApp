<?php
require_once __DIR__ . '/../includes/db.php';

// Session check
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $userId = $_SESSION['user_id'];

        // Validation
        $errors = [];

        // Username validation
        if (empty($username)) {
            $errors[] = "Username cannot be empty.";
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = "Username must be between 3-50 characters.";
        }

        // Email validation
        if (empty($email)) {
            $errors[] = "Email address cannot be empty.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }

        // Bio validation
        if (strlen($bio) > 500) {
            $errors[] = "Bio cannot be longer than 500 characters.";
        }

        // Check username and email uniqueness
        $stmt = $db->prepare("
            SELECT id FROM users 
            WHERE (username = ? OR email = ?) 
            AND id != ?
        ");
        $stmt->execute([$username, $email, $userId]);
        
        if ($stmt->rowCount() > 0) {
            $errors[] = "This username or email address is already in use.";
        }

        // If there are errors, save to session and redirect
        if (!empty($errors)) {
            $_SESSION['profile_errors'] = $errors;
            $_SESSION['profile_form_data'] = $_POST;
            header('Location: ../profile.php');
            exit();
        }

        // Update process
        $stmt = $db->prepare("
            UPDATE users 
            SET username = ?, 
                email = ?
            WHERE id = ?
        ");

        $result = $stmt->execute([$username, $email, $userId]);

        if ($result) {
            $_SESSION['success_message'] = "Your profile has been successfully updated.";
        } else {
            $_SESSION['profile_errors'] = ["An error occurred during the update."];
        }

    } catch (PDOException $e) {
        $_SESSION['profile_errors'] = ["Database error: " . $e->getMessage()];
    }
}

header('Location: ../profile.php');
exit();