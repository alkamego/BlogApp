<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

// Post ID kontrolü
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Post detaylarını al
$stmt = $db->prepare("
    SELECT p.*, u.username, c.name as category_name
    FROM posts p
    JOIN users u ON p.user_id = u.id
    JOIN categories c ON p.category = c.id
    WHERE p.id = ?
");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
// Post bulunamazsa 404
if (!$post) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit();
}
?>
<!-- Post Header -->
<header class="py-5 bg-light border-bottom mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="text-muted mb-2">
                    <a href="category.php?id=<?= $post['category'] ?>" class="text-decoration-none">
                        <i class="fas fa-folder me-1"></i><?= htmlspecialchars($post['category_name']) ?>
                    </a>
                    <span class="mx-2">•</span>
                    <i class="far fa-clock me-1"></i><?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
                    
                </div>
                <h1 class="fw-bolder mb-1"><?= htmlspecialchars($post['title']) ?></h1>
                <div class="d-flex align-items-center mt-3">
                   <i class="fas fa-user me-1"></i>
                    <span class="text-muted">
                        <?= htmlspecialchars($post['username']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Post Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Post Content -->
            <article class="mb-5">
                <?php if ($post['img']): ?>
                    <img src="<?= htmlspecialchars($post['img']) ?>" 
                         class="img-fluid rounded mb-4" 
                         alt="<?= htmlspecialchars($post['title']) ?>">
                <?php endif; ?>

                <div class="content">
                    <?= $post['content'] ?>
                </div>
            </article>
 
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related Posts -->
            <div class="card mb-4">
                <div class="card-header">Benzer Yazılar</div>
                <div class="card-body">
                    <?php
                    $stmt = $db->prepare("
                        SELECT p.*, u.username
                        FROM posts p
                        JOIN users u ON p.user_id = u.id
                        WHERE p.category = ? AND p.id != ?
                        ORDER BY p.created_at DESC
                        LIMIT 5
                    ");
                    $stmt->execute([$post['category'], $postId]);
                    $relatedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php if (empty($relatedPosts)): ?>
                        <div class="text-muted">Benzer yazı bulunamadı.</div>
                    <?php else: ?>
                        <?php foreach ($relatedPosts as $relatedPost): ?>
                            <div class="mb-3">
                                <h6 class="mb-1">
                                    <a href="post.php?id=<?= $relatedPost['id'] ?>" 
                                       class="text-decoration-none">
                                        <?= htmlspecialchars($relatedPost['title']) ?>
                                    </a>
                                </h6>
                                <div class="small text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    <?= date('d.m.Y', strtotime($relatedPost['created_at'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>