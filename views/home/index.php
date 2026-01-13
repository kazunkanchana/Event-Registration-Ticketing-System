<?php 
// Set page title
$pageTitle = 'Home';
require_once __DIR__ . '/../layout/header.php'; 
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>TicketFlow</h1>
            <p>Discover and register for amazing events in Sri Lanka</p>
            <a href="/Event/events" class="btn btn-primary">Browse Events</a>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<main class="container">
    <h2 class="text-center mb-3">Upcoming Events</h2>
    
    <?php if (empty($upcomingEvents)): ?>
        <p class="text-center">No upcoming events at the moment. Check back later!</p>
    <?php else: ?>
        <div class="grid grid-3">
            <?php foreach ($upcomingEvents as $event): ?>
                <!-- Event Card -->
                <div class="card">
                    <div class="card-header">
                        <?php echo htmlspecialchars($event['title']); ?>
                    </div>
                    <div class="card-body">
                        <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                        <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                        <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                        <p><strong>Price:</strong> Rs. <?php echo number_format($event['ticket_price'], 2); ?></p>
                        <p><strong>Available Seats:</strong> <?php echo $event['available_seats']; ?> / <?php echo $event['capacity']; ?></p>
                        
                        <a href="/Event/event/details/<?php echo $event['id']; ?>" 
                           class="btn btn-primary mt-2">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/Event/events" class="btn btn-outline">View All Events</a>
        </div>
    <?php endif; ?>
</main>

<!-- Add hero section styling -->
<style>
.hero {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: var(--white);
    padding: 4rem 0;
    margin-bottom: 3rem;
}

.hero-content {
    text-align: center;
}

.hero-content h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.hero-content p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
