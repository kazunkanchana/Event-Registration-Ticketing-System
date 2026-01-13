<?php 
// Set page title
$pageTitle = 'Edit Event - Admin';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <h1 class="mb-3">Edit Event</h1>
    
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/Event/admin/editEvent/<?php echo $event['id']; ?>">
                    <!-- Category -->
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                    <?php echo ($event['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Title -->
                    <div class="form-group">
                        <label class="form-label">Event Title *</label>
                        <input type="text" name="title" class="form-input" required 
                               placeholder="Enter event title"
                               value="<?php echo htmlspecialchars($event['title']); ?>">
                    </div>
                    
                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-textarea" required 
                                  placeholder="Enter event description"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    
                    <!-- Venue -->
                    <div class="form-group">
                        <label class="form-label">Venue *</label>
                        <input type="text" name="venue" class="form-input" required 
                               placeholder="Enter event venue"
                               value="<?php echo htmlspecialchars($event['venue']); ?>">
                    </div>
                    
                    <div class="grid grid-2">
                        <!-- Event Date -->
                        <div class="form-group">
                            <label class="form-label">Event Date *</label>
                            <input type="date" name="event_date" class="form-input" required
                                   value="<?php echo $event['event_date']; ?>">
                        </div>
                        
                        <!-- Event Time -->
                        <div class="form-group">
                            <label class="form-label">Event Time *</label>
                            <input type="time" name="event_time" class="form-input" required
                                   value="<?php echo $event['event_time']; ?>">
                        </div>
                    </div>
                    
                    <div class="grid grid-2">
                        <!-- Ticket Price -->
                        <div class="form-group">
                            <label class="form-label">Ticket Price (Rs.) *</label>
                            <input type="number" name="ticket_price" class="form-input" required 
                                   min="0" step="0.01" placeholder="0.00"
                                   value="<?php echo $event['ticket_price']; ?>">
                        </div>
                        
                        <!-- Capacity -->
                        <div class="form-group">
                            <label class="form-label">Capacity *</label>
                            <input type="number" name="capacity" class="form-input" required 
                                   min="1" placeholder="Number of seats"
                                   value="<?php echo $event['capacity']; ?>">
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="upcoming" <?php echo ($event['status'] === 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                            <option value="ongoing" <?php echo ($event['status'] === 'ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                            <option value="completed" <?php echo ($event['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo ($event['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    
                    <!-- Event Info -->
                    <div class="alert" style="background: #fef3c7; color: #92400e; border-left: 4px solid var(--warning-color);">
                        <p><strong>Current Registrations:</strong> <?php echo ($event['capacity'] - $event['available_seats']); ?> / <?php echo $event['capacity']; ?> seats booked</p>
                        <p><strong>Available Seats:</strong> <?php echo $event['available_seats']; ?></p>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="grid grid-2">
                        <a href="/Event/admin/events" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
