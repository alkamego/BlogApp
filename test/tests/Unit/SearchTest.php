<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;

class SearchTest extends TestCase
{
    private $db;
    private $userId;
    private $categoryId;
    private $postIds = [];

    protected function setUp(): void
    {
        try {
            // Database connection
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
            
            // Create test data
            $this->userId = $this->createTestUser();
            $this->categoryId = $this->createTestCategory();
            
            // Create multiple test posts
            $this->postIds[] = $this->createTestPost("PHP Programming Tips", "This is a post about PHP programming tips and tricks.");
            $this->postIds[] = $this->createTestPost("JavaScript Basics", "Learn JavaScript basics and fundamentals.");
            $this->postIds[] = $this->createTestPost("PHP Framework", "Exploring popular PHP frameworks like Laravel.");
            
            echo "\nTest setup completed successfully.";
            echo "\nCreated test user ID: " . $this->userId;
            echo "\nCreated test category ID: " . $this->categoryId;
            echo "\nCreated test posts IDs: " . implode(', ', $this->postIds) . "\n";
            
        } catch(PDOException $e) {
            echo "\nSetup Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function testSearchByTitle()
    {
        try {
            $search = "PHP";
            
            $sql = "
                SELECT p.*, u.username 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                WHERE (p.title LIKE :search OR p.content LIKE :search)
                ORDER BY p.created_at DESC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':search' => "%{$search}%"]);
            $results = $stmt->fetchAll();
            
            // Should find 2 posts with "PHP" in title or content
            $this->assertEquals(2, count($results), "Should find 2 posts containing 'PHP'");
            
            // Check if results contain expected titles
            $titles = array_column($results, 'title');
            $this->assertContains("PHP Programming Tips", $titles);
            $this->assertContains("PHP Framework", $titles);
            
            echo "\nSuccessfully found posts containing 'PHP'";
        } catch(PDOException $e) {
            $this->fail("Search test failed: " . $e->getMessage());
        }
    }

    public function testSearchByContent()
    {
        try {
            $search = "JavaScript";
            
            $sql = "
                SELECT p.*, u.username 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                WHERE (p.title LIKE :search OR p.content LIKE :search)
                ORDER BY p.created_at DESC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':search' => "%{$search}%"]);
            $results = $stmt->fetchAll();
            
            // Should find 1 post with "JavaScript" in title or content
            $this->assertEquals(1, count($results), "Should find 1 post containing 'JavaScript'");
            $this->assertEquals("JavaScript Basics", $results[0]['title']);
            
            echo "\nSuccessfully found post containing 'JavaScript'";
        } catch(PDOException $e) {
            $this->fail("Search test failed: " . $e->getMessage());
        }
    }

    public function testEmptySearchResults()
    {
        try {
            $search = "NonExistentTerm";
            
            $sql = "
                SELECT p.*, u.username 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                WHERE (p.title LIKE :search OR p.content LIKE :search)
                ORDER BY p.created_at DESC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':search' => "%{$search}%"]);
            $results = $stmt->fetchAll();
            
            // Should find no posts
            $this->assertEquals(0, count($results), "Should find no posts with non-existent term");
            
            echo "\nSuccessfully verified empty search results";
        } catch(PDOException $e) {
            $this->fail("Search test failed: " . $e->getMessage());
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

    private function createTestCategory()
    {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name) 
            VALUES (?)
        ");
        $stmt->execute(['Test Category ' . time()]);
        
        return $this->db->lastInsertId();
    }

    private function createTestPost($title, $content)
    {
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
            // Clean up test data
            foreach ($this->postIds as $postId) {
                $this->db->exec("DELETE FROM posts WHERE id = " . intval($postId));
            }
            
            if ($this->userId) {
                $this->db->exec("DELETE FROM users WHERE id = " . intval($this->userId));
            }
            
            if ($this->categoryId) {
                $this->db->exec("DELETE FROM categories WHERE id = " . intval($this->categoryId));
            }
            
            echo "\nTest data cleaned up successfully.\n";
        } catch(PDOException $e) {
            echo "\nCleanup Error: " . $e->getMessage() . "\n";
        }
    }
}