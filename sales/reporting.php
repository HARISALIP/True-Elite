<?php
$pageTitle = 'Sales Reporting | True Elite ERP';
$moduleName = 'Sales';
$subSection = 'reporting';
require_once '../includes/header.php';
require_once '../config/db.php';

// Fetch sales stats
$totalQuotations = $pdo->query("SELECT COUNT(*) FROM quotations")->fetchColumn();
$totalSalesOrders = $pdo->query("SELECT COUNT(*) FROM quotations WHERE status = 'Sales Order'")->fetchColumn();
$totalRevenue = $pdo->query("SELECT IFNULL(SUM(total), 0) FROM quotations WHERE status = 'Sales Order'")->fetchColumn();
$avgOrderValue = $totalSalesOrders > 0 ? ($totalRevenue / $totalSalesOrders) : 0;

// Recent quotations list
$recentQuotations = $pdo->query("
    SELECT quotation_number, customer_name, quotation_date, total, status 
    FROM quotations 
    ORDER BY created_at DESC 
    LIMIT 10
")->fetchAll();
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Bar -->
    <div class="bg-white border-b border-gray-200 px-6 py-2.5 flex items-center justify-between shadow-sm sticky top-12 z-20">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Sales Analytics & Revenue Reports</h1>
            <p class="text-xs text-gray-500">Sales order volume, total revenue, and quotation conversion performance</p>
        </div>
        <a href="quotations.php" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase shadow-sm">QUOTATIONS & ORDERS</a>
    </div>

    <!-- Main Container -->
    <main class="flex-1 p-6 max-w-7xl w-full mx-auto space-y-6">
        
        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Confirmed Revenue (AED)</span>
                <span class="text-2xl font-extrabold text-emerald-700 mt-1 block">AED <?php echo number_format($totalRevenue, 2); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Quotations Issued</span>
                <span class="text-2xl font-extrabold text-gray-900 mt-1 block"><?php echo number_format($totalQuotations); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Confirmed Sales Orders</span>
                <span class="text-2xl font-extrabold text-odoo-purple mt-1 block"><?php echo number_format($totalSalesOrders); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Average Order Value</span>
                <span class="text-2xl font-extrabold text-blue-700 mt-1 block">AED <?php echo number_format($avgOrderValue, 2); ?></span>
            </div>
        </div>

        <!-- Recent Quotations & Orders Table -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Recent Sales Transactions</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold text-[11px] uppercase">
                            <th class="py-2.5 px-4">Quotation No.</th>
                            <th class="py-2.5 px-4">Customer Name</th>
                            <th class="py-2.5 px-4">Date</th>
                            <th class="py-2.5 px-4 text-center">Status</th>
                            <th class="py-2.5 px-4 text-right">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <?php if (empty($recentQuotations)): ?>
                            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No sales transactions recorded yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentQuotations as $q): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 px-4 font-mono font-bold text-odoo-purple"><?php echo htmlspecialchars($q['quotation_number']); ?></td>
                                    <td class="py-2.5 px-4 font-semibold text-gray-900"><?php echo htmlspecialchars($q['customer_name'] ?? 'Walk-in Customer'); ?></td>
                                    <td class="py-2.5 px-4 text-gray-600"><?php echo date('d M Y', strtotime($q['quotation_date'])); ?></td>
                                    <td class="py-2.5 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold <?php echo $q['status'] === 'Sales Order' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800'; ?>">
                                            <?php echo htmlspecialchars($q['status']); ?>
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4 text-right font-bold text-gray-900">AED <?php echo number_format($q['total'], 2); ?></td>
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
