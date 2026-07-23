<?php
$pageTitle = 'Accounting Configuration | True Elite ERP';
$moduleName = 'Accounting';
$subSection = 'configuration';
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Bar -->
    <div class="bg-white border-b border-gray-200 px-6 py-2.5 flex items-center justify-between shadow-sm sticky top-12 z-20">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Chart of Accounts & Financial Configuration</h1>
            <p class="text-xs text-gray-500">Configure General Ledger accounts, fiscal year, and currency defaults</p>
        </div>
        <a href="index.php" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase shadow-sm">FINANCIAL OVERVIEW</a>
    </div>

    <!-- Main Container -->
    <main class="flex-1 p-6 max-w-5xl w-full mx-auto space-y-6">
        
        <!-- Chart of Accounts Summary Card -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">General Ledger Chart of Accounts</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase text-[11px]">
                            <th class="py-2.5 px-3">Account Code</th>
                            <th class="py-2.5 px-3">Account Name</th>
                            <th class="py-2.5 px-3">Type</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <tr class="hover:bg-gray-50"><td class="py-2.5 px-3 font-mono font-bold text-odoo-purple">101000</td><td class="py-2.5 px-3 font-semibold">Bank Accounts (AED)</td><td class="py-2.5 px-3 text-gray-600">Current Asset</td><td class="py-2.5 px-3 text-center"><span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full text-[11px] font-bold">Active</span></td></tr>
                        <tr class="hover:bg-gray-50"><td class="py-2.5 px-3 font-mono font-bold text-odoo-purple">102000</td><td class="py-2.5 px-3 font-semibold">Accounts Receivable (Customers)</td><td class="py-2.5 px-3 text-gray-600">Current Asset</td><td class="py-2.5 px-3 text-center"><span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full text-[11px] font-bold">Active</span></td></tr>
                        <tr class="hover:bg-gray-50"><td class="py-2.5 px-3 font-mono font-bold text-odoo-purple">201000</td><td class="py-2.5 px-3 font-semibold">Accounts Payable (Vendors)</td><td class="py-2.5 px-3 text-gray-600">Current Liability</td><td class="py-2.5 px-3 text-center"><span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full text-[11px] font-bold">Active</span></td></tr>
                        <tr class="hover:bg-gray-50"><td class="py-2.5 px-3 font-mono font-bold text-odoo-purple">400000</td><td class="py-2.5 px-3 font-semibold">Product Sales Income</td><td class="py-2.5 px-3 text-gray-600">Operating Revenue</td><td class="py-2.5 px-3 text-center"><span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full text-[11px] font-bold">Active</span></td></tr>
                        <tr class="hover:bg-gray-50"><td class="py-2.5 px-3 font-mono font-bold text-odoo-purple">500000</td><td class="py-2.5 px-3 font-semibold">Cost of Goods Sold & Direct Purchases</td><td class="py-2.5 px-3 text-gray-600">Cost of Sales</td><td class="py-2.5 px-3 text-center"><span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full text-[11px] font-bold">Active</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
