<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

// Arama parametrelerini al
$search = isset($_GET['query']) ? trim($_GET['query']) : '';

// Arama sorgusunu hazırla
$params = [];
$sql = "
    SELECT p.*, u.username
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    WHERE 1=1
";

if (!empty($search)) {
    $sql .= " AND (p.title LIKE :search OR p.content LIKE :search)";
    $params[':search'] = "%{$search}%";
}

 

$sql .= " ORDER BY p.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Search Header Section -->
<section class="search-header py-5 bg-primary text-white">
    <div class="container">
        <h1 class="display-6 mb-4">Arama Sonuçları</h1>
        <div class="row">
            <div class="col-md-8">
                <form action="search.php" method="GET" class="d-flex gap-2">
                    <input type="text" name="query" class="form-control form-control-lg" 
                           placeholder="Blog yazılarında ara..." 
                           value="<?= htmlspecialchars($search) ?>">
                     
                    <button type="submit" class="btn btn-light btn-lg">
                        <i class="fas fa-search me-2"></i>Ara
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Search Results Section -->
<section class="search-results py-5">
    <div class="container">
        <?php if (!empty($search)): ?>
            <p class="text-muted mb-4">
                <?= count($posts) ?> sonuç bulundu.
                <?php if (!empty($search)): ?>
                    "<?= htmlspecialchars($search) ?>" için arama sonuçları
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Arama kriterlerinize uygun sonuç bulunamadı. Lütfen farklı anahtar kelimeler deneyiniz.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 blog-card">
                            <img src="assets/img/blog-placeholder.jpg" class="card-img-top" alt="Blog Image">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                     
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                                    </small>
                                </div>
                                <h5 class="card-title">
                                    <a href="post.php?id=<?= $post['id'] ?>" class="text-decoration-none text-dark">
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
                                            <i class="far fa-eye me-1"></i>123
                                            <i class="far fa-comment ms-2 me-1"></i>45
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>