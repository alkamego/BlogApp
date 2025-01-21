<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">Welcome to the Blog World</h1>
                <p class="lead mb-4">Discover the latest blog posts, share your experiences.</p>
                <a href="create-post.php" class="btn btn-light btn-lg">
                    <i class="fas fa-pencil-alt me-2"></i>Create Post
                </a>
            </div>
             
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="categories-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Popular Categories</h2>
        <div class="row g-4">
            <?php
            $stmt = $db->query("SELECT * FROM categories LIMIT 6");
            while ($category = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                <div class="col-md-4 col-lg-2">
                    <a href="category.php?id=<?= $category['id'] ?>" class="category-card text-center d-block text-decoration-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-folder fa-2x mb-3 text-primary"></i>
                                <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Latest Posts -->
<section class="latest-posts py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Latest Posts</h2>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Most Recent</a></li>
                    <li><a class="dropdown-item" href="#">Most Viewed</a></li>
                    <li><a class="dropdown-item" href="#">Most Commented</a></li>
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
                        <img src="<?= htmlspecialchars($post['img']) ?>" class="card-img-top" alt="Blog Image">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="badge bg-primary"><?= htmlspecialchars($post['category_name']) ?></span>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    <?= date('m/d/Y', strtotime($post['created_at'])) ?>
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
                                   <i class="fas fa-user me-1"></i>
                                    <small class="text-muted"><?= htmlspecialchars($post['username']) ?></small>
                                </div>
                                 
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<!-- Latest Posts -->



<?php require_once __DIR__ . '/includes/footer.php'; ?>