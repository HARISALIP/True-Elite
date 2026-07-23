<?php
$pageTitle = 'Inventory Reporting | True Elite ERP';
$moduleName = 'Inventory';
$subSection = 'reporting';
require_once '../includes/header.php';
require_once '../config/db.php';

// Fetch inventory valuation metrics
$stmt = $pdo->query("
    SELECT 
        COUNT(*) as total_items,
        SUM(quantity_in_stock) as total_qty,
        SUM(cost * quantity_in_stock) as total_cost_valuation,
        SUM(sales_price * quantity_in_stock) as total_sales_valuation
    FROM products
");
$metrics = $stmt->fetch();

$totalCost = floatval($metrics['total_cost_valuation'] ?? 0);
$totalSales = floatval($metrics['total_sales_valuation'] ?? 0);
$profitMargin = $totalSales - $totalCost;

// Top products by valuation
$topProducts = $pdo->query("
    SELECT product_name, item_code, category, quantity_in_stock, cost, sales_price, (cost * quantity_in_stock) as valuation 
    FROM products 
    ORDER BY valuation DESC 
    LIMIT 10
")->fetchAll();
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shadow-sm sticky top-12 z-20">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Inventory Valuation & Stock Reporting</h1>
            <p class="text-xs text-gray-500">Asset valuation, category breakdown, and profit potential analysis</p>
        </div>
        <a href="index.php" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase">INVENTORY MASTER</a>
    </div>

    <!-- Main Container -->
    <main class="flex-1 p-6 max-w-7xl w-full mx-auto space-y-6">
        
        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Catalog Items</span>
                <span class="text-2xl font-extrabold text-gray-900 mt-1 block"><?php echo number_format($metrics['total_items']); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Units In Stock</span>
                <span class="text-2xl font-extrabold text-emerald-700 mt-1 block"><?php echo number_format($metrics['total_qty']); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Cost Valuation</span>
                <span class="text-2xl font-extrabold text-odoo-purple mt-1 block">AED <?php echo number_format($totalCost, 2); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Potential Sales Valuation</span>
                <span class="text-2xl font-extrabold text-blue-700 mt-1 block">AED <?php echo number_format($totalSales, 2); ?></span>
            </div>
        </div>

        <!-- Valuation Table -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Top Inventory Assets By Valuation</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold text-[11px] uppercase">
                            <th class="py-2.5 px-3">Item Code</th>
                            <th class="py-2.5 px-3">Product Name</th>
                            <th class="py-2.5 px-3">Category</th>
                            <th class="py-2.5 px-3 text-center">Qty In Stock</th>
                            <th class="py-2.5 px-3 text-right">Cost Price</th>
                            <th class="py-2.5 px-3 text-right">Selling Price</th>
                            <th class="py-2.5 px-3 text-right">Asset Valuation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <?php foreach ($topProducts as $p): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-2.5 px-3 font-mono text-xs text-gray-600"><?php echo htmlspecialchars($p['item_code'] ?? '-'); ?></td>
                                <td class="py-2.5 px-3 font-semibold text-gray-900"><?php echo htmlspecialchars($p['product_name']); ?></td>
                                <td class="py-2.5 px-3 text-gray-600"><?php echo htmlspecialchars($p['category'] ?? 'General'); ?></td>
                                <td class="py-2.5 px-3 text-center font-bold text-emerald-800"><?php echo floatval($p['quantity_in_stock']); ?></td>
                                <td class="py-2.5 px-3 text-right">AED <?php echo number_format($p['cost'], 2); ?></td>
                                <td class="py-2.5 px-3 text-right text-emerald-700">AED <?php echo number_format($p['sales_price'], 2); ?></td>
                                <td class="py-2.5 px-3 text-right font-bold text-odoo-purple">AED <?php echo number_format($p['valuation'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
