<?php
/**
 * Registration Controller
 * -----------------------
 * Handles registering for events.
 */

/**
 * Register for an event
 * ---------------------
 */
function registration_register($eventId) {
     // 1. Check Login
    if (!is_logged_in()) {
        redirect('/user/login');
    }
    
    $user = $_SESSION['user_id'];
    
    // 2. Validation
    // Is user already registered?
    if (registration_check($user, $eventId)) {
        $_SESSION['error'] = "You are already registered for this event.";
        redirect('/event/details/' . $eventId);
    }
    
    // Are seats available?
    if (!event_has_seats($eventId)) {
        $_SESSION['error'] = "Event is fully booked.";
        redirect('/event/details/' . $eventId);
    }
    
    // 3. Register
    $result = registration_create($user, $eventId);
    
    if ($result) {
        $_SESSION['success'] = "Registration successful! Your ticket is ready.";
        redirect('/my-registrations');
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        redirect('/event/details/' . $eventId);
    }
}

/**
 * Cancel registration
 * -------------------
 */
function registration_cancel_action($registrationId) {
    // 1. Check Login
    if (!is_logged_in()) {
        redirect('/user/login');
    }
    
    // 2. Cancel
    $result = registration_cancel($registrationId);
    
    if ($result) {
        $_SESSION['success'] = "Registration cancelled successfully.";
    } else {
        $_SESSION['error'] = "Failed to cancel registration.";
    }
    
    redirect('/my-registrations');
}
