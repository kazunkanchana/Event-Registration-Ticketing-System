<?php 
// Set page title
$pageTitle = 'All Events';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <h1 class="mb-3">Browse Events</h1>
    
    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="/Event/events">
                <div class="grid grid-3">
                    <!-- Category Filter -->
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                    <?php echo (isset($filters['category']) && $filters['category'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Search -->
                    <div class="form-group">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-input" 
                               placeholder="Search by title or venue..."
                               value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>">
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="form-group" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Events List -->
    <?php if (empty($events)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p>No events found matching your criteria.</p>
                <a href="/Event/events" class="btn btn-primary mt-2">Clear Filters</a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-3">
            <?php foreach ($events as $event): ?>
                <!-- Event Card -->
                <div class="card">
                    <?php if ($event['category_name']): ?>
                        <div style="background: var(--secondary-color); color: white; padding: 0.5rem; font-size: 0.875rem; font-weight: 500;">
                            <?php echo htmlspecialchars($event['category_name']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-header">
                        <?php echo htmlspecialchars($event['title']); ?>
                    </div>
                    <div class="card-body">
                        <p><?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...</p>
                        <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                        <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                        <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                        <p><strong>Price:</strong> Rs. <?php echo number_format($event['ticket_price'], 2); ?></p>
                        
                        <?php if ($event['available_seats'] > 0): ?>
                            <p><strong>Available:</strong> <?php echo $event['available_seats']; ?> seats</p>
                            <a href="/Event/event/details/<?php echo $event['id']; ?>" 
                               class="btn btn-primary mt-2">View Details</a>
                        <?php else: ?>
                            <p style="color: var(--danger-color); font-weight: 500;">Fully Booked</p>
                            <a href="/Event/event/details/<?php echo $event['id']; ?>" 
                               class="btn btn-outline mt-2">View Details</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
