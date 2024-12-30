<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;

class PostTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=blogyk",
                "root",
                "",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo "\nDatabase connection successful\n";
        } catch(PDOException $e) {
            $this->fail("Database connection failed: " . $e->getMessage());
        }
    }

    public function testListUserPosts()
{
    try {
        $userId = $this->createTestUser();
        echo "\nTest user created with ID: $userId\n";
        
        $categoryId = $this->createTestCategory();
        echo "\nTest category created with ID: $categoryId\n";
        
        $postId1 = $this->createTestPost($userId, $categoryId, "Test Post 1");
        $postId2 = $this->createTestPost($userId, $categoryId, "Test Post 2");
        
        echo "\nTest posts created with IDs: $postId1, $postId2\n";
        
        // Get user's posts with ID ordering
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name 
            FROM posts p 
            JOIN categories c ON p.category = c.id 
            WHERE p.user_id = ? 
            ORDER BY p.id DESC
        ");
        $stmt->execute([$userId]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\nFetched posts count: " . count($posts) . "\n";
        
        // Assertions
        $this->assertCount(2, $posts, "Should have exactly 2 posts");
        $this->assertEquals("Test Post 2", $posts[0]['title'], "First post should be Test Post 2");
        $this->assertEquals("Test Post 1", $posts[1]['title'], "Second post should be Test Post 1");
        $this->assertArrayHasKey('category_name', $posts[0], "Posts should have category_name");
        $this->assertArrayHasKey('created_at', $posts[0], "Posts should have created_at");
        $this->assertArrayHasKey('updated_at', $posts[0], "Posts should have updated_at");
        
    } catch(PDOException $e) {
        $this->fail("Test failed: " . $e->getMessage());
    }
}

    private function createTestUser()
    {
        try {
            $username = 'testuser_' . time();
            $email = 'test_' . time() . '@test.com';
            $password = password_hash('123456', PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("
                INSERT INTO users (username, email, password) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$username, $email, $password]);
            
            return $this->db->lastInsertId();
        } catch(PDOException $e) {
            $this->fail("Failed to create test user: " . $e->getMessage());
        }
    }

    private function createTestCategory()
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO categories (name) 
                VALUES (?)
            ");
            $stmt->execute(['Test Category ' . time()]);
            
            return $this->db->lastInsertId();
        } catch(PDOException $e) {
            $this->fail("Failed to create test category: " . $e->getMessage());
        }
    }

    private function createTestPost($userId, $categoryId, $title)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO posts (user_id, category, title, content) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $userId,
                $categoryId,
                $title,
                'Test content for ' . $title
            ]);
            
            return $this->db->lastInsertId();
        } catch(PDOException $e) {
            $this->fail("Failed to create test post: " . $e->getMessage());
        }
    }

    protected function tearDown(): void
    {
        try {
            // Clean up test data
            $this->db->exec("DELETE FROM posts WHERE title LIKE 'Test Post%'");
            $this->db->exec("DELETE FROM categories WHERE name LIKE 'Test Category%'");
            $this->db->exec("DELETE FROM users WHERE username LIKE 'testuser_%'");
            echo "\nTest data cleaned up successfully\n";
        } catch(PDOException $e) {
            echo "\nFailed to clean up test data: " . $e->getMessage() . "\n";
        }
    }
}