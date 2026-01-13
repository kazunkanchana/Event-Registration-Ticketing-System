<?php 
// Set page title
$pageTitle = 'Manage Categories - Admin';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <h1 class="mb-3">Manage Categories</h1>
    
    <div class="grid grid-2">
        <!-- List Categories -->
        <div class="card">
            <div class="card-header">
                <h2>Existing Categories</h2>
            </div>
            <div class="card-body">
                <?php if (empty($categories)): ?>
                    <p>No categories found.</p>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td>
                                        <a href="/Event/admin/deleteCategory/<?php echo $category['id']; ?>" 
                                           class="btn btn-danger" 
                                           style="font-size: 0.875rem; padding: 0.25rem 0.5rem;"
                                           onclick="return confirm('Are you sure?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Create Category -->
        <div class="card" style="height: fit-content;">
            <div class="card-header">
                <h2>Create New Category</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="/Event/admin/categories">
                    <div class="form-group">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-input" required placeholder="E.g. Music, Tech">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
