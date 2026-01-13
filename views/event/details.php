<?php 
// Set page title
$pageTitle = htmlspecialchars($event['title']);
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <!-- Back Button -->
    <a href="/Event/events" class="btn btn-outline mb-3">← Back to Events</a>
    
    <!-- Event Details Card -->
    <div class="card">
        <?php if ($event['category_name']): ?>
            <div style="background: var(--secondary-color); color: white; padding: 1rem; font-size: 1rem; font-weight: 500;">
                <?php echo htmlspecialchars($event['category_name']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Event Banner Image -->
        <?php if ($event['image'] && $event['image'] !== 'default.jpg'): ?>
            <div style="width: 100%; max-height: 400px; overflow: hidden;">
                <img src="/Event/public/uploads/events/<?php echo htmlspecialchars($event['image']); ?>" 
                     alt="<?php echo htmlspecialchars($event['title']); ?>"
                     style="width: 100%; height: auto; object-fit: cover;">
            </div>
        <?php endif; ?>
        
        <div class="card-header">
            <h1><?php echo htmlspecialchars($event['title']); ?></h1>
        </div>
        
        <div class="card-body">
            <div class="grid grid-2">
                <!-- Event Information -->
                <div>
                    <h3>Event Details</h3>
                    <p><strong>Description:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                    
                    <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                    <p><strong>Date:</strong> <?php echo date('l, F j, Y', strtotime($event['event_date'])); ?></p>
                    <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                    <p><strong>Ticket Price:</strong> Rs. <?php echo number_format($event['ticket_price'], 2); ?></p>
                    <p><strong>Capacity:</strong> <?php echo $event['capacity']; ?> people</p>
                    <p><strong>Available Seats:</strong> <?php echo $event['available_seats']; ?> remaining</p>
                    <p><strong>Status:</strong> 
                        <span style="text-transform: capitalize; color: var(--secondary-color); font-weight: 500;">
                            <?php echo htmlspecialchars($event['status']); ?>
                        </span>
                    </p>
                </div>
                
                <!-- Registration Section -->
                <div>
                    <h3>Registration</h3>
                    
                    <?php if ($isRegistered): ?>
                        <!-- Already Registered -->
                        <div class="alert alert-success">
                            <p><strong>✓ You are registered for this event!</strong></p>
                            <p>Check your registrations to view your ticket details.</p>
                        </div>
                        <a href="/Event/my-registrations" 
                           class="btn btn-secondary">View My Registrations</a>
                    
                    <?php elseif ($event['available_seats'] <= 0): ?>
                        <!-- Fully Booked -->
                        <div class="alert alert-error">
                            <p><strong>This event is fully booked.</strong></p>
                            <p>No seats available at the moment.</p>
                        </div>
                    
                    <?php elseif (!isset($_SESSION['user_id'])): ?>
                        <!-- Not Logged In -->
                        <div class="alert" style="background: #fef3c7; color: #92400e; border-left: 4px solid var(--warning-color);">
                            <p><strong>Login Required</strong></p>
                            <p>Please login to register for this event.</p>
                        </div>
                        <a href="/Event/login" class="btn btn-primary">Login to Register</a>
                    
                    <?php elseif (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')): ?>
                        <!-- Admin Cannot Register -->
                        <div class="alert" style="background: #e0e7ff; color: #3730a3; border-left: 4px solid var(--primary-color);">
                            <p><strong>Admin Access</strong></p>
                            <p>As an administrator, you cannot register for events. You can manage this event from the Admin Panel.</p>
                        </div>
                        <a href="/Event/admin/events" class="btn btn-outline">Manage Events</a>
                    
                    <?php else: ?>
                        <!-- Can Register -->
                        <p>Register now to secure your seat at this event!</p>
                        <p><strong>Ticket Price:</strong> Rs. <?php echo number_format($event['ticket_price'], 2); ?></p>
                        <p><strong>Seats Remaining:</strong> <?php echo $event['available_seats']; ?></p>
                        
                        <a href="/Event/registration/register/<?php echo $event['id']; ?>" 
                           class="btn btn-primary">
                            Register Now
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
