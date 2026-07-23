<?php
$pageTitle = 'Purchase Reports | True Elite ERP';
$moduleName = 'Purchase';
$subSection = 'reports';
require_once '../includes/header.php';
require_once '../config/db.php';

// Fetch purchase spend analytics
$totalVouchers = $pdo->query("SELECT COUNT(*) FROM direct_purchases")->fetchColumn();
$totalSpend = $pdo->query("SELECT IFNULL(SUM(grand_total), 0) FROM direct_purchases")->fetchColumn();
$totalItemsPurchased = $pdo->query("SELECT IFNULL(SUM(quantity), 0) FROM direct_purchase_items")->fetchColumn();
$avgVoucherValue = $totalVouchers > 0 ? ($totalSpend / $totalVouchers) : 0;

// Top suppliers by spend
$topVendors = $pdo->query("
    SELECT supplier_name, COUNT(id) as voucher_count, SUM(grand_total) as total_spend 
    FROM direct_purchases 
    GROUP BY supplier_name 
    ORDER BY total_spend DESC 
    LIMIT 10
")->fetchAll();
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Bar -->
    <div class="bg-white border-b border-gray-200 px-6 py-2.5 flex items-center justify-between shadow-sm sticky top-12 z-20">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Purchase Analytics & Procurement Reports</h1>
            <p class="text-xs text-gray-500">Expenditure tracking, vendor allocation, and order volume metrics</p>
        </div>
        <a href="index.php" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase shadow-sm">PURCHASE ORDERS</a>
    </div>

    <!-- Main Content Container -->
    <main class="flex-1 p-6 max-w-7xl w-full mx-auto space-y-6">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Spend (AED)</span>
                <span class="text-2xl font-extrabold text-odoo-purple mt-1 block">AED <?php echo number_format($totalSpend, 2); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Vouchers Issued</span>
                <span class="text-2xl font-extrabold text-gray-900 mt-1 block"><?php echo number_format($totalVouchers); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Units Purchased</span>
                <span class="text-2xl font-extrabold text-emerald-700 mt-1 block"><?php echo number_format($totalItemsPurchased); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Average Voucher Value</span>
                <span class="text-2xl font-extrabold text-blue-700 mt-1 block">AED <?php echo number_format($avgVoucherValue, 2); ?></span>
            </div>
        </div>

        <!-- Vendor Allocation Table -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Top Vendors By Spend Volume</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold text-[11px] uppercase">
                            <th class="py-2.5 px-4">Vendor Name</th>
                            <th class="py-2.5 px-4 text-center">Vouchers Issued</th>
                            <th class="py-2.5 px-4 text-right">Total Expenditure</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <?php if (empty($topVendors)): ?>
                            <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">No vendor purchase transactions recorded yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($topVendors as $v): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 px-4 font-bold text-gray-900"><?php echo htmlspecialchars($v['supplier_name']); ?></td>
                                    <td class="py-2.5 px-4 text-center font-bold text-odoo-purple"><?php echo $v['voucher_count']; ?></td>
                                    <td class="py-2.5 px-4 text-right font-bold text-emerald-700">AED <?php echo number_format($v['total_spend'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
