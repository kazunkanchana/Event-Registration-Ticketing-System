<?php 
// Set page title
$pageTitle = 'Create Event - Admin';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <h1 class="mb-3">Create New Event</h1>
    
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/Event/admin/createEvent" enctype="multipart/form-data">
                    <!-- Category -->
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Title -->
                    <div class="form-group">
                        <label class="form-label">Event Title *</label>
                        <input type="text" name="title" class="form-input" required 
                               placeholder="Enter event title">
                    </div>
                    
                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-textarea" required 
                                  placeholder="Enter event description"></textarea>
                    </div>
                    
                    <!-- Venue -->
                    <div class="form-group">
                        <label class="form-label">Venue *</label>
                        <input type="text" name="venue" class="form-input" required 
                               placeholder="Enter event venue">
                    </div>
                    
                    <div class="grid grid-2">
                        <!-- Event Date -->
                        <div class="form-group">
                            <label class="form-label">Event Date *</label>
                            <input type="date" name="event_date" class="form-input" required>
                        </div>
                        
                        <!-- Event Time -->
                        <div class="form-group">
                            <label class="form-label">Event Time *</label>
                            <input type="time" name="event_time" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-2">
                        <!-- Ticket Price -->
                        <div class="form-group">
                            <label class="form-label">Ticket Price (Rs.) *</label>
                            <input type="number" name="ticket_price" class="form-input" required 
                                   min="0" step="0.01" value="0" placeholder="0.00">
                        </div>
                        
                        <!-- Capacity -->
                        <div class="form-group">
                            <label class="form-label">Capacity *</label>
                            <input type="number" name="capacity" class="form-input" required 
                                   min="1" placeholder="Number of seats">
                        </div>
                    </div>
                    
                    <!-- Event Banner -->
                    <div class="form-group">
                        <label class="form-label">Event Banner Image</label>
                        <input type="file" 
                               name="event_image" 
                               id="eventImageInput" 
                               class="form-input" 
                               accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                               style="padding: 0.5rem;">
                        <small style="color: var(--text-secondary); display: block; margin-top: 0.25rem;">
                            Accepted formats: JPG, PNG, GIF, WEBP. Max size: 5MB.
                        </small>
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" style="display: none; margin-top: 1rem;">
                            <img id="previewImg" src="" alt="Preview" 
                                 style="max-width: 100%; max-height: 300px; border-radius: var(--radius); box-shadow: var(--shadow);">
                            <button type="button" id="removeImageBtn" 
                                    style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: var(--danger-color); color: white; border: none; border-radius: var(--radius-sm); cursor: pointer;">
                                Remove Image
                            </button>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="upcoming" selected>Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="grid grid-2">
                        <a href="/Event/admin/events" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
// Image Upload Preview and Validation
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('eventImageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeBtn = document.getElementById('removeImageBtn');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, PNG, GIF, or WEBP)');
                    imageInput.value = '';
                    return;
                }
                
                // Validate file size (5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    alert('File size must be less than 5MB');
                    imageInput.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Remove image
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                imageInput.value = '';
                imagePreview.style.display = 'none';
                previewImg.src = '';
            });
        }
    }
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
