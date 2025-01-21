<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

// Session control
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get categories for dropdown
$stmt = $db->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Add CKEditor from CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

<!-- Custom styles -->
<style>
.ck-editor__editable {
    min-height: 400px;
    max-height: 600px;
}
.ck-editor__editable:focus {
    border-color: #86b7fe !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
}
.image-preview {
    max-width: 100%;
    max-height: 300px;
    object-fit: contain;
}
.preview-container {
    display: none;
    position: relative;
}
.remove-image {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    border: none;
    border-radius: 50%;
    padding: 5px 10px;
    cursor: pointer;
}
</style>

<!-- Page Header -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">Create New Post</h1>
                <p class="lead mb-4">Share your thoughts and experiences with the world.</p>
            </div>
            <div class="col-md-6 text-end">
                <i class="fas fa-pencil-alt fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Create Post Form -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <?php displayFlashMessage(); ?>
                        
                        <form action="process/create-post.php" method="POST" id="createPostForm" enctype="multipart/form-data">
                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading me-2"></i>Post Title
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="title" 
                                       name="title" 
                                       required 
                                       minlength="5" 
                                       maxlength="255"
                                       placeholder="Enter your post title">
                                <div class="form-text">
                                    Make it clear and attention-grabbing
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="mb-4">
                                <label for="category" class="form-label">
                                    <i class="fas fa-folder me-2"></i>Category
                                </label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Featured Image -->
                            <div class="mb-4">
                                <label for="image" class="form-label">
                                    <i class="fas fa-image me-2"></i>Featured Image
                                </label>
                                <input type="file" 
                                       class="form-control" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                <div class="form-text">
                                    Recommended size: 1200x630 pixels, Max size: 2MB
                                </div>
                                <!-- Image Preview -->
                                <div class="preview-container mt-3 text-center">
                                    <img src="" alt="Preview" class="image-preview rounded shadow-sm">
                                    <button type="button" class="remove-image">×</button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="mb-4">
                                <label for="editor" class="form-label">
                                    <i class="fas fa-paragraph me-2"></i>Post Content
                                </label>
                                <textarea id="editor" name="content" style="display: none;"></textarea>
                                <div class="form-text">
                                    Minimum 50 characters
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="my-posts.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to My Posts
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Publish Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
let editor;

ClassicEditor
    .create(document.querySelector('#editor'), {
        toolbar: [
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            '|',
            'outdent',
            'indent',
            '|',
            'blockQuote',
            'insertTable',
            'undo',
            'redo'
        ]
    })
    .then(newEditor => {
        editor = newEditor;
    })
    .catch(error => {
        console.error(error);
    });

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createPostForm');
    const imageInput = document.getElementById('image');
    const previewContainer = document.querySelector('.preview-container');
    const previewImage = document.querySelector('.image-preview');
    const removeButton = document.querySelector('.remove-image');
    
    // Image Preview
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    // Remove Image
    removeButton.addEventListener('click', function() {
        imageInput.value = '';
        previewContainer.style.display = 'none';
        previewImage.src = '';
    });
    
    // Form Validation
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const content = editor.getData();
        const category = document.getElementById('category').value;
        const image = imageInput.files[0];
        
        if (title.length < 5) {
            e.preventDefault();
            alert('Title must be at least 5 characters long');
            return;
        }
        
        const contentText = content.replace(/<[^>]*>/g, '').trim();
        if (contentText.length < 50) {
            e.preventDefault();
            alert('Content must be at least 50 characters long');
            return;
        }
        
        if (!category) {
            e.preventDefault();
            alert('Please select a category');
            return;
        }
        
        if (image && image.size > 2 * 1024 * 1024) { // 2MB
            e.preventDefault();
            alert('Image size must be less than 2MB');
            return;
        }
    });
});
</script>