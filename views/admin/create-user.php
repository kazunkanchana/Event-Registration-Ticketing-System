<?php 
// Set page title
$pageTitle = 'Create User - Admin';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <h1 class="mb-3">Create New User</h1>
    
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/Event/admin/createUser">
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phone *</label>
                        <input type="tel" name="phone" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" required minlength="6">
                    </div>
                    
                    <p class="text-secondary mb-3">
                        <small>This user will be created with the <strong>User</strong> role.</small>
                    </p>
                    
                    <div class="grid grid-2">
                        <a href="/Event/admin/users" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
