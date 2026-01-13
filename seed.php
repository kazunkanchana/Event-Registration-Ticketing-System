<?php
require_once 'config/database.php';
require_once 'core/Database.php';
require_once 'core/helpers.php';

// Create Sample Category
db_execute("INSERT IGNORE INTO categories (id, name, description) VALUES (1, 'Music', 'Live concerts and festivals')");

// Create Sample Events
$events = [
    [
        'title' => 'Summer Music Festival',
        'venue' => 'Colombo Port City',
        'date' => date('Y-m-d', strtotime('+7 days')),
        'time' => '18:00:00',
        'price' => 5000.00
    ],
    [
        'title' => 'Tech Startup Meetup',
        'venue' => 'Lotus Tower Auditorium',
        'date' => date('Y-m-d', strtotime('+14 days')),
        'time' => '09:00:00',
        'price' => 1500.00
    ],
    [
        'title' => 'Food Carnival 2026',
        'venue' => 'Viharamahadevi Park',
        'date' => date('Y-m-d', strtotime('+21 days')),
        'time' => '10:00:00',
        'price' => 0.00
    ]
];

echo "<h1>Seeding Data...</h1>";

foreach ($events as $evt) {
    try {
        $sql = "INSERT INTO events (title, category_id, description, venue, event_date, event_time, ticket_price, capacity, available_seats, status, created_by, image) 
                VALUES (:title, 1, 'A dummy sample event description.', :venue, :date, :time, :price, 100, 100, 'upcoming', 1, 'default.jpg')";
        
        $params = [];
        $params[':title'] = $evt['title'];
        $params[':venue'] = $evt['venue'];
        $params[':date'] = $evt['date'];
        $params[':time'] = $evt['time'];
        $params[':price'] = $evt['price'];
        
        db_execute($sql, $params);
        echo "Created: " . $evt['title'] . "<br>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}

echo "<h2>Done!</h2><a href='/Event/'>Go Home</a>";
?>
