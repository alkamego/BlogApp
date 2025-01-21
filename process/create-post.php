<?php
require_once __DIR__ . '/../includes/db.php';

// Session control
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $userId = $_SESSION['user_id'];

    // Validation
    if (empty($title) || empty($content) || empty($category)) {
        setFlashMessage('danger', 'All fields are required.');
        header('Location: ../create-post.php');
        exit();
    }

    // if (strlen($title) < 5) {
    //     setFlashMessage('danger', 'Title must be at least 5 characters long.');
    //     header('Location: ../create-post.php');
    //     exit();
    // }

    if (strlen(strip_tags($content)) < 50) {
        setFlashMessage('danger', 'Content must be at least 50 characters long.');
        header('Location: ../create-post.php');
        exit();
    }

    try {
        // Start transaction
        $db->beginTransaction();

        // Handle image upload if exists
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            
            // Validate file size (2MB max)
            if ($file['size'] > 2 * 1024 * 1024) {
                throw new Exception('Image size must be less than 2MB');
            }

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $file['tmp_name']);
            finfo_close($fileInfo);

            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, PNG and GIF are allowed.');
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            
            // Create uploads directory if it doesn't exist
            $uploadDir = __DIR__ . '/../img/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Move uploaded file
            $imagePath = 'img/posts/' . $filename;
            if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                throw new Exception('Failed to upload image.');
            }
        }

        // Insert post
        $stmt = $db->prepare("
            INSERT INTO posts (user_id, category, title, content, img, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $userId,
            $category,
            $title,
            $content,
            $imagePath
        ]);

        // Commit transaction
        $db->commit();

        setFlashMessage('success', 'Post created successfully!');
        header('Location: ../my-posts.php');
        exit();

    } catch (Exception $e) {
        // Rollback transaction
        $db->rollBack();

        // Delete uploaded image if exists
        if (isset($imagePath) && file_exists(__DIR__ . '/../' . $imagePath)) {
            unlink(__DIR__ . '/../' . $imagePath);
        }

        setFlashMessage('danger', 'Error: ' . $e->getMessage());
        header('Location: ../create-post.php');
        exit();
    }
} else {
    header('Location: ../create-post.php');
    exit();
}

// Helper function to validate image file
function isValidImage($file) {
    $validTypes = [
        IMAGETYPE_JPEG,
        IMAGETYPE_PNG,
        IMAGETYPE_GIF
    ];

    $detectedType = exif_imagetype($file);
    return in_array($detectedType, $validTypes);
}