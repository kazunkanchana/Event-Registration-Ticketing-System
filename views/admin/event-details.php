<?php 
// Set page title
$pageTitle = 'Event Details - Admin';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <!-- Back Button -->
    <a href="/Event/admin/events" class="btn btn-outline mb-3">← Back to Manage Events</a>
    
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
            <!-- Event Information -->
            <h3>Event Details</h3>
            <div class="grid grid-2 mb-3">
                <div>
                    <p><strong>Description:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                </div>
                
                <div>
                    <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                    <p><strong>Date:</strong> <?php echo date('l, F j, Y', strtotime($event['event_date'])); ?></p>
                    <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                    <p><strong>Ticket Price:</strong> Rs. <?php echo number_format($event['ticket_price'], 2); ?></p>
                    <p><strong>Total Capacity:</strong> <?php echo $event['capacity']; ?> people</p>
                    <p><strong>Available Seats:</strong> <?php echo $event['available_seats']; ?> remaining</p>
                    <p><strong>Status:</strong> 
                        <span style="text-transform: capitalize; color: var(--secondary-color); font-weight: 500;">
                            <?php echo htmlspecialchars($event['status']); ?>
                        </span>
                    </p>
                </div>
            </div>
            
            <!-- Registration Statistics -->
            <div class="grid grid-3 mb-3">
                <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="card-body text-center">
                        <h3 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: white;"><?php echo $totalRegistrations; ?></h3>
                        <p style="font-weight: 500; color: white;">Total Registrations</p>
                    </div>
                </div>
                
                <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                    <div class="card-body text-center">
                        <h3 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: white;"><?php echo $event['available_seats']; ?></h3>
                        <p style="font-weight: 500; color: white;">Seats Available</p>
                    </div>
                </div>
                
                <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                    <div class="card-body text-center">
                        <h3 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: #ffffff !important;">
                            <?php echo number_format(($totalRegistrations / $event['capacity']) * 100, 1) . '%'; ?>
                        </h3>
                        <p style="font-weight: 500; color: white;">Capacity Filled</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Registered Users Table -->
    <div class="card">
        <div class="card-header">
            <h2>Registered Attendees</h2>
        </div>
        
        <?php if (empty($registrations)): ?>
            <div class="card-body text-center">
                <p>No registrations yet for this event.</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Ticket Number</th>
                            <th>Registration Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $reg): ?>
                            <tr>
                                <td><?php echo $reg['id']; ?></td>
                                <td><?php echo htmlspecialchars($reg['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($reg['user_email']); ?></td>
                                <td><?php echo htmlspecialchars($reg['user_phone']); ?></td>
                                <td><?php echo htmlspecialchars($reg['ticket_number']); ?></td>
                                <td><?php echo date('M j, Y g:i A', strtotime($reg['registration_date'])); ?></td>
                                <td>
                                    <?php if ($reg['status'] === 'confirmed'): ?>
                                        <span class="badge" style="background-color: var(--secondary-color); color: white;">Confirmed</span>
                                    <?php else: ?>
                                        <span class="badge" style="background-color: var(--danger-color); color: white;">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
