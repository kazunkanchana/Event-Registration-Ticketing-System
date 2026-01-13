<?php
require_once 'config/database.php';
require_once 'core/Database.php';
require_once 'core/helpers.php';
require_once 'models/User.php';

echo "<h1>Creating Admin User...</h1>";

$email = 'admin@ticketflow.com';
$password = 'password123';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Check if exists
$exists = user_email_exists($email);

if ($exists) {
    echo "Admin user already exists.<br>";
    // Optional: Update password to be sure
    $sql = "UPDATE users SET password = :pass, role = 'admin' WHERE email = :email";
    db_execute($sql, [':pass' => $hash, ':email' => $email]);
    echo "Updated admin password/role.<br>";
} else {
    $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role) 
            VALUES ('Super', 'Admin', :email, '0000000000', :pass, 'admin')";
            
    $params = [
        ':email' => $email,
        ':pass' => $hash
    ];
    
    if (db_execute($sql, $params)) {
        echo "Admin user created successfully.<br>";
    } else {
        echo "Failed to create admin.<br>";
    }
}

echo "<br><strong>Email:</strong> $email<br>";
echo "<strong>Password:</strong> $password<br>";
echo "<br><a href='/Event/login'>Go to Login</a>";
?>
