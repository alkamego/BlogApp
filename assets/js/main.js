// assets/js/main.js
document.addEventListener('DOMContentLoaded', function() {
    // Alert'leri 5 saniye sonra otomatik kapat
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});