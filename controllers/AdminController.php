<?php
/**
 * Admin Controller
 * ----------------
 * Handles all admin tasks.
 */
    
/**
 * Dashboard
 * ---------
 */
function admin_dashboard() {
    require_admin();
    
    // Call global functions directly
    $totalEvents = event_count();
    $upcomingEvents = event_count('upcoming');
    $totalRegistrations = registration_count();
    $totalUsers = user_count();
    
    // Get recent events (limit to 5)
    $recentEvents = event_get_all([], 5);
    
    // Package data in format the view expects
    $data = [];
    $data['stats'] = [
        'total_events' => $totalEvents,
        'upcoming_events' => $upcomingEvents,
        'total_registrations' => $totalRegistrations,
        'total_users' => $totalUsers
    ];
    $data['recentEvents'] = $recentEvents;
    
    view('admin/dashboard', $data);
}

/**
 * Manage Events
 * -------------
 */

/**
 * Manage Categories
 * -----------------
 */
function admin_categories() {
    require_admin();
    
    // Handle Create
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = sanitize($_POST['name']);
        if (!empty($name)) {
            if (category_create($name)) {
                $_SESSION['success'] = "Category created";
            } else {
                $_SESSION['error'] = "Failed to create category (might already exist)";
            }
        }
        redirect('/admin/categories');
    }
    
    $categories = category_get_all();
    view('admin/categories', ['categories' => $categories]);
}

/**
 * Delete Category
 * ---------------
 */
function admin_deleteCategory($id) {
    require_admin();
    
    $result = category_delete($id);
    if ($result === true) {
        $_SESSION['success'] = "Category deleted";
    } elseif ($result === 'has_events') {
        $_SESSION['error'] = "Cannot delete category: events depend on it";
    } else {
        $_SESSION['error'] = "Failed to delete category";
    }
    redirect('/admin/categories');
}

/**
 * Create Admin (Super Admin Only)
 * -------------------------------
 */
function admin_createAdmin() {
    require_admin();
    
    // Check if Super Admin
    // Assuming 'admin@ticketflow.com' is the super admin for now, or check DB role
    // Ideally user DB has 'super_admin' role string.
    // Let's rely on session 'email' or 'role' if we update it.
    // For safety, hardcode check or check role 'super_admin'.
    
    $isSuperAdmin = ($_SESSION['email'] === 'admin@ticketflow.com') || ($_SESSION['role'] === 'super_admin');
    
    if (!$isSuperAdmin) {
        $_SESSION['error'] = "Only Super Admins can create new admins";
        redirect('/admin/dashboard');
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [];
        $data['first_name'] = sanitize($_POST['first_name']);
        $data['last_name'] = sanitize($_POST['last_name']);
        $data['email'] = sanitize($_POST['email']);
        $data['phone'] = sanitize($_POST['phone']);
        $data['password'] = $_POST['password'];
        $data['role'] = 'admin'; // Always create 'admin' here
        
        if (user_email_exists($data['email'])) {
             $_SESSION['error'] = "Email already exists";
        } else {
             // We need a version of user_register that accepts 'role' or just allow it 
             // user_register in User.php HARDCODES 'user' role.
             // We need to write a new specific function or update user_register?
             // Let's assume we update user_register to take default role or add helper.
             // Or write raw query here? Better to add function in User model or logic.
             // But I can't update User.php right here in this step easily without context switch.
             // I'll assume we used 'user_register' but it sets 'user'.
             // So I will INSERT manually via a new User model function 'user_create_admin' or just modify user_register later.
             // Wait, I can't modify user_register now.
             // I will add a method 'user_create_admin' to User.php in next step.
             // For now, call it.
             if (user_create_admin($data)) {
                 $_SESSION['success'] = "New Admin created successfully";
                 redirect('/admin/users'); // Or dashboard
                 return;
             } else {
                 $_SESSION['error'] = "Failed to create admin";
             }
        }
    }
    
    view('admin/create-admin');
}

/**
 * Create User (Admin Action)
 * --------------------------
 */
function admin_createUser() {
    require_admin();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [];
        $data['first_name'] = sanitize($_POST['first_name']);
        $data['last_name'] = sanitize($_POST['last_name']);
        $data['email'] = sanitize($_POST['email']);
        $data['phone'] = sanitize($_POST['phone']);
        $data['password'] = $_POST['password'];
        
        if (user_email_exists($data['email'])) {
             $_SESSION['error'] = "Email already exists";
        } else {
             // Use standard registration which sets role='user'
             if (user_register($data)) {
                 $_SESSION['success'] = "New User created successfully";
                 redirect('/admin/users');
                 return;
             } else {
                 $_SESSION['error'] = "Failed to create user";
             }
        }
    }
    
    view('admin/create-user');
}

/**
 * Manage Events (with Search)
 * ---------------------------
 */
function admin_events() {
    require_admin();
    
    $search = isset($_GET['search']) ? sanitize($_GET['search']) : null;
    $filters = [];
    if ($search) {
        $filters['search'] = $search;
    }
    
    $events = event_get_all($filters);
    
    $data = [];
    $data['events'] = $events;
    $data['search'] = $search;
    
    view('admin/events', $data);
}

