<?php
$pageTitle = 'Inventory Configuration | True Elite ERP';
$moduleName = 'Inventory';
$subSection = 'configuration';
require_once '../includes/header.php';
require_once '../config/db.php';

// Fetch distinct categories
$categories = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''")->fetchAll(PDO::FETCH_COLUMN);
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Bar -->
    <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shadow-sm sticky top-12 z-20">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Inventory Settings & Categories</h1>
            <p class="text-xs text-gray-500">Configure product categories, reorder rules, and stock alert defaults</p>
        </div>
        <a href="index.php" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase">BACK TO PRODUCTS</a>
    </div>

    <!-- Main Form Grid -->
    <main class="flex-1 p-6 max-w-5xl w-full mx-auto space-y-6">
        
        <!-- Category Management Card -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Active Product Categories</h3>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <?php foreach ($categories as $cat): ?>
                    <span class="bg-purple-50 text-odoo-purple border border-purple-200 px-3 py-1 rounded-sm text-xs font-bold flex items-center gap-1.5">
                        <?php echo htmlspecialchars($cat); ?>
                    </span>
                <?php endforeach; ?>
                <span class="bg-gray-50 text-gray-600 border border-gray-200 px-3 py-1 rounded-sm text-xs font-medium">General</span>
            </div>

            <p class="text-xs text-gray-500 italic">Product categories are automatically assigned when adding new items or creating direct purchase vouchers.</p>
        </div>

        <!-- Default Stock Settings Card -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6 space-y-4">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-2">Reorder & Stock Alert Defaults</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Default Minimum Stock Alert Threshold</label>
                    <input type="number" value="5" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-50 text-gray-700 font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Valuation Method</label>
                    <input type="text" value="Standard Price (FIFO Cost Basis)" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-50 text-gray-700 font-semibold">
                </div>
            </div>
        </div>

    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
