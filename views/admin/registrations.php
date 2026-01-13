<?php 
// Set page title
$pageTitle = 'Manage Registrations - Admin';
require_once __DIR__ . '/../layout/header.php'; 

// Calculate statistics
$totalRegistrations = count($registrations);
$totalRevenue = 0;
foreach ($registrations as $reg) {
    $totalRevenue += $reg['ticket_price'];
}
?>

<main class="container">
    <h1 class="mb-3">Event Registrations Management</h1>
    
    <!-- Statistics Cards -->
    <div class="grid grid-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">
                    <?php echo $totalRegistrations; ?>
                </h3>
                <p style="color: var(--text-secondary); font-weight: 500;">Total Registrations</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <h3 style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 0.5rem;">
                    Rs. <?php echo number_format($totalRevenue, 2); ?>
                </h3>
                <p style="color: var(--text-secondary); font-weight: 500;">Total Revenue</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <h3 style="font-size: 2.5rem; color: var(--warning-color); margin-bottom: 0.5rem;">
                    Rs. <?php echo $totalRegistrations > 0 ? number_format($totalRevenue / $totalRegistrations, 2) : '0.00'; ?>
                </h3>
                <p style="color: var(--text-secondary); font-weight: 500;">Average Ticket Price</p>
            </div>
        </div>
    </div>
    
    <!-- Registrations Table -->
    <?php if (empty($registrations)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p>No registrations yet.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header">
                <h2>All Registrations</h2>
            </div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Event</th>
                            <th>Event Date</th>
                            <th>Ticket Price</th>
                            <th>Registered On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $registration): ?>
                            <tr>
                                <td>
                                    <code style="background: var(--light-color); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-family: 'Courier New', monospace; font-size: 0.85rem;">
                                        <?php echo htmlspecialchars($registration['ticket_number']); ?>
                                    </code>
                                </td>
                                <td><?php echo htmlspecialchars($registration['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($registration['user_email']); ?></td>
                                <td><?php echo htmlspecialchars($registration['event_title']); ?></td>
                                <td>
                                    <?php echo date('M j, Y', strtotime($registration['event_date'])); ?><br>
                                    <small><?php echo date('g:i A', strtotime($registration['event_time'])); ?></small>
                                </td>
                                <td>Rs. <?php echo number_format($registration['ticket_price'], 2); ?></td>
                                <td><?php echo date('M j, Y', strtotime($registration['registration_date'])); ?></td>
                                <td>
                                    <span style="text-transform: capitalize; 
                                                 padding: 0.25rem 0.5rem; 
                                                 border-radius: var(--radius-sm); 
                                                 font-size: 0.875rem;
                                                 background: <?php echo $registration['status'] === 'confirmed' ? '#d1fae5' : '#fee2e2'; ?>;
                                                 color: <?php echo $registration['status'] === 'confirmed' ? '#065f46' : '#991b1b'; ?>;">
                                        <?php echo htmlspecialchars($registration['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/Event/event/<?php echo $registration['event_id']; ?>" 
                                       class="btn btn-outline" style="font-size: 0.875rem; padding: 0.25rem 0.5rem;">
                                        View Event
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
