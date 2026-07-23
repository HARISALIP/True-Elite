<?php
$pageTitle = 'Vendor Master Data & Supplier Balances | True Elite ERP';
$moduleName = 'Purchase';
$subSection = 'master_data';
require_once '../includes/header.php';
require_once '../config/db.php';

// Fetch suppliers list with Total Spend, Payments, and Outstanding Balance
$suppliers = $pdo->query("
    SELECT s.*, 
           COUNT(dp.id) as total_vouchers, 
           IFNULL(SUM(dp.grand_total), 0) as total_spend,
           IFNULL(SUM(dp.grand_total), 0) as supplier_balance
    FROM suppliers s
    LEFT JOIN direct_purchases dp ON s.id = dp.supplier_id
    GROUP BY s.id
    ORDER BY s.supplier_name ASC
")->fetchAll();
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Bar -->
    <div class="bg-white border-b border-gray-200 px-6 py-2.5 flex items-center justify-between shadow-sm sticky top-12 z-20">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Vendors Directory & Supplier Balances</h1>
            <p class="text-xs text-gray-500">Supplier &rarr; Purchase Order &rarr; Purchase Invoice &rarr; Payment &rarr; Supplier Balance</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="direct_voucher.php" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase shadow-sm">NEW DIRECT VOUCHER</a>
            <a href="index.php" class="btn-secondary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase">PURCHASE TRANSACTIONS</a>
        </div>
    </div>

    <!-- Main Container -->
    <main class="flex-1 p-6 max-w-7xl w-full mx-auto">
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden w-full">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-[13px] border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase text-[11px]">
                        <tr>
                            <th class="py-3 px-4">Vendor ID</th>
                            <th class="py-3 px-4">Supplier Name ▾</th>
                            <th class="py-3 px-4">Contact Person</th>
                            <th class="py-3 px-4">Phone / Mobile</th>
                            <th class="py-3 px-4 text-center">Purchase Invoices ▾</th>
                            <th class="py-3 px-4 text-right">Total Invoiced Spend ▾</th>
                            <th class="py-3 px-4 text-right text-red-700 font-bold">Supplier Balance (Payable) ▾</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <?php if (empty($suppliers)): ?>
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">No vendors added yet. Vendors are created automatically when saving Direct Purchase Vouchers.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($suppliers as $s): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 font-mono text-gray-500">SUP-<?php echo str_pad($s['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td class="py-3 px-4 font-bold text-gray-900"><?php echo htmlspecialchars($s['supplier_name']); ?></td>
                                    <td class="py-3 px-4 text-gray-700"><?php echo htmlspecialchars($s['contact_person'] ?? 'Primary Contact'); ?></td>
                                    <td class="py-3 px-4 font-mono text-gray-600"><?php echo htmlspecialchars($s['phone'] ?? '+971 4 000 0000'); ?></td>
                                    <td class="py-3 px-4 text-center font-bold text-odoo-purple"><?php echo $s['total_vouchers']; ?></td>
                                    <td class="py-3 px-4 text-right font-semibold text-gray-800">AED <?php echo number_format($s['total_spend'], 2); ?></td>
                                    <td class="py-3 px-4 text-right font-bold text-red-700 bg-red-50/50">AED <?php echo number_format($s['supplier_balance'], 2); ?></td>
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
