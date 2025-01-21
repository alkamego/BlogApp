 

<!-- Main Footer -->
<footer class="main-footer pt-5 pb-2">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 mb-4">
                <div class="footer-about">
                    <h5 class="footer-heading">About Us</h5>
                    <p class="">Blog Site serves as a platform that brings you the most up-to-date and quality content.</p>
                    <div class="social-links footer mt-3">
                        <a href="#" class="social-link facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link linkedin">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link youtube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-heading">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fas fa-angle-right me-2"></i>Home</a></li>
                    <li><a href="#"><i class="fas fa-angle-right me-2"></i>About</a></li>
                    <li><a href="#"><i class="fas fa-angle-right me-2"></i>Categories</a></li>
                    <li><a href="#"><i class="fas fa-angle-right me-2"></i>Contact</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-heading">Categories</h5>
                <ul class="footer-links">
                   <?php
                   $stmt = $db->query("SELECT * FROM categories LIMIT 6");
                   while ($category = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <li><a href="category.php?id=<?= $category['id'] ?>"><i class="fas fa-angle-right me-2"></i><?= htmlspecialchars($category['name']) ?></a></li>  
                <?php endwhile; ?>

            </ul>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-4 mb-4">
            <h5 class="footer-heading">Contact Information</h5>
            <div class="footer-contact-info">
                <p class="d-flex align-items-center mb-3">
                    <i class="fas fa-map-marker-alt me-3"></i>
                    address
                </p>
                <p class="d-flex align-items-center mb-3">
                    <i class="fas fa-phone-alt me-3"></i>
                    +1 (555) 123-4567
                </p>
                <p class="d-flex align-items-center mb-3">
                    <i class="fas fa-envelope me-3"></i>
                    info@blogsite.com
                </p>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <hr class="mt-4 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-md-0 text-center text-md-start">
                    &copy; 2024 Blog Site. All rights reserved.
                </p>
            </div>
            <div class="col-md-6">
                <div class="footer-bottom-links text-center text-md-end">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Use</a>
                    <a href="#">GDPR</a>
                </div>
            </div>
        </div>
    </div>
</div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>