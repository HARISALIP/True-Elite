<?php
$pageTitle = 'Accounting & VAT Reporting | True Elite ERP';
$moduleName = 'Accounting';
$subSection = 'reporting';
require_once '../includes/header.php';
require_once '../config/db.php';

// Calculate financial figures with exact schema column names (workflow_status, grand_total)
$totalSalesRevenue = $pdo->query("SELECT IFNULL(SUM(grand_total), 0) FROM quotations WHERE workflow_status = 'Sales Order'")->fetchColumn();
$salesVatTotal = $totalSalesRevenue * 0.05; // 5% Output VAT

$totalPurchaseExpenses = $pdo->query("SELECT IFNULL(SUM(grand_total), 0) FROM direct_purchases")->fetchColumn();
$purchaseVatTotal = $pdo->query("SELECT IFNULL(SUM(tax_total), 0) FROM direct_purchases")->fetchColumn(); // 5% Input VAT

$netOperatingProfit = $totalSalesRevenue - $totalPurchaseExpenses;
$netVatPayable = $salesVatTotal - $purchaseVatTotal;
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Bar -->
    <div class="bg-white border-b border-gray-200 px-6 py-2.5 flex items-center justify-between shadow-sm sticky top-12 z-20">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Financial Reporting & UAE 5% VAT Statement</h1>
            <p class="text-xs text-gray-500">Sales + Purchases + Expenses &rarr; Accounts &rarr; Profit & Loss &rarr; VAT Return</p>
        </div>
        <a href="index.php" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase shadow-sm">FINANCIAL OVERVIEW</a>
    </div>

    <!-- Main Container -->
    <main class="flex-1 p-6 max-w-5xl w-full mx-auto space-y-6">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Sales Revenue</span>
                <span class="text-xl font-extrabold text-emerald-700 mt-1 block">AED <?php echo number_format($totalSalesRevenue, 2); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Purchase Expenses</span>
                <span class="text-xl font-extrabold text-red-700 mt-1 block">AED <?php echo number_format($totalPurchaseExpenses, 2); ?></span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Net Operating Profit</span>
                <span class="text-xl font-extrabold <?php echo $netOperatingProfit >= 0 ? 'text-odoo-purple' : 'text-red-800'; ?> mt-1 block">
                    AED <?php echo number_format($netOperatingProfit, 2); ?>
                </span>
            </div>

            <div class="bg-white rounded-sm p-4 border border-gray-200 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Net VAT Payable (FTA)</span>
                <span class="text-xl font-extrabold text-blue-700 mt-1 block">AED <?php echo number_format($netVatPayable, 2); ?></span>
            </div>
        </div>

        <!-- Profit & Loss Summary Card -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">1. Executive Profit & Loss Summary Statement</h3>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-100 font-semibold">
                    <span class="text-gray-700">Gross Sales Income (Sales Revenue)</span>
                    <span class="text-emerald-700">AED <?php echo number_format($totalSalesRevenue, 2); ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 font-semibold">
                    <span class="text-gray-700">Cost of Goods & Direct Purchases</span>
                    <span class="text-red-700">- AED <?php echo number_format($totalPurchaseExpenses, 2); ?></span>
                </div>
                <div class="flex justify-between py-3 border-t-2 border-gray-900 font-extrabold text-base">
                    <span class="text-gray-900 uppercase">Net Operating Profit</span>
                    <span class="<?php echo $netOperatingProfit >= 0 ? 'text-odoo-purple' : 'text-red-700'; ?>">AED <?php echo number_format($netOperatingProfit, 2); ?></span>
                </div>
            </div>
        </div>

        <!-- UAE 5% VAT Return Card -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">2. UAE Federal Tax Authority (FTA) 5% VAT Return</h3>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-100 font-semibold">
                    <span class="text-gray-700">5% Output VAT (Collected on Sales)</span>
                    <span class="text-emerald-700">AED <?php echo number_format($salesVatTotal, 2); ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 font-semibold">
                    <span class="text-gray-700">5% Input VAT (Paid on Purchases & Expenses)</span>
                    <span class="text-blue-700">- AED <?php echo number_format($purchaseVatTotal, 2); ?></span>
                </div>
                <div class="flex justify-between py-3 border-t-2 border-gray-900 font-extrabold text-base">
                    <span class="text-gray-900 uppercase">Net VAT Payable / (Refundable)</span>
                    <span class="text-blue-800">AED <?php echo number_format($netVatPayable, 2); ?></span>
                </div>
            </div>
        </div>

    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
