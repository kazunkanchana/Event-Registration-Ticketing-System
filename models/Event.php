<?php
/**
 * Event Functions
 * ---------------
 * Simple functions to handle event data.
 */

/**
 * Get all events
 * --------------
 * Supports filters for category, search, and status.
 */
function event_get_all($filters = [], $limit = null) {
    $sql = "SELECT e.*, c.name as category_name 
            FROM events e 
            LEFT JOIN categories c ON e.category_id = c.id";
    
    $conditions = [];
    $params = [];
    
    // Status Filter
    if (isset($filters['status'])) {
        $conditions[] = "e.status = :status";
        $params[':status'] = $filters['status'];
    }
    
    // Category Filter
    if (isset($filters['category'])) {
        $conditions[] = "c.name = :category";
        $params[':category'] = $filters['category'];
    }
    
    // Search Filter
    if (isset($filters['search'])) {
        $conditions[] = "e.title LIKE :search OR e.description LIKE :search";
        $params[':search'] = '%' . $filters['search'] . '%';
    }
    
    // Combine SQL
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY e.event_date DESC";
    
    // Add limit if specified
    if ($limit !== null) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    return db_query_all($sql, $params);
}

/**
 * Get single event by ID
 * ----------------------
 */
function event_get_by_id($id) {
    $sql = "SELECT e.*, c.name as category_name 
            FROM events e 
            LEFT JOIN categories c ON e.category_id = c.id 
            WHERE e.id = :id";
    
    $params = [];
    $params[':id'] = $id;
    
    return db_query_one($sql, $params);
}

/**
 * Get upcoming events (Limit)
 * ---------------------------
 */
function event_get_upcoming($limit = 3) {
    $sql = "SELECT * FROM events 
            WHERE status = 'upcoming' 
            ORDER BY event_date ASC 
            LIMIT " . (int)$limit;
            
    return db_query_all($sql);
}

/**
 * Create new event
 * ----------------
 */
function event_create($data) {
    // DEBUG: Log attempt
    file_put_contents('debug_create_event.txt', date('Y-m-d H:i:s') . " - event_create called with data:\n" . print_r($data, true) . "\n", FILE_APPEND);
    
    $sql = "INSERT INTO events (title, category_id, description, venue, event_date, event_time, ticket_price, capacity, available_seats, image, created_by, status) 
            VALUES (:title, :category_id, :description, :venue, :event_date, :event_time, :ticket_price, :capacity, :available_seats, :image, :created_by, :status)";
            
    $params = [];
    $params[':title'] = $data['title'];
    $params[':category_id'] = $data['category_id'];
    $params[':description'] = $data['description'];
    $params[':venue'] = $data['venue'];
    $params[':event_date'] = $data['event_date'];
    $params[':event_time'] = $data['event_time'];
    $params[':ticket_price'] = $data['ticket_price'];
    $params[':capacity'] = $data['capacity'];
    $params[':available_seats'] = $data['capacity']; // Same value as capacity
    $params[':image'] = $data['image'];
    $params[':created_by'] = $data['created_by'];
    $params[':status'] = $data['status'];
    
    // DEBUG: Log SQL and params
    file_put_contents('debug_create_event.txt', date('Y-m-d H:i:s') . " - SQL: $sql\n", FILE_APPEND);
    file_put_contents('debug_create_event.txt', date('Y-m-d H:i:s') . " - Params:\n" . print_r($params, true) . "\n", FILE_APPEND);
    
    $result = db_execute($sql, $params);
    
    // DEBUG: Log db_execute result
    file_put_contents('debug_create_event.txt', date('Y-m-d H:i:s') . " - db_execute returned: " . ($result ? 'TRUE' : 'FALSE') . "\n", FILE_APPEND);
    
    return $result;
}

/**
 * Update event
 * ------------
 */
function event_update($id, $data) {
    $sql = "UPDATE events SET 
            title = :title, 
            category_id = :category_id,
            description = :description,
            venue = :venue,
            event_date = :event_date,
            event_time = :event_time,
            ticket_price = :ticket_price,
            capacity = :capacity,
            status = :status 
            WHERE id = :id";
            
    $params = [];
    $params[':id'] = $id;
    $params[':title'] = $data['title'];
    $params[':category_id'] = $data['category_id'];
    $params[':description'] = $data['description'];
    $params[':venue'] = $data['venue'];
    $params[':event_date'] = $data['event_date'];
    $params[':event_time'] = $data['event_time'];
    $params[':ticket_price'] = $data['ticket_price'];
    $params[':capacity'] = $data['capacity'];
    $params[':status'] = $data['status'];
    
    return db_execute($sql, $params);
}

/**
 * Delete event
 * ------------
 */
function event_delete($id) {
    db_transaction_start();
    try {
        // 1. Delete Registrations
        $sql = "DELETE FROM registrations WHERE event_id = :id";
        if (!db_execute($sql, [':id' => $id])) {
             // It's okay if 0 rows affect, but if error? db_execute returns true usually.
        }
        
        // 2. Delete Event
        $sql = "DELETE FROM events WHERE id = :id";
        if (!db_execute($sql, [':id' => $id])) {
            throw new Exception("Failed to delete event");
        }
        
        db_transaction_commit();
        return true;
    } catch (Exception $e) {
        db_transaction_rollback();
        return false;
    }
}

/**
 * Check if event has seats
 * ------------------------
 */
function event_has_seats($id) {
    $sql = "SELECT available_seats FROM events WHERE id = :id";
    
    $params = [];
    $params[':id'] = $id;
    
    $event = db_query_one($sql, $params);
    
    if ($event && $event['available_seats'] > 0) {
        return true;
    }
    
    return false;
}

/**
 * Get Total Event Count
 * ---------------------
 */
function event_count($status = null) {
    if ($status) {
        $sql = "SELECT COUNT(*) as count FROM events WHERE status = :status";
        
        $params = [];
        $params[':status'] = $status;
        
        $result = db_query_one($sql, $params);
    } else {
        $sql = "SELECT COUNT(*) as count FROM events";
        $result = db_query_one($sql);
    }
    
    if ($result) {
        return $result['count'];
    }
    
    return 0;
}
