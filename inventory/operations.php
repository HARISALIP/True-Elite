<?php
$pageTitle = 'Inventory Operations | True Elite ERP';
$moduleName = 'Inventory';
$subSection = 'operations';
require_once '../includes/header.php';
require_once '../config/db.php';

// Fetch stock movements with product details
$stmt = $pdo->query("
    SELECT sm.*, p.product_name, p.item_code, p.category 
    FROM stock_movements sm
    LEFT JOIN products p ON sm.product_id = p.id
    ORDER BY sm.created_at DESC
");
$movements = $stmt->fetchAll();
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Toolbar -->
    <div class="bg-white border-b border-gray-200 px-6 py-2.5 flex items-center justify-between shadow-sm z-20 sticky top-12">
        <div class="flex items-center gap-3">
            <h1 class="text-lg font-bold text-gray-900">Inventory Operations & Stock Transfers</h1>
            <span class="bg-purple-100 text-odoo-purple px-2.5 py-0.5 rounded-full text-xs font-semibold"><?php echo count($movements); ?> Records</span>
        </div>

        <div class="flex items-center gap-2">
            <a href="index.php" class="btn-primary px-3 py-1.5 rounded-sm text-xs font-medium uppercase">MANAGE INVENTORY</a>
            <a href="../purchase/direct_voucher.php" class="btn-secondary px-3 py-1.5 rounded-sm text-xs font-medium uppercase">NEW STOCK RECEIPT</a>
        </div>
    </div>

    <!-- Main List Container -->
    <main class="flex-1 p-4">
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden w-full">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-[13px] border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold cursor-pointer text-[12px]">
                        <tr>
                            <th class="px-3 py-2.5">Date & Time ▾</th>
                            <th class="px-3 py-2.5">Item Code ▾</th>
                            <th class="px-3 py-2.5">Product Name ▾</th>
                            <th class="px-3 py-2.5">Category ▾</th>
                            <th class="px-3 py-2.5 text-center">Movement Type ▾</th>
                            <th class="px-3 py-2.5 text-center">Quantity ▾</th>
                            <th class="px-3 py-2.5">Reference No ▾</th>
                            <th class="px-3 py-2.5">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <?php if (empty($movements)): ?>
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">No stock movements recorded yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($movements as $m): ?>
                                <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                                    <td class="px-3 py-2.5 text-gray-600 font-mono text-[11px]"><?php echo date('d M Y, h:i A', strtotime($m['created_at'])); ?></td>
                                    <td class="px-3 py-2.5 font-mono text-gray-600 text-[11px]"><?php echo htmlspecialchars($m['item_code'] ?? '-'); ?></td>
                                    <td class="px-3 py-2.5 font-semibold text-gray-900"><?php echo htmlspecialchars($m['product_name'] ?? 'Product #' . $m['product_id']); ?></td>
                                    <td class="px-3 py-2.5 text-gray-600"><?php echo htmlspecialchars($m['category'] ?? 'General'); ?></td>
                                    <td class="px-3 py-2.5 text-center">
                                        <?php if ($m['movement_type'] === 'STOCK_IN'): ?>
                                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[11px]">STOCK IN</span>
                                        <?php else: ?>
                                            <span class="bg-red-100 text-red-800 font-bold px-2 py-0.5 rounded-full text-[11px]">STOCK OUT</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-2.5 text-center font-mono font-bold text-sm <?php echo $m['movement_type'] === 'STOCK_IN' ? 'text-emerald-700' : 'text-red-700'; ?>">
                                        <?php echo ($m['movement_type'] === 'STOCK_IN' ? '+' : '-') . floatval($m['quantity']); ?>
                                    </td>
                                    <td class="px-3 py-2.5 font-mono text-gray-700 text-[11px]"><?php echo htmlspecialchars($m['reference_no'] ?? '-'); ?></td>
                                    <td class="px-3 py-2.5 text-gray-500 text-[11px]"><?php echo htmlspecialchars($m['notes'] ?? '-'); ?></td>
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
