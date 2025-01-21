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
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $userId = $_SESSION['user_id'];

        // Validation
        $errors = [];

        // Check current password
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($currentPassword, $user['password'])) {
            $errors[] = "Current password is incorrect.";
        }

        // New password validation
        if (strlen($newPassword) < 6) {
            $errors[] = "New password must be at least 6 characters long.";
        }

        // Password match check
        if ($newPassword !== $confirmPassword) {
            $errors[] = "New passwords do not match.";
        }

        // If there are errors, save to session and redirect
        if (!empty($errors)) {
            $_SESSION['password_errors'] = $errors;
            header('Location: ../profile.php#security');
            exit();
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("
            UPDATE users 
            SET password = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        $result = $stmt->execute([$hashedPassword, $userId]);

        if ($result) {
            $_SESSION['success_message'] = "Your password has been successfully updated.";
        } else {
            $_SESSION['password_errors'] = ["An error occurred while updating the password."];
        }

    } catch (PDOException $e) {
        $_SESSION['password_errors'] = ["Database error: " . $e->getMessage()];
    }
}

header('Location: ../profile.php#security');
exit();