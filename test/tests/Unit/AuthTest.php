<?php

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        // Test veritabanı bağlantısı
        $this->db = new PDO(
            "mysql:host=localhost;dbname=blogyk",
            "root",
            ""
        );
    }

    // Kayıt Testleri
    public function testEmptyRegistration()
    {
        $result = $this->register('', '', '', '');
        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Tüm alanları doldurunuz.', $result['message']);
    }

    public function testSuccessfulRegistration()
    {
        $username = 'testuser_' . time(); // Benzersiz kullanıcı adı
        $result = $this->register($username, 'test@test.com', '123456', '123456');
        $this->assertEquals('success', $result['status']);
    }

    public function testDuplicateRegistration()
    {
        $username = 'testuser_' . time();
        // İlk kayıt
        $this->register($username, 'test@test.com', '123456', '123456');
        // Aynı kullanıcı adıyla tekrar kayıt
        $result = $this->register($username, 'test2@test.com', '123456', '123456');
        $this->assertEquals('error', $result['status']);
    }

    // Giriş Testleri
    public function testEmptyLogin()
    {
        $result = $this->login('', '');
        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Tüm alanları doldurunuz.', $result['message']);
    }

    public function testSuccessfulLogin()
    {
        $username = 'testuser_' . time();
        // Önce kayıt ol
        $this->register($username, 'test@test.com', '123456', '123456');
        // Sonra giriş yap
        $result = $this->login($username, '123456');
        $this->assertEquals('success', $result['status']);
    }

    private function register($username, $email, $password, $confirm_password)
    {
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            return [
                'status' => 'error',
                'message' => 'Tüm alanları doldurunuz.'
            ];
        }

        try {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                return [
                    'status' => 'error',
                    'message' => 'Bu kullanıcı adı zaten kullanımda.'
                ];
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);

            return [
                'status' => 'success',
                'message' => 'Kayıt başarılı!'
            ];
        } catch(PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ];
        }
    }

    private function login($username, $password)
    {
        if (empty($username) || empty($password)) {
            return [
                'status' => 'error',
                'message' => 'Tüm alanları doldurunuz.'
            ];
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return [
                    'status' => 'success',
                    'message' => 'Giriş başarılı!'
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Kullanıcı adı veya şifre hatalı!'
            ];
        } catch(PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ];
        }
    }
}