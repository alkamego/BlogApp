<?php
require_once __DIR__ . '/../includes/db.php';

// Oturum kontrolü
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Form verilerini al
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $userId = $_SESSION['user_id'];

        // Validasyon
        $errors = [];

        // Mevcut şifreyi kontrol et
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($currentPassword, $user['password'])) {
            $errors[] = "Mevcut şifre yanlış.";
        }

        // Yeni şifre kontrolü
        if (strlen($newPassword) < 6) {
            $errors[] = "Yeni şifre en az 6 karakter olmalıdır.";
        }

        // Şifre eşleşme kontrolü
        if ($newPassword !== $confirmPassword) {
            $errors[] = "Yeni şifreler eşleşmiyor.";
        }

        // Hata varsa session'a kaydet ve geri yönlendir
        if (!empty($errors)) {
            $_SESSION['password_errors'] = $errors;
            header('Location: ../profile.php#security');
            exit();
        }

        // Şifreyi güncelle
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("
            UPDATE users 
            SET password = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        $result = $stmt->execute([$hashedPassword, $userId]);

        if ($result) {
            $_SESSION['success_message'] = "Şifreniz başarıyla güncellendi.";
        } else {
            $_SESSION['password_errors'] = ["Şifre güncellenirken bir hata oluştu."];
        }

    } catch (PDOException $e) {
        $_SESSION['password_errors'] = ["Veritabanı hatası: " . $e->getMessage()];
    }
}

header('Location: ../profile.php#security');
exit();