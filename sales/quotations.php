<?php
$pageTitle = 'Quotations | True Elite Admin';
$moduleName = 'Sales';
require_once '../includes/header.php';
require_once '../config/db.php';

try {
    // Clean up old demo/test records
    $pdo->exec("DELETE FROM quotations WHERE quotation_number NOT LIKE 'TEK-%'");

    $stmt = $pdo->query("
        SELECT q.id, q.quotation_number, q.quotation_date, q.created_at, c.customer_name, u.name as salesperson, q.grand_total, q.workflow_status 
        FROM quotations q
        LEFT JOIN customers c ON q.customer_id = c.id
        LEFT JOIN users u ON q.salesperson_id = u.id
        ORDER BY q.created_at DESC
    ");
    $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $quotes = [];
}
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Toolbar -->
    <div class="bg-white border-b border-gray-200 px-4 py-2 flex items-center justify-between shadow-sm z-20 sticky top-12">
        <div class="flex items-center gap-2">
            <a href="index.php" class="btn-primary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm inline-block">NEW</a>
            <button type="button" class="btn-secondary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm">GENERATE INVOICES</button>
        </div>
        
        <!-- Search and Filters -->
        <div class="flex items-center gap-4">
            <div class="relative">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="list-search" placeholder="Search..." class="w-64 pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded-sm focus:outline-none focus:border-odoo-purple focus:ring-1 focus:ring-odoo-purple" onkeyup="filterList()">
            </div>
            
            <div class="flex items-center gap-1 text-sm text-gray-600">
                <span>1-3 / 3</span>
                <button class="p-1 hover:bg-gray-100 rounded-sm"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button class="p-1 hover:bg-gray-100 rounded-sm"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-4">
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm whitespace-nowrap" id="list-table">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-medium cursor-pointer">
                    <tr>
                        <th class="px-4 py-2.5 w-10 cursor-default">
                            <input type="checkbox" class="rounded-sm border-gray-300 text-odoo-purple focus:ring-odoo-purple">
                        </th>
                        <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors" onclick="sortTable(1)">Quotation Number ▾</th>
                        <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors" onclick="sortTable(2)">Quotation Date ▾</th>
                        <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors" onclick="sortTable(3)">Customer ▾</th>
                        <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors" onclick="sortTable(4)">Salesperson ▾</th>
                        <th class="px-4 py-2.5 text-right hover:bg-gray-100 transition-colors" onclick="sortTable(5)">Total ▾</th>
                        <th class="px-4 py-2.5 text-right hover:bg-gray-100 transition-colors" onclick="sortTable(6)">Status ▾</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700" id="list-body">
                    <?php if (empty($quotes)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No quotations found. Create a new one to get started.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($quotes as $quote): ?>
                            <tr class="table-row-hover" onclick="window.location.href='index.php?id=<?php echo $quote['quotation_number']; ?>'">
                                <td class="px-4 py-2.5" onclick="event.stopPropagation()">
                                    <input type="checkbox" class="rounded-sm border-gray-300 text-odoo-purple focus:ring-odoo-purple">
                                </td>
                                <td class="px-4 py-2.5 font-medium text-gray-900"><?php echo htmlspecialchars($quote['quotation_number']); ?></td>
                                <td class="px-4 py-2.5"><?php echo date('Y-m-d H:i', strtotime($quote['created_at'])); ?></td>
                                <td class="px-4 py-2.5 font-medium"><?php echo htmlspecialchars($quote['customer_name'] ?? 'Unknown'); ?></td>
                                <td class="px-4 py-2.5"><?php echo htmlspecialchars($quote['salesperson'] ?? 'Unknown'); ?></td>
                                <td class="px-4 py-2.5 text-right font-medium"><?php echo number_format($quote['grand_total'], 2); ?></td>
                                <td class="px-4 py-2.5 text-right">
                                    <?php 
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        if ($quote['workflow_status'] === 'Quotation Sent') $statusClass = 'bg-yellow-100 text-yellow-800';
                                        if ($quote['workflow_status'] === 'Sales Order') $statusClass = 'bg-green-100 text-green-800';
                                        if ($quote['workflow_status'] === 'Cancelled') $statusClass = 'bg-red-100 text-red-800';
                                    ?>
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-medium tracking-wide uppercase <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($quote['workflow_status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>

<!-- Load List Interactivity Script -->
<script src="../assets/js/list.js"></script>
