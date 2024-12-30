<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']) ? true : false;
    
    if (empty($username) || empty($password)) {
        setFlashMessage('danger', 'Tüm alanları doldurunuz.');
        header("Location: ../login.php");
        exit();
    }

    try {
        // Kullanıcı adı veya email ile kullanıcıyı bul
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Giriş başarılı
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Beni hatırla işlemi
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 gün
                
                // Token'ı veritabanına kaydet (bu kısmı implement etmeniz gerekiyor)
                // saveRememberToken($user['id'], $token);
            }
            
            setFlashMessage('success', 'Başarıyla giriş yaptınız!');
            header("Location: ../index.php");
            exit();
        } else {
            setFlashMessage('danger', 'Kullanıcı adı/email veya şifre hatalı!');
            header("Location: ../login.php");
            exit();
        }
    } catch(PDOException $e) {
        setFlashMessage('danger', 'Bir hata oluştu: ' . $e->getMessage());
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}