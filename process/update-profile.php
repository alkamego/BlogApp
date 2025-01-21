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
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $userId = $_SESSION['user_id'];

        // Validasyon
        $errors = [];

        // Kullanıcı adı kontrolü
        if (empty($username)) {
            $errors[] = "Kullanıcı adı boş olamaz.";
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = "Kullanıcı adı 3-50 karakter arasında olmalıdır.";
        }

        // Email kontrolü
        if (empty($email)) {
            $errors[] = "E-posta adresi boş olamaz.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Geçerli bir e-posta adresi giriniz.";
        }

        // Bio kontrolü
        if (strlen($bio) > 500) {
            $errors[] = "Hakkımda yazısı 500 karakterden uzun olamaz.";
        }

        // Kullanıcı adı ve email benzersiz olmalı
        $stmt = $db->prepare("
            SELECT id FROM users 
            WHERE (username = ? OR email = ?) 
            AND id != ?
        ");
        $stmt->execute([$username, $email, $userId]);
        
        if ($stmt->rowCount() > 0) {
            $errors[] = "Bu kullanıcı adı veya e-posta adresi zaten kullanılıyor.";
        }

        // Hata varsa session'a kaydet ve geri yönlendir
        if (!empty($errors)) {
            $_SESSION['profile_errors'] = $errors;
            $_SESSION['profile_form_data'] = $_POST;
            header('Location: ../profile.php');
            exit();
        }

        // Güncelleme işlemi
        $stmt = $db->prepare("
            UPDATE users 
            SET username = ?, 
                email = ?
            WHERE id = ?
        ");

        $result = $stmt->execute([$username, $email, $userId]);

        if ($result) {
            $_SESSION['success_message'] = "Profil bilgileriniz başarıyla güncellendi.";
        } else {
            $_SESSION['profile_errors'] = ["Güncelleme sırasında bir hata oluştu."];
        }

    } catch (PDOException $e) {
        $_SESSION['profile_errors'] = ["Veritabanı hatası: " . $e->getMessage()];
    }
}

header('Location: ../profile.php');
exit();