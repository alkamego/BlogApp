<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']) ? true : false;
    
    if (empty($username) || empty($password)) {
        setFlashMessage('danger', 'Please fill in all fields.');
        header("Location: ../login.php");
        exit();
    }

    try {
        // Find user by username or email
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Remember me functionality
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
                
                // Save token to database (you need to implement this)
                // saveRememberToken($user['id'], $token);
            }
            
            setFlashMessage('success', 'Successfully logged in!');
            header("Location: ../index.php");
            exit();
        } else {
            setFlashMessage('danger', 'Invalid username/email or password!');
            header("Location: ../login.php");
            exit();
        }
    } catch(PDOException $e) {
        setFlashMessage('danger', 'An error occurred: ' . $e->getMessage());
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}