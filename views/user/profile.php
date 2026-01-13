<?php 
// Set page title
$pageTitle = 'My Profile';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <h1 class="mb-3">My Profile</h1>
    
    <div style="max-width: 700px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">
                <h2>Profile Information</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="/Event/profile">
                    <div class="grid grid-2">
                        <!-- First Name -->
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-input" required 
                                   value="<?php echo htmlspecialchars($user['first_name']); ?>">
                        </div>
                        
                        <!-- Last Name -->
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-input" required 
                                   value="<?php echo htmlspecialchars($user['last_name']); ?>">
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" required 
                               value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    
                    <!-- Phone -->
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-input" required 
                               value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    
                    <!-- Account Info (Read-only) -->
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-input" readonly 
                               value="<?php echo ucfirst($user['role']); ?>" 
                               style="background: var(--light-color);">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Member Since</label>
                        <input type="text" class="form-input" readonly 
                               value="<?php echo date('F j, Y', strtotime($user['created_at'])); ?>" 
                               style="background: var(--light-color);">
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
