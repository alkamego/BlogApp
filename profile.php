<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

// Oturum kontrolü
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Kullanıcı bilgilerini al
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!-- Profile Header -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-10">
                <h1 class="display-6 mb-0"><?= htmlspecialchars($user['username']) ?></h1>
                <p class="lead mb-0">Profil Bilgileriniz</p>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Sol Menü -->
            <div class="col-md-3 mb-4">
                <div class="list-group">
                    <a href="#profile-info" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                        <i class="fas fa-user me-2"></i>Profil Bilgileri
                    </a>
                     
                </div>
            </div>

            <!-- Sağ İçerik -->
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Profil Bilgileri -->
                    <div class="tab-pane fade show active" id="profile-info">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Profil Bilgilerini Güncelle</h5>
                            </div>
                            <div class="card-body">
                                <form action="process/update-profile.php" method="POST">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Kullanıcı Adı</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="username" 
                                               name="username" 
                                               value="<?= htmlspecialchars($user['username']) ?>"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-posta Adresi</label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="<?= htmlspecialchars($user['email']) ?>"
                                               required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Değişiklikleri Kaydet
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                     
                </div>
            </div>
        </div>
    </div>
</section>
 
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show active tab based on URL hash
    let hash = window.location.hash;
    if (hash) {
        let tab = document.querySelector(`a[href="${hash}"]`);
        if (tab) {
            tab.click();
        }
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>