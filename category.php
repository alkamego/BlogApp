<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

// Kategori ID'sini al
$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kategori bilgisini al
$stmt = $db->prepare("
    SELECT * FROM categories 
    WHERE id = ?
");
$stmt->execute([$categoryId]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Kategori bulunamazsa ana sayfaya yönlendir
if (!$category) {
    header('Location: index.php');
    exit();
}

// Kategoriye ait postları al
$stmt = $db->prepare("
    SELECT p.*, u.username, c.name as category_name
    FROM posts p
    JOIN users u ON p.user_id = u.id
    JOIN categories c ON p.category = c.id
    WHERE p.category = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([$categoryId]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Category Header -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <h1 class="display-6 mb-3"><?= htmlspecialchars($category['name']) ?></h1>
        <p class="lead"><?= count($posts) ?> article found</p>
    </div>
</section>

<!-- Posts Section -->
<section class="search-results py-5">
    <div class="container">
        <div class="row g-4">
            <?php if (empty($posts)): ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        There are no articles in this category yet.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 blog-card">
                            <?php if ($post['img']): ?>
                                <img src="<?= htmlspecialchars($post['img']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($post['title']) ?>">
                            <?php else: ?>
                                <img src="assets/img/blog-placeholder.jpg" 
                                     class="card-img-top" 
                                     alt="Blog Image">
                            <?php endif; ?>

                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                                    </small>
                                </div>
                                <h5 class="card-title">
                                    <a href="post.php?id=<?= $post['id'] ?>" 
                                       class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h5>
                                <p class="card-text">
                                    <?= substr(strip_tags($post['content']), 0, 150) ?>...
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="author d-flex align-items-center">
                                        <img src="assets/img/avatar-placeholder.jpg" 
                                             class="rounded-circle me-2" width="30">
                                        <small class="text-muted">
                                            <?= htmlspecialchars($post['username']) ?>
                                        </small>
                                    </div>
                                    <div class="stats">
                                        <small class="text-muted">
                                            <i class="far fa-eye me-1"></i><?= $post['views'] ?? 0 ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>