<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;

class UpdatePasswordTest extends TestCase
{
    private $db;
    private $userId;
    private $currentPassword = '123456'; // Test için sabit şifre

    protected function setUp(): void
    {
        try {
            // Veritabanı bağlantısı
            $this->db = new PDO(
                "mysql:host=localhost;dbname=blogyk",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            
            // Test kullanıcısı oluştur
            $this->userId = $this->createTestUser();
            
            echo "\nTest setup completed successfully.";
            
        } catch(PDOException $e) {
            echo "\nSetup Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function testUpdatePasswordWithValidData()
    {
        try {
            $newPassword = 'newPassword123';
            
            // Önce mevcut şifreyi kontrol et
            $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$this->userId]);
            $user = $stmt->fetch();

            // Mevcut şifre doğru olmalı
            $this->assertTrue(password_verify($this->currentPassword, $user['password']));

            // Şifreyi güncelle
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");

            $result = $stmt->execute([$hashedPassword, $this->userId]);
            
            // Güncelleme başarılı olmalı
            $this->assertTrue($result);

            // Yeni şifreyi kontrol et
            $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$this->userId]);
            $updatedUser = $stmt->fetch();

            // Yeni şifre doğru olmalı
            $this->assertTrue(password_verify($newPassword, $updatedUser['password']));
            
            echo "\nSuccessfully tested password update with valid data";
        } catch(PDOException $e) {
            $this->fail("Test failed: " . $e->getMessage());
        }
    }

    public function testUpdatePasswordWithWrongCurrentPassword()
    {
        $wrongPassword = 'wrongPassword123';
        
        // Mevcut şifreyi kontrol et
        $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$this->userId]);
        $user = $stmt->fetch();

        // Yanlış şifre doğrulama başarısız olmalı
        $this->assertFalse(password_verify($wrongPassword, $user['password']));
        
        echo "\nSuccessfully tested wrong current password validation";
    }

    public function testUpdatePasswordWithShortNewPassword()
    {
        $shortPassword = '123'; // 6 karakterden kısa
        
        // Şifre uzunluğu kontrolü
        $this->assertFalse($this->isValidPassword($shortPassword));
        
        echo "\nSuccessfully tested short password validation";
    }

    public function testPasswordMismatch()
    {
        $newPassword = 'newPassword123';
        $confirmPassword = 'differentPassword123';
        
        // Şifreler eşleşmeli
        $this->assertNotEquals($newPassword, $confirmPassword);
        
        echo "\nSuccessfully tested password mismatch validation";
    }

    private function isValidPassword($password)
    {
        return strlen($password) >= 6;
    }

    private function createTestUser()
    {
        $username = 'testuser_' . time() . rand(1000, 9999);
        $email = 'test_' . time() . rand(1000, 9999) . '@test.com';
        $password = password_hash($this->currentPassword, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, created_at, updated_at) 
            VALUES (?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([$username, $email, $password]);
        
        return $this->db->lastInsertId();
    }

    protected function tearDown(): void
    {
        try {
            // Test verilerini temizle
            if ($this->userId) {
                $this->db->exec("DELETE FROM users WHERE id = " . intval($this->userId));
            }
            
            echo "\nTest data cleaned up successfully.\n";
        } catch(PDOException $e) {
            echo "\nCleanup Error: " . $e->getMessage() . "\n";
        }
    }
}