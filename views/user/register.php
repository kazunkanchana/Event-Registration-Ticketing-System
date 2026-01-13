<?php 
// Set page title
$pageTitle = 'Register';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">
                <h1>Create Account</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="/Event/register">
                    <div class="grid grid-2">
                        <!-- First Name -->
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-input" required 
                                   placeholder="Enter first name">
                        </div>
                        
                        <!-- Last Name -->
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-input" required 
                                   placeholder="Enter last name">
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-input" required 
                               placeholder="Enter your email">
                    </div>
                    
                    <!-- Phone -->
                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" name="phone" class="form-input" required 
                               placeholder="e.g., 0771234567">
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" required 
                               placeholder="At least 6 characters">
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="confirm_password" class="form-input" required 
                               placeholder="Re-enter your password">
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>
                </form>
                
                <div class="mt-3 text-center">
                    <p>Already have an account? 
                        <a href="/Event/login">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