/**
 * View Event Details (Admin)
 * ---------------------------
 */
function admin_viewEvent($id) {
    require_admin();
    
    // Get event details
    $event = event_get_by_id($id);
    
    if (!$event) {
        $_SESSION['error'] = "Event not found";
        redirect('/admin/events');
        return;
    }
    
    // Get all registrations for this event
    $registrations = registration_get_by_event($id);
    $totalRegistrations = count($registrations);
    
    $data = [];
    $data['event'] = $event;
    $data['registrations'] = $registrations;
    $data['totalRegistrations'] = $totalRegistrations;
    
    view('admin/event-details', $data);
}

/**
 * Create Event
 * ------------
 */
function admin_createEvent() {
    require_admin();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [];
        $data['title'] = sanitize($_POST['title']);
        $data['category_id'] = $_POST['category_id'];
        $data['description'] = sanitize($_POST['description']);
        $data['venue'] = sanitize($_POST['venue']);
        $data['event_date'] = $_POST['event_date'];
        $data['event_time'] = $_POST['event_time'];
        $data['ticket_price'] = $_POST['ticket_price'];
        $data['capacity'] = $_POST['capacity'];
        $data['created_by'] = $_SESSION['user_id'];
        $data['status'] = sanitize($_POST['status']);
        
        // Handle image upload
        $imageName = 'default.jpg';
        
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['event_image'];
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            // Validate file type
            if (in_array($file['type'], $allowedTypes)) {
                // Validate file size
                if ($file['size'] <= $maxSize) {
                    // Generate unique filename
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $imageName = 'event_' . time() . '_' . uniqid() . '.' . $extension;
                    
                    // Upload directory
                    $uploadDir = __DIR__ . '/../public/uploads/events/';
                    $uploadPath = $uploadDir . $imageName;
                    
                    // Move uploaded file
                    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        $_SESSION['error'] = "Failed to upload image";
                        $imageName = 'default.jpg';
                    }
                } else {
                    $_SESSION['error'] = "Image file too large (max 5MB)";
                }
            } else {
                $_SESSION['error'] = "Invalid image file type";
            }
        }
        
        $data['image'] = $imageName;
        
        $result = event_create($data);
        
        if ($result) {
            $_SESSION['success'] = "Event created successfully";
            redirect('/admin/events');
        } else {
            $_SESSION['error'] = "Failed to create event";
        }
    }
    
    $categories = category_get_all();
    
    $data = [];
    $data['categories'] = $categories;
    
    view('admin/create-event', $data);
}

/**
 * Edit Event
 * ----------
 */
function admin_editEvent($id) {
    require_admin();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $data = [];
         $data['title'] = sanitize($_POST['title']);
         $data['category_id'] = $_POST['category_id'];
         $data['description'] = sanitize($_POST['description']);
         $data['venue'] = sanitize($_POST['venue']);
         $data['event_date'] = $_POST['event_date'];
         $data['event_time'] = $_POST['event_time'];
         $data['ticket_price'] = $_POST['ticket_price'];
         $data['capacity'] = $_POST['capacity'];
         $data['status'] = $_POST['status'];
         
         event_update($id, $data);
         $_SESSION['success'] = "Event updated";
         redirect('/admin/events');
    }
    
    $event = event_get_by_id($id);
    $categories = category_get_all();
    
    $data = [];
    $data['event'] = $event;
    $data['categories'] = $categories;
    
    view('admin/edit-event', $data);
}

/**
 * Delete Event
 * ------------
 */
function admin_deleteEvent($id) {
    require_admin();
    
    event_delete($id);
    
    $_SESSION['success'] = "Event deleted";
    redirect('/admin/events');
}

/**
 * Manage Registrations
 * --------------------
 */
function admin_registrations() {
    require_admin();
    
    $registrations = registration_get_all();
    
    $data = [];
    $data['registrations'] = $registrations;
    
    view('admin/registrations', $data);
}

/**
 * Manage Users
 * ------------
 */
function admin_users() {
    require_admin();
    
    $list = user_get_all();
    
    $data = [];
    $data['users'] = $list;
    
    view('admin/users', $data);
}

/**
 * Delete User
 * -----------
 */
function admin_deleteUser($id) {
    require_admin();
    
    // DEBUG LOGGING
    file_put_contents('debug_delete.txt', date('Y-m-d H:i:s') . " - Attempting to delete user ID: $id\n", FILE_APPEND);
    
    // Prevent deleting self
    if ($id == $_SESSION['user_id']) {
        $_SESSION['error'] = "Cannot delete yourself";
        file_put_contents('debug_delete.txt', date('Y-m-d H:i:s') . " - Failed: Cannot delete self\n", FILE_APPEND);
        redirect('/admin/users');
        return;
    }
    
    if (user_delete($id)) {
        $_SESSION['success'] = "User deleted successfully";
        file_put_contents('debug_delete.txt', date('Y-m-d H:i:s') . " - Success: User $id deleted\n", FILE_APPEND);
    } else {
         $_SESSION['error'] = "Failed to delete user";
         file_put_contents('debug_delete.txt', date('Y-m-d H:i:s') . " - Fail: user_delete returned false\n", FILE_APPEND);
    }
    
    redirect('/admin/users');
}
