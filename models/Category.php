<?php
/**
 * Category Functions
 * ------------------
 */

/**
 * Get All Categories
 * ------------------
 */
function category_get_all() {
    $sql = "SELECT * FROM categories ORDER BY name ASC";
    return db_query_all($sql);
}

/**
 * Get Category Name
 * -----------------
 */
function category_get_name($id) {
    $sql = "SELECT name FROM categories WHERE id = :id";
    $params = [];
    $params[':id'] = $id;
    
    $result = db_query_one($sql, $params);
    
    if ($result) {
        return $result['name'];
    }
    return false;
}

/**
 * Create Category
 * ---------------
 */
function category_create($name) {
    // Check if exists
    $sql = "SELECT id FROM categories WHERE name = :name";
    $exists = db_query_one($sql, [':name' => $name]);
    if ($exists) return false;

    $sql = "INSERT INTO categories (name) VALUES (:name)";
    $params = [':name' => $name];
    if (db_execute($sql, $params)) {
        return db_last_id();
    }
    return false;
}

/**
 * Delete Category
 * ---------------
 */
function category_delete($id) {
    // Prevent delete if events exist?
    // For now, let's assume we want to block deletion if events exist.
    $sql = "SELECT COUNT(*) as count FROM events WHERE category_id = :id";
    $result = db_query_one($sql, [':id' => $id]);
    if ($result && $result['count'] > 0) {
        return 'has_events'; // Cannot delete if events linked
    }

    $sql = "DELETE FROM categories WHERE id = :id";
    return db_execute($sql, [':id' => $id]);
}
