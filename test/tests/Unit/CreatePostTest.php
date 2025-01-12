<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;

class CreatePostTest extends TestCase
{
    private $db;
    private $userId;
    private $categoryId;

    protected function setUp(): void
    {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=blogyk",
                "root",
                "",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $this->userId = $this->createTestUser();
            $this->categoryId = $this->createTestCategory();
            
            echo "\nTest setup completed successfully.\n";
        } catch(PDOException $e) {
            $this->fail("Database connection failed: " . $e->getMessage());
        }
    }

    public function testCreatePostWithValidData()
    {
        $postData = [
            'title' => 'Test Post Title',
            'content' => 'This is a test post content that is long enough to pass the minimum character validation. It contains more than 50 characters.',
            'category' => $this->categoryId,
            'user_id' => $this->userId
        ];

        // Validate data
        $this->assertTrue($this->validatePostData($postData));

        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, category, title, content, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");

        $result = $stmt->execute([
            $postData['user_id'],
            $postData['category'],
            $postData['title'],
            $postData['content']
        ]);

        $this->assertTrue($result);
        $postId = $this->db->lastInsertId();
        
        // Verify the post
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertNotFalse($post);
        $this->assertEquals($postData['title'], $post['title']);
        $this->assertEquals($postData['content'], $post['content']);
    }

    public function testCreatePostWithImage()
    {
        $imagePath = 'img/posts/test_image.jpg';
        
        $postData = [
            'title' => 'Test Post With Image',
            'content' => 'This is a test post content with an image. It contains more than 50 characters for validation.',
            'category' => $this->categoryId,
            'user_id' => $this->userId,
            'img' => $imagePath
        ];

        // Validate data
        $this->assertTrue($this->validatePostData($postData));

        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, category, title, content, img, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $result = $stmt->execute([
            $postData['user_id'],
            $postData['category'],
            $postData['title'],
            $postData['content'],
            $postData['img']
        ]);

        $this->assertTrue($result);
        $postId = $this->db->lastInsertId();
        
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertNotFalse($post);
        $this->assertEquals($postData['img'], $post['img']);
    }

    public function testCreatePostWithInvalidTitle()
    {
        $postData = [
            'title' => '', // Empty title
            'content' => 'Valid content that is long enough.',
            'category' => $this->categoryId,
            'user_id' => $this->userId
        ];

        // This should return false for invalid data
        $this->assertFalse($this->validatePostData($postData));

        // Test with short title
        $postData['title'] = 'Hi'; // Too short
        $this->assertFalse($this->validatePostData($postData));
    }

    private function validatePostData($data)
    {
        // Title validation
        if (empty($data['title']) || strlen($data['title']) < 5) {
            return false;
        }

        // Content validation
        if (empty($data['content']) || strlen(strip_tags($data['content'])) < 50) {
            return false;
        }

        // Category validation
        if (empty($data['category'])) {
            return false;
        }

        // User validation
        if (empty($data['user_id'])) {
            return false;
        }

        return true;
    }

    private function createTestUser()
    {
        $username = 'testuser_' . time();
        $email = 'test_' . time() . '@test.com';
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

    protected function tearDown(): void
    {
        try {
            $this->db->exec("DELETE FROM posts WHERE user_id = {$this->userId}");
            $this->db->exec("DELETE FROM categories WHERE id = {$this->categoryId}");
            $this->db->exec("DELETE FROM users WHERE id = {$this->userId}");
            
            echo "\nTest data cleaned up successfully.\n";
        } catch(PDOException $e) {
            echo "\nFailed to clean up test data: " . $e->getMessage() . "\n";
        }
    }
}