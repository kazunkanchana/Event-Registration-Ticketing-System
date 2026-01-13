<?php
/**
 * Event Controller
 * ----------------
 * Handles showing events.
 */

/**
 * Show All Events Page
 * --------------------
 */
function event_index() {
    // 1. Check for Filters (Search/Category)
    $filters = [];
    
    if (isset($_GET['category'])) {
        $filters['category'] = $_GET['category'];
    }
    
    if (isset($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    
    // 2. Get Data
    $events = event_get_all($filters);
    $categories = category_get_all();
    
    // 3. Show Page
    $data = [];
    $data['events'] = $events;
    $data['categories'] = $categories;
    
    view('event/index', $data);
}

/**
 * Show Single Event Details
 * -------------------------
 */
function event_details($id) {
    // 1. Get Event Info
    $event = event_get_by_id($id);
    
    if (!$event) {
        redirect('/event');
    }
    
    // 2. user Registration Status
    $isRegistered = false;
    
    if (is_logged_in()) {
        $userId = $_SESSION['user_id'];
        $isRegistered = registration_check($userId, $id);
    }
    
    // 3. Prepare Data
    $data = [];
    $data['event'] = $event;
    $data['isRegistered'] = $isRegistered;
    
    // 4. Show Page
    view('event/details', $data);
}

?>
