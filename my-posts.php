<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

// Session control
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user's blog posts
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name 
    FROM posts p 
    JOIN categories c ON p.category = c.id 
    WHERE p.user_id = ? 
    ORDER BY p.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Page Header -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">My Posts</h1>
                <p class="lead mb-4">Manage your blog posts, edit them and create new ones.</p>
                <a href="create-post.php" class="btn btn-light btn-lg">
                    <i class="fas fa-plus me-2"></i>Create New Post
                </a>
            </div>
            <div class="col-md-6 text-end">
                <i class="fas fa-pencil-alt fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Posts List -->
<section class="py-5">
    <div class="container">
        <?php displayFlashMessage(); ?>

        <?php if (empty($posts)): ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-pencil-alt fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">You don't have any blog posts yet.</h3>
                    <p class="text-muted mb-4">Start creating your first blog post now!</p>
                    <a href="create-post.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Post
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 40%">Title</th>
                                    <th>Category</th>
                                    <th>Created At</th>
                                    <th>Last Update</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td>
                                            <a href="post.php?id=<?= $post['id'] ?>" class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($post['title']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?= htmlspecialchars($post['category_name']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                <?= date('d.m.Y H:i', strtotime($post['updated_at'])) ?>
                                            </small>
                                        </td>
                                        <td class="text-end"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
 

<?php require_once __DIR__ . '/includes/footer.php'; ?>

 