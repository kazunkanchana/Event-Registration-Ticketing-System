<?php
/**
 * Front Controller / Entry Point
 * ----------------------------
 * This file is the starting point for the entire application.
 * It handles all incoming requests (URLs) and decides which page to show.
 * This pattern is known as the "Front Controller" pattern.
 */

// 1. Start the Session
// We need sessions to remember logged-in users and show flash messages (success/error).
session_start();

// 2. Load Core & Config
require_once 'config/database.php';
require_once 'core/Database.php';
require_once 'core/helpers.php';

// 3. Load All Models (Function Libraries)
require_once 'models/User.php';
require_once 'models/Event.php';
require_once 'models/Registration.php';
require_once 'models/Category.php';

// 4. Load All Controllers (Function Libraries)
require_once 'controllers/HomeController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/EventController.php';
require_once 'controllers/RegistrationController.php';
require_once 'controllers/AdminController.php';

/*
 * 3. URL Processing
 * -----------------
 * We need to figure out what the user is asking for.
 * Example: if they go to /Event/login, we want to show the login page.
 */

// Get the full URL requested by the browser
$url = $_SERVER['REQUEST_URI'];

// Define the base path of our project
// This is because our project is in a sub-folder called '/Event'
$basePath = '/Event';

// If the URL starts with our base path, remove it so we just get the page name
if (strpos($url, $basePath) === 0) {
    $url = substr($url, strlen($basePath));
}

// Remove any query parameters (like ?search=marketing) to get the clean path
$url = strtok($url, '?');

// Remove trailing slash if present (e.g., /events/ becomes /events)
$url = rtrim($url, '/');

// If the URL is empty, it means we are on the home page
if (empty($url)) {
    $url = '/';
}

/*
 * 4. Define Routes
 * ----------------
 * A "Route" maps a URL to a specific function in a Controller.
 * Format: 'URL' => ['ControllerClassName', 'MethodName']
 */
// 5. Routing Logic (Simple URL parsing)

// Get URL (e.g., /event/details/1)
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$parts = explode('/', $url);

// Get Page (First part)
$page = isset($parts[0]) ? $parts[0] : 'home';

// Get Action (Second part)
$action = isset($parts[1]) ? $parts[1] : 'index';

// Get ID (Third part)
$id = isset($parts[2]) ? $parts[2] : null;

// Route Map
// connect URL parts to Function Names
// Format: "page_action" -> function_name

if ($page == 'home') {
    home_index();
} 
// Shortcuts
elseif ($page == 'events') {
    event_index();
}
elseif ($page == 'login') {
    user_login_page();
}
elseif ($page == 'register') {
    user_register_page();
}
elseif ($page == 'logout') {
    user_logout();
}
elseif ($page == 'profile') {
    user_profile();
}
elseif ($page == 'my-registrations') {
    user_registrations();
}
// Standard Routes
elseif ($page == 'admin') {
    if ($action == 'dashboard') admin_dashboard(); 
    elseif ($action == 'events') admin_events();
    elseif ($action == 'viewEvent') admin_viewEvent($id);
    elseif ($action == 'createEvent') admin_createEvent();
    elseif ($action == 'editEvent') admin_editEvent($id);
    elseif ($action == 'deleteEvent') admin_deleteEvent($id);
    elseif ($action == 'registrations') admin_registrations();
    elseif ($action == 'users') admin_users();
    elseif ($action == 'deleteUser') admin_deleteUser($id);
    elseif ($action == 'createUser') admin_createUser(); // New Route
    // Categories
    elseif ($action == 'categories') admin_categories();
    elseif ($action == 'deleteCategory') admin_deleteCategory($id);
    // Super Admin
    elseif ($action == 'createAdmin') admin_createAdmin();
    else header("Location: /Event/admin/dashboard");
}
elseif ($page == 'event') {
    if ($action == 'index') event_index();
    elseif ($action == 'details') event_details($id);
    else event_index();
}
elseif ($page == 'user') {
    if ($action == 'login') user_login_page();
    elseif ($action == 'register') user_register_page();
    elseif ($action == 'logout') user_logout();
    elseif ($action == 'profile') user_profile();
    elseif ($action == 'myRegistrations') user_registrations();
    else user_login_page();
}
elseif ($page == 'registration') {
    if ($action == 'register') registration_register($id);
    elseif ($action == 'cancel') registration_cancel_action($id);
    else header("Location: /Event/home");
}
else {
    // 404 Not Found
    require_once 'views/errors/404.php';
}
?>
