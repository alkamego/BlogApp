<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;

class PostDetailTest extends TestCase
{
    private $db;
    private $userId;
    private $categoryId;
    private $postId;

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
            
            // Test verilerini oluştur
            $this->userId = $this->createTestUser();
            $this->categoryId = $this->createTestCategory();
            $this->postId = $this->createTestPost();
            
            echo "\nTest setup completed successfully.";
            
        } catch(PDOException $e) {
            echo "\nSetup Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function testFetchValidPost()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, u.username, c.name as category_name
                FROM posts p
                JOIN users u ON p.user_id = u.id
                JOIN categories c ON p.category = c.id
                WHERE p.id = ?
            ");
            
            $stmt->execute([$this->postId]);
            $post = $stmt->fetch();

            // Post bulunmalı
            $this->assertNotFalse($post);
            
            // Gerekli alanlar var mı?
            $this->assertArrayHasKey('title', $post);
            $this->assertArrayHasKey('content', $post);
            $this->assertArrayHasKey('username', $post);
            $this->assertArrayHasKey('category_name', $post);
            $this->assertArrayHasKey('created_at', $post);

            echo "\nSuccessfully tested fetching valid post";
        } catch(PDOException $e) {
            $this->fail("Test failed: " . $e->getMessage());
        }
    }

    public function testFetchInvalidPost()
    {
        try {
            $invalidPostId = 99999;
            
            $stmt = $this->db->prepare("
                SELECT p.*, u.username, c.name as category_name
                FROM posts p
                JOIN users u ON p.user_id = u.id
                JOIN categories c ON p.category = c.id
                WHERE p.id = ?
            ");
            
            $stmt->execute([$invalidPostId]);
            $post = $stmt->fetch();

            // Post bulunmamalı
            $this->assertFalse($post);
            
            echo "\nSuccessfully tested fetching invalid post";
        } catch(PDOException $e) {
            $this->fail("Test failed: " . $e->getMessage());
        }
    }

    public function testFetchRelatedPosts()
    {
        try {
            // İkinci bir post oluştur
            $this->createTestPost();

            $stmt = $this->db->prepare("
                SELECT p.*, u.username
                FROM posts p
                JOIN users u ON p.user_id = u.id
                WHERE p.category = ? AND p.id != ?
                ORDER BY p.created_at DESC
                LIMIT 5
            ");
            
            $stmt->execute([$this->categoryId, $this->postId]);
            $relatedPosts = $stmt->fetchAll();

            // En az bir benzer post olmalı
            $this->assertGreaterThan(0, count($relatedPosts));
            
            // İlk related post'un gerekli alanları var mı?
            $this->assertArrayHasKey('title', $relatedPosts[0]);
            $this->assertArrayHasKey('username', $relatedPosts[0]);
            $this->assertArrayHasKey('created_at', $relatedPosts[0]);

            echo "\nSuccessfully tested fetching related posts";
        } catch(PDOException $e) {
            $this->fail("Test failed: " . $e->getMessage());
        }
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

    private function createTestCategory()
    {
        $categoryName = 'TestCategory_' . time() . rand(1000, 9999);
        
        $stmt = $this->db->prepare("
            INSERT INTO categories (name) 
            VALUES (?)
        ");
        
        $stmt->execute([$categoryName]);
        
        return $this->db->lastInsertId();
    }

    private function createTestPost()
    {
        $title = 'Test Post ' . time() . rand(1000, 9999);
        $content = 'Test content ' . time() . rand(1000, 9999);
        
        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, category, title, content, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $this->userId,
            $this->categoryId,
            $title,
            $content
        ]);
        
        return $this->db->lastInsertId();
    }

    protected function tearDown(): void
    {
        try {
            // Test verilerini temizle
            if ($this->postId) {
                $this->db->exec("DELETE FROM posts WHERE id = " . intval($this->postId));
            }
            
            if ($this->categoryId) {
                $this->db->exec("DELETE FROM categories WHERE id = " . intval($this->categoryId));
            }
            
            if ($this->userId) {
                $this->db->exec("DELETE FROM users WHERE id = " . intval($this->userId));
            }
            
            echo "\nTest data cleaned up successfully.\n";
        } catch(PDOException $e) {
            echo "\nCleanup Error: " . $e->getMessage() . "\n";
        }
    }
}