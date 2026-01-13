<?php
/**
 * PHP SYNTAX HELPER
 * -----------------
 * ->  (Arrow):  "Go inside". Example: $this->model('Event')
 * =>  (Double Arrow): "Points to". Used to set values in a list.
 */
/**
 * Home Controller
 * ---------------
 * Handles the main landing page.
 */

function home_index() {
    // 1. Get Data
    $events = event_get_upcoming(6);
    // DEBUG: Uncomment to see raw data
    // echo "<pre>"; print_r($events); echo "</pre>";
    $categories = category_get_all();
    
    // 2. Prepare Data
    $data = [];
    $data['upcomingEvents'] = $events;
    $data['categories'] = $categories;
    
    // 3. Show View
    view('home/index', $data);
}

?>
