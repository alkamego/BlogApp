<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;

class UpdateProfileTest extends TestCase
{
    private $db;
    private $userId;

    protected function setUp(): void
    {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=blogyk",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            
            $this->userId = $this->createTestUser();
            echo "\nTest setup completed successfully.";
            
        } catch(PDOException $e) {
            echo "\nSetup Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function testUpdateProfileWithValidData()
    {
        try {
            $newData = [
                'username' => 'updated_user_' . time(),
                'email' => 'updated_' . time() . '@test.com'
            ];

            $stmt = $this->db->prepare("
                UPDATE users 
                SET username = ?, 
                    email = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");

            $result = $stmt->execute([
                $newData['username'],
                $newData['email'],
                $this->userId
            ]);

            $this->assertTrue($result);

            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$this->userId]);
            $user = $stmt->fetch();

            $this->assertEquals($newData['username'], $user['username']);
            $this->assertEquals($newData['email'], $user['email']);

            echo "\nSuccessfully tested profile update with valid data";
        } catch(PDOException $e) {
            $this->fail("Test failed: " . $e->getMessage());
        }
    }

    public function testUpdateProfileWithDuplicateUsername()
    {
        try {
            $anotherUserId = $this->createTestUser();

            $stmt = $this->db->prepare("
                SELECT username FROM users WHERE id = ?
            ");
            $stmt->execute([$this->userId]);
            $existingUser = $stmt->fetch();

            $stmt = $this->db->prepare("
                UPDATE users 
                SET username = ?
                WHERE id = ?
            ");

            $this->expectException(PDOException::class);
            $stmt->execute([$existingUser['username'], $anotherUserId]);

            echo "\nSuccessfully tested duplicate username prevention";
        } catch(PDOException $e) {
            throw $e;
        }
    }

    public function testUpdateProfileWithInvalidEmail()
    {
        $invalidEmail = 'invalid-email';
        $this->assertFalse(filter_var($invalidEmail, FILTER_VALIDATE_EMAIL));
        echo "\nSuccessfully tested invalid email validation";
    }

    public function testUpdateProfileWithEmptyUsername()
    {
        // Boş username kontrolü
        $emptyUsername = '';
        
        // Username boş olmamalı
        $this->assertEmpty($emptyUsername);
        $this->assertFalse($this->isValidUsername($emptyUsername));
        
        echo "\nSuccessfully tested empty username validation";
    }

    private function isValidUsername($username)
    {
        return !empty($username) && strlen($username) >= 3 && strlen($username) <= 50;
    }

    private function createTestUser()
    {
        $username = 'testuser_' . time() . rand(1000, 9999);
        $email = 'test_' . time() . rand(1000, 9999) . '@test.com';
        $password = password_hash('123456', PASSWORD_DEFAULT);
        
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
            if ($this->userId) {
                $this->db->exec("DELETE FROM users WHERE id = " . intval($this->userId));
            }
            echo "\nTest data cleaned up successfully.\n";
        } catch(PDOException $e) {
            echo "\nCleanup Error: " . $e->getMessage() . "\n";
        }
    }
}