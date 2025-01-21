<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;

class CategoryFilterTest extends TestCase
{
    private $db;
    private $userId;
    private $categoryIds = [];
    private $postIds = [];

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
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
            
            // Test verilerini oluştur
            $this->userId = $this->createTestUser();
            $this->createTestCategories();
            $this->createTestPosts();
            
            echo "\nTest setup completed successfully.";
            
        } catch(PDOException $e) {
            echo "\nSetup Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function testFilterByValidCategory()
    {
        try {
            // PHP kategorisindeki postları getir
            $stmt = $this->db->prepare("
                SELECT p.*, u.username, c.name as category_name
                FROM posts p
                JOIN users u ON p.user_id = u.id
                JOIN categories c ON p.category = c.id
                WHERE p.category = ?
                ORDER BY p.created_at DESC
            ");
            
            $stmt->execute([$this->categoryIds['PHP']]);
            $posts = $stmt->fetchAll();
            
            // PHP kategorisinde 2 post olmalı
            $this->assertEquals(2, count($posts));
            
            // Postların kategorisi PHP olmalı
            foreach ($posts as $post) {
                $this->assertEquals('PHP', $post['category_name']);
            }
            
            echo "\nSuccessfully tested PHP category filter";
        } catch(PDOException $e) {
            $this->fail("Category filter test failed: " . $e->getMessage());
        }
    }

    public function testFilterByEmptyCategory()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, u.username, c.name as category_name
                FROM posts p
                JOIN users u ON p.user_id = u.id
                JOIN categories c ON p.category = c.id
                WHERE p.category = ?
                ORDER BY p.created_at DESC
            ");
            
            $stmt->execute([$this->categoryIds['Python']]);
            $posts = $stmt->fetchAll();
            
            // Python kategorisinde post olmamalı
            $this->assertEquals(0, count($posts));
            
            echo "\nSuccessfully tested empty category filter";
        } catch(PDOException $e) {
            $this->fail("Empty category test failed: " . $e->getMessage());
        }
    }

    public function testFilterByInvalidCategory()
    {
        try {
            $invalidCategoryId = 9999;
            
            $stmt = $this->db->prepare("
                SELECT p.*, u.username, c.name as category_name
                FROM posts p
                JOIN users u ON p.user_id = u.id
                JOIN categories c ON p.category = c.id
                WHERE p.category = ?
                ORDER BY p.created_at DESC
            ");
            
            $stmt->execute([$invalidCategoryId]);
            $posts = $stmt->fetchAll();
            
            // Geçersiz kategoride post olmamalı
            $this->assertEquals(0, count($posts));
            
            echo "\nSuccessfully tested invalid category filter";
        } catch(PDOException $e) {
            $this->fail("Invalid category test failed: " . $e->getMessage());
        }
    }

    private function createTestUser()
    {
        $username = 'testuser_' . time() . rand(1000, 9999);
        $email = 'test_' . time() . rand(1000, 9999) . '@test.com';
        $password = password_hash('123456', PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$username, $email, $password]);
        
        return $this->db->lastInsertId();
    }

    private function createTestCategories()
    {
        $categories = ['PHP', 'JavaScript', 'Python'];
        
        foreach ($categories as $category) {
            $stmt = $this->db->prepare("
                INSERT INTO categories (name) 
                VALUES (?)
            ");
            $stmt->execute([$category]);
            
            $this->categoryIds[$category] = $this->db->lastInsertId();
        }
    }

    private function createTestPosts()
    {
        // PHP kategorisi için 2 post
        $this->createPost(
            $this->categoryIds['PHP'],
            'PHP Development Tips',
            'Content about PHP development...'
        );
        
        $this->createPost(
            $this->categoryIds['PHP'],
            'Laravel Framework Guide',
            'Content about Laravel...'
        );

        // JavaScript kategorisi için 1 post
        $this->createPost(
            $this->categoryIds['JavaScript'],
            'JavaScript Basics',
            'Content about JavaScript...'
        );
    }

    private function createPost($categoryId, $title, $content)
    {
        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, category, title, content, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $this->userId,
            $categoryId,
            $title,
            $content
        ]);
        
        $this->postIds[] = $this->db->lastInsertId();
    }

    protected function tearDown(): void
    {
        try {
            // Test verilerini temizle
            foreach ($this->postIds as $postId) {
                $this->db->exec("DELETE FROM posts WHERE id = " . intval($postId));
            }
            
            foreach ($this->categoryIds as $categoryId) {
                $this->db->exec("DELETE FROM categories WHERE id = " . intval($categoryId));
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