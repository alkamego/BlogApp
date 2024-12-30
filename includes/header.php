<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Site</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar py-2 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <span class="me-3"><i class="fas fa-envelope me-2"></i>info@blogsite.com</span>
                        <span><i class="fas fa-phone me-2"></i>+90 555 123 4567</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="social-links text-end">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <span class="text-primary fw-bold fs-3">BLOG</span>
                <span class="text-dark fw-light fs-3">SITE</span>
            </a>
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-list me-1"></i> Categories
                        </a>
                        <ul class="dropdown-menu">
                            <?php         
                            $categories=$db->prepare("select * from categories ");
                            $categories->execute();
                            foreach ($categories as $key => $val) {  ?>
                            <li><a class="dropdown-item" href="category.php?id=<?= $category['id'] ?>"><?= $category['name']; ?></a></li>
                        <?php } ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-newspaper me-1"></i> News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-info-circle me-1"></i> Lifestyle
                        </a>
                    </li>
                </ul>
                <!-- Auth Buttons -->
                <div class="nav-buttons d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="profile.php">
                                        <i class="fas fa-user-circle me-2"></i>Profilim
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="my-posts.php">
                                        <i class="fas fa-file-alt me-2"></i>Yazılarım
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="create-post.php">
                                        <i class="fas fa-plus me-2"></i>Yeni Yazı
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="process/logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <a href="register.php" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="search-bar py-3 bg-light border-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="search.php" method="get">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" placeholder="Search blog posts...">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>