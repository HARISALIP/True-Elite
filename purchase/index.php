<?php
$pageTitle = 'Purchase | True Elite Admin';
$moduleName = 'Purchase';
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<?php
$currentTab = $_GET['tab'] ?? 'transaction';
?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <?php if ($currentTab !== 'transaction'): ?>
        <!-- Coming Soon View for Master Data, Reports -->
        <main class="flex-1 p-8 flex items-center justify-center">
            <div class="bg-white p-12 rounded-sm shadow-sm border border-gray-200 text-center max-w-md w-full">
                <div class="w-16 h-16 bg-purple-100 text-[#714B67] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 uppercase tracking-wide mb-2"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $currentTab))); ?> Section</h2>
                <p class="text-gray-500 text-sm mb-6">This section is currently under development. Future updates will be deployed here.</p>
                <a href="index.php?tab=transaction" class="btn-primary px-5 py-2 rounded-sm text-sm font-medium inline-block">GO TO TRANSACTIONS</a>
            </div>
        </main>
    <?php else: ?>
        <!-- Action Toolbar (Identical to Sales List) -->
        <div class="bg-white border-b border-gray-200 px-4 py-2 flex items-center justify-between shadow-sm z-20 sticky top-12">
            <div class="flex items-center gap-2">
                <a href="direct_voucher.php" class="btn-primary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm inline-block uppercase">NEW DIRECT PURCHASE VOUCHER</a>
                <a href="../inventory/index.php" class="btn-secondary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm inline-block uppercase">INVENTORY MASTER</a>
            </div>
            
            <!-- Search and Counter -->
            <div class="flex items-center gap-4">
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="list-search" placeholder="Search..." class="w-64 pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded-sm focus:outline-none focus:border-odoo-purple focus:ring-1 focus:ring-odoo-purple" onkeyup="filterVouchers()">
                </div>
                
                <div class="flex items-center gap-1 text-sm text-gray-600">
                    <span id="counter-text">0-0 / 0</span>
                    <button class="p-1 hover:bg-gray-100 rounded-sm opacity-50 cursor-not-allowed"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                    <button class="p-1 hover:bg-gray-100 rounded-sm opacity-50 cursor-not-allowed"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                </div>
            </div>
        </div>

        <!-- Main Content List Sheet -->
        <main class="flex-1 p-4">
        <!-- KPI Summary Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div class="bg-white rounded-sm p-3.5 shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Purchase Vouchers</span>
                    <h3 id="stat-total-vouchers" class="text-xl font-bold text-gray-800 mt-0.5">0</h3>
                </div>
                <div class="w-9 h-9 rounded bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-sm p-3.5 shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Purchases Amount</span>
                    <h3 id="stat-total-amount" class="text-xl font-bold text-emerald-600 mt-0.5">AED 0.00</h3>
                </div>
                <div class="w-9 h-9 rounded bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-sm p-3.5 shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Active Suppliers</span>
                    <h3 id="stat-total-suppliers" class="text-xl font-bold text-indigo-600 mt-0.5">0</h3>
                </div>
                <div class="w-9 h-9 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap" id="list-table">
                    <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-medium cursor-pointer">
                        <tr>
                            <th class="px-4 py-2.5 w-10 cursor-default">
                                <input type="checkbox" class="rounded-sm border-gray-300 text-odoo-purple focus:ring-odoo-purple">
                            </th>
                            <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors">Voucher Number ▾</th>
                            <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors">Supplier ▾</th>
                            <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors">Purchase Date ▾</th>
                            <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors">Supplier Ref Invoice ▾</th>
                            <th class="px-4 py-2.5 text-center hover:bg-gray-100 transition-colors">Items Count ▾</th>
                            <th class="px-4 py-2.5 text-right hover:bg-gray-100 transition-colors">Total ▾</th>
                            <th class="px-4 py-2.5 text-right hover:bg-gray-100 transition-colors">Stock Status ▾</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700" id="list-body">
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">Loading purchase vouchers...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php endif; ?>
</div>

<script>
let voucherList = [];

document.addEventListener('DOMContentLoaded', () => {
    fetch('../api/purchases.php?action=get_vouchers')
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                voucherList = res.data || [];
                renderVouchers(voucherList);
            }
        });
});

function renderVouchers(vouchers) {
    const tbody = document.getElementById('list-body');
    const counterText = document.getElementById('counter-text');

    updatePurchaseStats(vouchers || []);

    if (!vouchers || vouchers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">No purchase vouchers found. Create a new one to get started.</td></tr>`;
        counterText.textContent = "0-0 / 0";
        return;
    }

    counterText.textContent = `1-${vouchers.length} / ${vouchers.length}`;

    tbody.innerHTML = vouchers.map(v => {
        const total = parseFloat(v.grand_total || 0).toFixed(2);
        return `
            <tr class="hover:bg-gray-50 cursor-pointer border-b border-gray-100">
                <td class="px-4 py-2.5" onclick="event.stopPropagation()">
                    <input type="checkbox" class="rounded-sm border-gray-300 text-odoo-purple focus:ring-odoo-purple">
                </td>
                <td class="px-4 py-2.5 font-medium text-gray-900">${escapeHtml(v.voucher_number)}</td>
                <td class="px-4 py-2.5 font-medium">${escapeHtml(v.supplier_name || 'General Supplier')}</td>
                <td class="px-4 py-2.5">${escapeHtml(v.purchase_date)}</td>
                <td class="px-4 py-2.5 font-mono text-xs">${escapeHtml(v.reference_no || '-')}</td>
                <td class="px-4 py-2.5 text-center font-medium">${v.item_count || 1}</td>
                <td class="px-4 py-2.5 text-right font-medium text-gray-900">${total}</td>
                <td class="px-4 py-2.5 text-right">
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-medium tracking-wide uppercase bg-green-100 text-green-800">
                        Added to Stock
                    </span>
                </td>
            </tr>
        `;
    }).join('');
}

function filterVouchers() {
    const q = document.getElementById('list-search').value.toLowerCase();
    const filtered = voucherList.filter(v => 
        (v.voucher_number && v.voucher_number.toLowerCase().includes(q)) ||
        (v.supplier_name && v.supplier_name.toLowerCase().includes(q)) ||
        (v.reference_no && v.reference_no.toLowerCase().includes(q))
    );
    renderVouchers(filtered);
}

function updatePurchaseStats(vouchers) {
    let totalVouchers = vouchers.length;
    let totalAmount = 0;
    let suppliersSet = new Set();

    vouchers.forEach(v => {
        totalAmount += parseFloat(v.grand_total || 0);
        if (v.supplier_name) suppliersSet.add(v.supplier_name.trim());
    });

    document.getElementById('stat-total-vouchers').textContent = totalVouchers;
    document.getElementById('stat-total-amount').textContent = 'AED ' + totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('stat-total-suppliers').textContent = suppliersSet.size;
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>

<?php require_once '../includes/footer.php'; ?>
