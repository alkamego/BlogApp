<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
 
?>
 

<!-- Search Results Section -->
<section class="search-results py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Post 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 blog-card">
                    <img src="assets/img/blog-placeholder.jpg" class="card-img-top" alt="Blog Image">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                15.01.2024
                            </small>
                        </div>
                        <h5 class="card-title">
                            <a href="post.php?id=1" class="text-decoration-none text-dark">
                                Modern PHP Development Techniques
                            </a>
                        </h5>
                        <p class="card-text">
                            PHP has evolved significantly over the years. Modern PHP development encompasses a wide range of best practices, tools, and techniques...
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="author d-flex align-items-center">
                                <img src="assets/img/avatar-placeholder.jpg" 
                                     class="rounded-circle me-2" width="30">
                                <small class="text-muted">
                                    John Doe
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

            <!-- Post 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 blog-card">
                    <img src="assets/img/blog-placeholder.jpg" class="card-img-top" alt="Blog Image">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                14.01.2024
                            </small>
                        </div>
                        <h5 class="card-title">
                            <a href="post.php?id=2" class="text-decoration-none text-dark">
                                Getting Started with Laravel 10
                            </a>
                        </h5>
                        <p class="card-text">
                            Laravel is one of the most popular PHP frameworks, known for its elegant syntax and robust features. Version 10 brings exciting new capabilities...
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="author d-flex align-items-center">
                                <img src="assets/img/avatar-placeholder.jpg" 
                                     class="rounded-circle me-2" width="30">
                                <small class="text-muted">
                                    Jane Smith
                                </small>
                            </div>
                            <div class="stats">
                                <small class="text-muted">
                                    <i class="far fa-eye me-1"></i>234
                                    <i class="far fa-comment ms-2 me-1"></i>56
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 blog-card">
                    <img src="assets/img/blog-placeholder.jpg" class="card-img-top" alt="Blog Image">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                13.01.2024
                            </small>
                        </div>
                        <h5 class="card-title">
                            <a href="post.php?id=3" class="text-decoration-none text-dark">
                                PHP Security Best Practices
                            </a>
                        </h5>
                        <p class="card-text">
                            Security is paramount in web development. This post covers essential security practices every PHP developer should know...
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="author d-flex align-items-center">
                                <img src="assets/img/avatar-placeholder.jpg" 
                                     class="rounded-circle me-2" width="30">
                                <small class="text-muted">
                                    Alex Johnson
                                </small>
                            </div>
                            <div class="stats">
                                <small class="text-muted">
                                    <i class="far fa-eye me-1"></i>345
                                    <i class="far fa-comment ms-2 me-1"></i>67
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>