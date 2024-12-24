<?php 
require_once __DIR__ . '/includes/header.php';
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
                            <!-- Sample Post 1 -->
                            <tr>
                                <td>
                                    <a href="#" class="text-decoration-none text-dark">
                                        Getting Started with Web Development
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        Technology
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        15.01.2024 10:30
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        15.01.2024 10:30
                                    </small>
                                </td>
                                <td></td>
                            </tr>

                            <!-- Sample Post 2 -->
                            <tr>
                                <td>
                                    <a href="#" class="text-decoration-none text-dark">
                                        10 Tips for Better Programming
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        Programming
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        14.01.2024 15:45
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        14.01.2024 15:45
                                    </small>
                                </td>
                                <td></td>
                            </tr>

                            <!-- Sample Post 3 -->
                            <tr>
                                <td>
                                    <a href="#" class="text-decoration-none text-dark">
                                        The Future of Artificial Intelligence
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        AI
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        13.01.2024 09:15
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        13.01.2024 09:15
                                    </small>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
 

<?php require_once __DIR__ . '/includes/footer.php'; ?>

 