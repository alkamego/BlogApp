<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">Blog Dünyasına Hoş Geldiniz</h1>
                <p class="lead mb-4">En güncel blog yazılarını keşfedin, deneyimlerinizi paylaşın.</p>
                <a href="create-post.php" class="btn btn-light btn-lg">
                    <i class="fas fa-pencil-alt me-2"></i>Yazı Oluştur
                </a>
            </div>
            <div class="col-md-6">
                <img src="assets/img/blog-hero.svg" alt="Blog Hero" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
 

<!-- Latest Posts -->
<section class="latest-posts py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Son Yazılar</h2>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2"></i>Filtrele
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">En Yeniler</a></li>
                    <li><a class="dropdown-item" href="#">En Çok Okunanlar</a></li>
                    <li><a class="dropdown-item" href="#">En Çok Yorumlananlar</a></li>
                </ul>
            </div>
        </div>

        <div class="row g-4">
            <?php
            $stmt = $db->query("
                SELECT p.*, u.username, c.name as category_name 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                JOIN categories c ON p.category = c.id 
                ORDER BY p.created_at DESC 
                LIMIT 6
            ");
            while ($post = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 blog-card">
                    <img src="assets/img/blog-placeholder.jpg" class="card-img-top" alt="Blog Image">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="badge bg-primary"><?= htmlspecialchars($post['category_name']) ?></span>
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
                                <img src="assets/img/avatar-placeholder.jpg" class="rounded-circle me-2" width="30">
                                <small class="text-muted"><?= htmlspecialchars($post['username']) ?></small>
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
            <?php endwhile; ?>
        </div>

        <div class="text-center mt-5">
            <a href="all-posts.php" class="btn btn-outline-primary">
                Tüm Yazıları Gör <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h3 class="mb-4">Yeni Yazılardan Haberdar Olun</h3>
                <p class="text-muted mb-4">En son blog yazılarımızdan ve güncellemelerimizden haberdar olmak için bültenimize abone olun.</p>
                <form class="newsletter-form">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="E-posta adresiniz...">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane me-2"></i>Abone Ol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>