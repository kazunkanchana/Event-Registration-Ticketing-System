<?php
/**
 * User Controller
 * ---------------
 * Handles login, registration, and profile.
 */

/**
 * Show Registration Page or Handle Init
 * -------------------------------------
 */
function user_register_page() {
    if (is_logged_in()) {
        redirect('/home');
    }
    
    // handle form submit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. Get Data
        $data = [];
        $data['first_name'] = sanitize($_POST['first_name']);
        $data['last_name'] = sanitize($_POST['last_name']);
        $data['email'] = sanitize($_POST['email']);
        $data['phone'] = sanitize($_POST['phone']);
        $data['password'] = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // 2. Validation
        if ($data['password'] !== $confirmPassword) {
            $_SESSION['error'] = "Passwords do not match";
            redirect('/user/register');
        }
        
        // Check email
        if (user_email_exists($data['email'])) {
            $_SESSION['error'] = "Email already registered";
            redirect('/user/register');
        }
        
        // 3. Create User
        $userId = user_register($data);
        
        if ($userId) {
            $_SESSION['success'] = "Registration successful! Please login.";
            redirect('/user/login');
        } else {
            $_SESSION['error'] = "Registration failed";
            redirect('/user/register');
        }
    } else {
        view('user/register');
    }
}

/**
 * Show Login Page or Handle Login
 * -------------------------------
 */
function user_login_page() {
    if (is_logged_in()) {
        redirect('/home');
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        
        $user = user_login($email, $password);
        
        if ($user) {
            // Set Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'];
            $_SESSION['role'] = $user['role'];
            
            $_SESSION['success'] = "Welcome back, " . $user['first_name'] . "!";
            
            if ($user['role'] === 'admin') {
                redirect('/admin/dashboard');
            } else {
                redirect('/home');
            }
        } else {
            $_SESSION['error'] = "Invalid email or password";
            redirect('/user/login');
        }
    } else {
        view('user/login');
    }
}

/**
 * Logout
 * ------
 */
function user_logout() {
    session_destroy();
    
    // Start new session to show message
    session_start();
    $_SESSION['success'] = "Logged out successfully";
    
    redirect('/home');
}

/**
 * User Profile
 * ------------
 */
function user_profile() {
    require_login();
    
    $userId = $_SESSION['user_id'];
    
    // Update handling
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [];
        $data['first_name'] = sanitize($_POST['first_name']);
        $data['last_name'] = sanitize($_POST['last_name']);
        $data['email'] = sanitize($_POST['email']);
        $data['phone'] = sanitize($_POST['phone']);
        
        user_update($userId, $data);
        $_SESSION['success'] = "Profile updated";
        // Refresh user name in session
        $_SESSION['user_name'] = $data['first_name'];
    }
    
    $user = user_get_by_id($userId);
    
    $data = [];
    $data['user'] = $user;
    
    view('user/profile', $data);
}

/**
 * My Registrations
 * ----------------
 */
function user_registrations() {
    require_login();
    
    $userId = $_SESSION['user_id'];
    
    $registrations = registration_get_by_user($userId);
    
    $data = [];
    $data['registrations'] = $registrations;
    
    view('user/my-registrations', $data);
}
