<?php
/**
 * User Functions
 * --------------
 * Simple functions to handle user logic.
 */

/**
 * Register a new user
 * -------------------
 */
function user_register($data) {
    // 1. Hash Password
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // 2. Insert into DB
    $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role) 
            VALUES (:first_name, :last_name, :email, :phone, :password, 'user')";
            
    $params = [];
    $params[':first_name'] = $data['first_name'];
    $params[':last_name'] = $data['last_name'];
    $params[':email'] = $data['email'];
    $params[':phone'] = $data['phone'];
    $params[':password'] = $passwordHash;
    
    $result = db_execute($sql, $params);
    
    if ($result) {
        return db_last_id();
    }
    
    return false;
}

/**
 * Login User
 * ----------
 */
function user_login($email, $password) {
    // 1. Find User
    $sql = "SELECT * FROM users WHERE email = :email";
    
    $params = [];
    $params[':email'] = $email;
    
    $user = db_query_one($sql, $params);
    
    // 2. Verify Password
    if ($user) {
        if (password_verify($password, $user['password'])) {
            // Updated to store email for Super Admin check
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            return $user; 
        }
    }
    
    return false;
}

/**
 * Check if Email Exists
 * ---------------------
 */
function user_email_exists($email, $excludeId = null) {
    $sql = "SELECT id FROM users WHERE email = :email";
    $params = [];
    $params[':email'] = $email;
    
    if ($excludeId) {
        $sql .= " AND id != :id";
        $params[':id'] = $excludeId;
    }
    
    $result = db_query_one($sql, $params);
    
    if ($result) {
        return true;
    }
    return false;
}

/**
 * Get User by ID
 * --------------
 */
function user_get_by_id($id) {
    $sql = "SELECT * FROM users WHERE id = :id";
    
    $params = [];
    $params[':id'] = $id;
    
    return db_query_one($sql, $params);
}

/**
 * Update User
 * -----------
 */
function user_update($id, $data) {
    $sql = "UPDATE users SET 
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            phone = :phone 
            WHERE id = :id";
            
    $params = [];
    $params[':id'] = $id;
    $params[':first_name'] = $data['first_name'];
    $params[':last_name'] = $data['last_name'];
    $params[':email'] = $data['email'];
    $params[':phone'] = $data['phone'];
    
    return db_execute($sql, $params);
}

/**
 * Get All Users
 * -------------
 */
function user_get_all() {
    $sql = "SELECT * FROM users ORDER BY created_at DESC";
    return db_query_all($sql);
}

/**
 * User Count
 * ----------
 */
function user_count() {
    $sql = "SELECT COUNT(*) as count FROM users";
    $result = db_query_one($sql);
    
    if ($result) {
        return $result['count'];
    }
    return 0;
}

/**
 * Delete User
 * -----------
 */
function user_delete($id) {
    db_transaction_start();
    try {
        // 1. Delete Registrations (User's tickets)
        $sql = "DELETE FROM registrations WHERE user_id = :id";
        db_execute($sql, [':id' => $id]);
        
        // 2. Unlink Events Created by User
        $sql = "UPDATE events SET created_by = NULL WHERE created_by = :id";
        db_execute($sql, [':id' => $id]);
        
        // 3. Delete User
        $sql = "DELETE FROM users WHERE id = :id";
        if (!db_execute($sql, [':id' => $id])) {
            throw new Exception("Failed to delete user");
        }
        
        db_transaction_commit();
        return true;
    } catch (Exception $e) {
        db_transaction_rollback();
        return false;
    }
}

/**
 * Create Admin User (Manual Role Assignment)
 * ------------------------------------------
 */
function user_create_admin($data) {
    // 1. Hash Password
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // 2. Insert into DB with 'admin' role
    $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role) 
            VALUES (:first_name, :last_name, :email, :phone, :password, :role)";
            
    $params = [];
    $params[':first_name'] = $data['first_name'];
    $params[':last_name'] = $data['last_name'];
    $params[':email'] = $data['email'];
    $params[':phone'] = $data['phone'];
    $params[':password'] = $passwordHash;
    $params[':role'] = $data['role']; // Should be 'admin'
    
    $result = db_execute($sql, $params);
    
    if ($result) {
        return db_last_id();
    }
    
    return false;
}
