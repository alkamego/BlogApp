<?php 
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Register</h3>
                </div>
                <div class="card-body p-4">
                 <?php displayFlashMessage(); ?>

                    <form action="process/register.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="fas fa-user"></i> Username
                            </label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-user-plus"></i> Register
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0">Already have an account? 
                                <a href="login.php" class="text-primary">Login</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>