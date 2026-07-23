<?php
$pageTitle = 'Inventory Overview | True Elite ERP';
$moduleName = 'Inventory';
$subSection = 'overview';
require_once '../includes/header.php';
require_once '../config/db.php';

// Fetch stats for overview cards & dashboard KPI items
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$lowStockCount = $pdo->query("SELECT COUNT(*) FROM products WHERE quantity_in_stock <= min_stock_alert")->fetchColumn();
$totalQtyInStock = $pdo->query("SELECT IFNULL(SUM(quantity_in_stock), 0) FROM products")->fetchColumn();
$stockValuation = $pdo->query("SELECT IFNULL(SUM(cost * quantity_in_stock), 0) FROM products")->fetchColumn();

$totalDirectPurchases = $pdo->query("SELECT COUNT(*) FROM direct_purchases")->fetchColumn();
$totalMovements = $pdo->query("SELECT COUNT(*) FROM stock_movements")->fetchColumn();
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Bar -->
    <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shadow-sm sticky top-12 z-20">
        <div>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Inventory Overview</h1>
            <p class="text-xs text-gray-500">Warehouse operations, stock receipts, and delivery orders summary</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="../purchase/direct_voucher.php" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase shadow-sm">CREATE RECEIPT</a>
            <a href="index.php" class="btn-secondary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase">PRODUCTS MASTER</a>
        </div>
    </div>

    <!-- Main Overview Kanban & KPI Grid -->
    <main class="flex-1 p-6 max-w-7xl w-full mx-auto space-y-6">
        
        <!-- Top 4 KPI Dashboard Stat Cards (Moved from Products page) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-sm p-4 shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Total Items</span>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-0.5"><?php echo number_format($totalProducts); ?></h3>
                </div>
                <div class="w-10 h-10 rounded bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-sm p-4 shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Low Stock Alerts</span>
                    <h3 class="text-2xl font-extrabold text-red-600 mt-0.5"><?php echo number_format($lowStockCount); ?></h3>
                </div>
                <div class="w-10 h-10 rounded bg-red-50 text-red-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-sm p-4 shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Total Qty In Stock</span>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-0.5"><?php echo number_format($totalQtyInStock); ?></h3>
                </div>
                <div class="w-10 h-10 rounded bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-sm p-4 shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Stock Valuation (Cost)</span>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-0.5">AED <?php echo number_format($stockValuation, 2); ?></h3>
                </div>
                <div class="w-10 h-10 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Operations Kanban Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Receipts Card (Stock In) -->
            <div class="bg-white rounded-sm border border-gray-200 shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Receipts (Stock In)
                    </h3>
                    <span class="text-xs text-gray-400 font-mono">PURCHASES</span>
                </div>
                <div class="flex items-baseline justify-between mb-4">
                    <div>
                        <span class="text-3xl font-extrabold text-odoo-purple"><?php echo $totalDirectPurchases; ?></span>
                        <span class="text-xs text-gray-500 block mt-1">Processed Purchase Receipts</span>
                    </div>
                    <a href="../purchase/direct_voucher.php" class="btn-primary px-3 py-1 text-xs rounded-sm">1 TO PROCESS</a>
                </div>
                <div class="pt-3 border-t border-gray-50 flex justify-between text-xs text-gray-600">
                    <span>Direct Purchase Vouchers</span>
                    <a href="../purchase/index.php" class="text-odoo-purple font-semibold hover:underline">View All &rarr;</a>
                </div>
            </div>

            <!-- Delivery Orders Card (Stock Out) -->
            <div class="bg-white rounded-sm border border-gray-200 shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span> Delivery Orders
                    </h3>
                    <span class="text-xs text-gray-400 font-mono">SALES</span>
                </div>
                <div class="flex items-baseline justify-between mb-4">
                    <div>
                        <span class="text-3xl font-extrabold text-blue-700"><?php echo $totalMovements; ?></span>
                        <span class="text-xs text-gray-500 block mt-1">Total Movement Transfers</span>
                    </div>
                    <a href="../sales/quotations.php" class="btn-secondary px-3 py-1 text-xs rounded-sm">FULFILL ORDERS</a>
                </div>
                <div class="pt-3 border-t border-gray-50 flex justify-between text-xs text-gray-600">
                    <span>Sales Orders & Quotations</span>
                    <a href="../sales/quotations.php" class="text-blue-700 font-semibold hover:underline">View Sales &rarr;</a>
                </div>
            </div>

            <!-- Low Stock & Physical Inventory Card -->
            <div class="bg-white rounded-sm border border-gray-200 shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span> Physical Inventory
                    </h3>
                    <span class="text-xs text-gray-400 font-mono">PRODUCTS</span>
                </div>
                <div class="flex items-baseline justify-between mb-4">
                    <div>
                        <span class="text-3xl font-extrabold text-amber-700"><?php echo $totalProducts; ?></span>
                        <span class="text-xs text-gray-500 block mt-1">Catalog Item Codes</span>
                    </div>
                    <a href="index.php" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 text-xs rounded-sm font-semibold">VIEW MASTER</a>
                </div>
                <div class="pt-3 border-t border-gray-50 flex justify-between text-xs text-gray-600">
                    <span>Low Stock Items: <b class="text-red-600"><?php echo $lowStockCount; ?></b></span>
                    <a href="index.php" class="text-amber-800 font-semibold hover:underline">Manage Stock &rarr;</a>
                </div>
            </div>

        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
