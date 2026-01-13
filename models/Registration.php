<?php
/**
 * Registration Functions
 * ----------------------
 * Handles event registration logic.
 */

/**
 * Register User for Event
 * -----------------------
 */
function registration_create($userId, $eventId) {
    if (!$userId) return false;

    // Check if already registered (in any state)
    // We need to know if we should INSERT or UPDATE
    $sql = "SELECT id, status FROM registrations WHERE user_id = :u AND event_id = :e";
    $existing = db_query_one($sql, [':u' => $userId, ':e' => $eventId]);

    db_transaction_start();
    
    try {
        $registrationId = 0;

        if ($existing) {
            // Already exists. Check status.
            if ($existing['status'] == 'confirmed') {
                // Already confirmed. Do nothing.
                db_transaction_rollback();
                return $existing['id'];
            } else {
                // Was cancelled. Reactivate it!
                $registrationId = $existing['id'];
                $sql = "UPDATE registrations SET status = 'confirmed', registration_date = CURRENT_TIMESTAMP WHERE id = :id";
                // Note: We update created_at so it shows as a "new" registration date
                if (!db_execute($sql, [':id' => $registrationId])) {
                     throw new Exception("Failed to reactivate");
                }
            }
        } else {
            // New Registration
            $time = time();
            $ticketNumber = 'EVT-' . $eventId . '-' . $userId . '-' . $time;
            
            $sql = "INSERT INTO registrations (user_id, event_id, ticket_number, status) 
                    VALUES (:user_id, :event_id, :ticket_number, 'confirmed')";
            
            $params = [];
            $params[':user_id'] = $userId;
            $params[':event_id'] = $eventId;
            $params[':ticket_number'] = $ticketNumber;
            
            if (!db_execute($sql, $params)) {
                throw new Exception("Failed to register");
            }
            $registrationId = db_last_id();
        }

        // Decrease seats (Common for both New and Reactivated)
        $sql = "UPDATE events SET available_seats = available_seats - 1 WHERE id = :event_id AND available_seats > 0";
        if (!db_execute($sql, [':event_id' => $eventId])) {
            throw new Exception("No seats available");
        }
        
        db_transaction_commit();
        return $registrationId;
        
    } catch (Exception $e) {
        db_transaction_rollback();
        return false;
    }
}

/**
 * Cancel Registration
 * -------------------
 */
function registration_cancel($registrationId) {
    // Get details first
    $sql = "SELECT event_id FROM registrations WHERE id = :id";
    $paramsLookup = [];
    $paramsLookup[':id'] = $registrationId;
    
    $reg = db_query_one($sql, $paramsLookup);
    
    if (!$reg) {
        return false;
    }
    
    db_transaction_start();
    
    try {
        // 1. Update status
        $sql = "UPDATE registrations SET status = 'cancelled' WHERE id = :id";
        $params = [];
        $params[':id'] = $registrationId;
        
        if (!db_execute($sql, $params)) {
             throw new Exception("Update failed");
        }
        
        // 2. Increase seats
        $sql = "UPDATE events SET available_seats = available_seats + 1 WHERE id = :event_id";
        $params2 = [];
        $params2[':event_id'] = $reg['event_id'];
        
        if (!db_execute($sql, $params2)) {
            throw new Exception("Seat update failed");
        }
        
        db_transaction_commit();
        return true;
        
    } catch (Exception $e) {
        db_transaction_rollback();
        return false;
    }
}

/**
 * Check if registered
 * -------------------
 */
function registration_check($userId, $eventId) {
    // Check for active (confirmed) registrations
    $sql = "SELECT id FROM registrations 
            WHERE user_id = :user_id 
            AND event_id = :event_id 
            AND status = 'confirmed'";
            
    $params = [];
    $params[':user_id'] = $userId;
    $params[':event_id'] = $eventId;
    
    $result = db_query_one($sql, $params);
    
    if ($result) {
        return true;
    }
    return false;
}

/**
 * Get User Registrations
 * ----------------------
 */
function registration_get_by_user($userId) {
    // Select all necessary fields, including aliases for view compatibility
    $sql = "SELECT r.*, 
            e.title as event_title, 
            e.event_date, 
            e.event_time, 
            e.venue,
            e.ticket_price,
            c.name as category_name
            FROM registrations r 
            JOIN events e ON r.event_id = e.id 
            LEFT JOIN categories c ON e.category_id = c.id
            WHERE r.user_id = :user_id 
            ORDER BY r.registration_date DESC";
            
    $params = [];
    $params[':user_id'] = $userId;
    
    return db_query_all($sql, $params);
}

/**
 * Get All Registrations (Admin)
 * -----------------------------
 */
function registration_get_all() {
    $sql = "SELECT r.*, 
            e.title as event_title, 
            e.event_date,
            e.event_time,
            e.ticket_price,
            u.email as user_email,
            CONCAT(u.first_name, ' ', u.last_name) as user_name 
            FROM registrations r 
            JOIN events e ON r.event_id = e.id 
            JOIN users u ON r.user_id = u.id 
            ORDER BY r.registration_date DESC";
            
    return db_query_all($sql);
}

/**
 * Get Registrations by Event (Admin)
 * -----------------------------------
 */
function registration_get_by_event($eventId) {
    $sql = "SELECT r.*, 
            u.email as user_email,
            u.phone as user_phone,
            CONCAT(u.first_name, ' ', u.last_name) as user_name 
            FROM registrations r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.event_id = :event_id
            ORDER BY r.registration_date DESC";
            
    $params = [];
    $params[':event_id'] = $eventId;
    
    return db_query_all($sql, $params);
}

/**
 * Count Registrations
 * -------------------
 */
function registration_count() {
    $sql = "SELECT COUNT(*) as count FROM registrations";
    $result = db_query_one($sql);
    
    if ($result) {
        return $result['count'];
    }
    return 0;
}
