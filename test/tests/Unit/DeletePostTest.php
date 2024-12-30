<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;

class DeletePostTest extends TestCase
{
    private $db;
    private $userId;
    private $categoryId;
    private $postId;

    protected function setUp(): void
    {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=blogyk",
                "root",
                "",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Create test user
            $this->userId = $this->createTestUser();
            
            // Create test category
            $this->categoryId = $this->createTestCategory();
            
            // Create test post
            $this->postId = $this->createTestPost();
            
            echo "\nTest setup completed successfully.\n";
        } catch(PDOException $e) {
            $this->fail("Database connection failed: " . $e->getMessage());
        }
    }

    public function testDeletePostWithValidId()
    {
        // Delete the post
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$this->postId, $this->userId]);
        
        // Assert deletion was successful
        $this->assertTrue($result);
        
        // Verify post no longer exists
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$this->postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertFalse($post);
    }

    public function testDeletePostWithImage()
    {
        // Create post with image
        $imagePath = 'img/posts/test_image.jpg';
        $postId = $this->createTestPostWithImage($imagePath);
        
        // Delete the post
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$postId, $this->userId]);
        
        // Assert deletion was successful
        $this->assertTrue($result);
        
        // Verify post no longer exists
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertFalse($post);
    }

    public function testDeleteNonExistentPost()
    {
        $nonExistentId = 99999;
        
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$nonExistentId, $this->userId]);
        
        // Deletion should be "successful" but affect 0 rows
        $this->assertTrue($result);
        $this->assertEquals(0, $stmt->rowCount());
    }

    public function testDeletePostWithWrongUser()
    {
        // Create another user
        $anotherUserId = $this->createTestUser();
        
        // Try to delete post with wrong user
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$this->postId, $anotherUserId]);
        
        // Deletion should be "successful" but affect 0 rows
        $this->assertTrue($result);
        $this->assertEquals(0, $stmt->rowCount());
        
        // Verify post still exists
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$this->postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertNotFalse($post);
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

    private function createTestCategory()
    {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name) 
            VALUES (?)
        ");
        $stmt->execute(['Test Category ' . time()]);
        
        return $this->db->lastInsertId();
    }

    private function createTestPost()
    {
        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, category, title, content, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $this->userId,
            $this->categoryId,
            'Test Post Title',
            'Test post content that is long enough for testing purposes.'
        ]);
        
        return $this->db->lastInsertId();
    }

    private function createTestPostWithImage($imagePath)
    {
        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, category, title, content, img, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $this->userId,
            $this->categoryId,
            'Test Post With Image',
            'Test post content with image for testing purposes.',
            $imagePath
        ]);
        
        return $this->db->lastInsertId();
    }

    protected function tearDown(): void
    {
        try {
            // Clean up test data
            $this->db->exec("DELETE FROM posts WHERE user_id = {$this->userId}");
            $this->db->exec("DELETE FROM categories WHERE id = {$this->categoryId}");
            $this->db->exec("DELETE FROM users WHERE id = {$this->userId}");
            
            echo "\nTest data cleaned up successfully.\n";
        } catch(PDOException $e) {
            echo "\nFailed to clean up test data: " . $e->getMessage() . "\n";
        }
    }
}