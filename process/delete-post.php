<?php
require_once __DIR__ . '/../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response = [
        'status' => 'error',
        'message' => 'Unauthorized access'
    ];
    echo json_encode($response);
    exit();
}

// Check if request is POST and has JSON content
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(file_get_contents('php://input'))) {
    try {
        // Get and decode JSON data
        $jsonData = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($jsonData['post_id']) || !is_numeric($jsonData['post_id'])) {
            throw new Exception('Invalid post ID');
        }

        $postId = (int)$jsonData['post_id'];
        $userId = $_SESSION['user_id'];

        // Start transaction
        $db->beginTransaction();

        // First, get the post details to check ownership and get image path
        $stmt = $db->prepare("
            SELECT user_id, img 
            FROM posts 
            WHERE id = ?
        ");
        $stmt->execute([$postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if post exists and belongs to current user
        if (!$post) {
            throw new Exception('Post not found');
        }

        if ($post['user_id'] != $userId) {
            throw new Exception('You do not have permission to delete this post');
        }

        // Delete the post
        $stmt = $db->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$postId, $userId]);

        if (!$result) {
            throw new Exception('Failed to delete post');
        }

        // If post had an image, delete it
        if (!empty($post['img'])) {
            $imagePath = __DIR__ . '/../' . $post['img'];
            if (file_exists($imagePath)) {
                if (!unlink($imagePath)) {
                    // Log error but don't throw exception
                    error_log("Failed to delete image file: " . $imagePath);
                }
            }
        }

        // Commit transaction
        $db->commit();

        $response = [
            'status' => 'success',
            'message' => 'Post deleted successfully'
        ];

    } catch (Exception $e) {
        // Rollback transaction
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }

} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method or empty data'
    ];
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);