<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        setFlashMessage('danger', 'Please fill in all fields.');
        header("Location: ../register.php");
        exit();
    } elseif ($password !== $confirm_password) {
        setFlashMessage('danger', 'Passwords do not match.');
        header("Location: ../register.php");
        exit();
    } else {
        try {
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                setFlashMessage('danger', 'This username or email is already in use.');
                header("Location: ../register.php");
                exit();
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);
                
                setFlashMessage('success', 'Registration successful! You can now login.');
                header("Location: ../login.php");
                exit();
            }
        } catch(PDOException $e) {
            setFlashMessage('danger', 'An error occurred: ' . $e->getMessage());
            header("Location: ../register.php");
            exit();
        }
    }
} else {
    header("Location: ../register.php");
    exit();
}