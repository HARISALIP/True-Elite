<?php
$pageTitle = 'Direct Purchase Voucher | True Elite ERP';
$moduleName = 'Purchase';
require_once '../includes/header.php';
?>
<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<main class="pt-20 min-h-screen bg-[#F0EFF5] px-4 md:px-6 pb-8">
    <div class="max-w-6xl mx-auto">
        <!-- Top Toolbar with Odoo Status Pipeline -->
        <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3 border border-gray-200 rounded-sm shadow-sm">
            <div class="flex items-center gap-2">
                <button type="button" onclick="savePurchaseVoucher()" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase shadow-sm">SAVE & STOCK IN</button>
                <button type="button" onclick="window.print()" class="btn-secondary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase">PRINT VOUCHER</button>
                <a href="index.php" class="px-3.5 py-1.5 border border-gray-300 rounded-sm text-xs font-semibold text-gray-600 hover:bg-gray-50 uppercase">CANCEL</a>
            </div>

            <!-- Odoo Status Pipeline Widget -->
            <div class="flex items-center text-[11px] font-bold uppercase tracking-wider">
                <span class="bg-[#714B67] text-white px-3 py-1 rounded-l-sm flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span> RFQ
                </span>
                <span class="bg-gray-100 text-gray-600 border-y border-gray-200 px-3 py-1">RFQ SENT</span>
                <span class="bg-gray-100 text-gray-600 border border-gray-200 px-3 py-1 rounded-r-sm">PURCHASE ORDER</span>
            </div>
        </div>

        <!-- Paper Form Container -->
        <div class="bg-white rounded-xl border border-gray-300 shadow-sm p-6 md:p-8">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Purchase Voucher</span>
                        <h2 id="display-voucher-no" class="text-3xl font-extrabold text-gray-900 mt-1">PV-26-0001</h2>
                    </div>
                    <div class="bg-orange-50 border border-orange-200 text-orange-800 px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Stock added to inventory immediately upon saving
                    </div>
                </div>
            </div>

            <!-- Odoo 2-Column Form Header -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Vendor *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <select id="supplier-select" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-xs outline-none focus:ring-1 focus:ring-odoo-purple bg-white">
                                <option value="">-- Select Vendor --</option>
                            </select>
                            <input type="text" id="new-supplier-name" placeholder="Or New Vendor Name" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-xs outline-none focus:ring-1 focus:ring-odoo-purple">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Vendor Reference *</label>
                        <input type="text" id="reference-no" placeholder="e.g. INV-99238" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-xs outline-none focus:ring-1 focus:ring-odoo-purple">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Currency</label>
                        <input type="text" value="AED" readonly class="w-full px-3 py-1.5 border border-gray-200 bg-gray-50 rounded-sm text-xs font-semibold text-gray-700 outline-none">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Order Deadline *</label>
                        <input type="date" id="purchase-date" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-xs outline-none focus:ring-1 focus:ring-odoo-purple bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Expected Arrival *</label>
                        <input type="date" id="expected-arrival" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-xs outline-none focus:ring-1 focus:ring-odoo-purple bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Payment Terms</label>
                        <select id="payment-terms" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-xs outline-none focus:ring-1 focus:ring-odoo-purple bg-white">
                            <option value="Immediate">Immediate Cash/Bank</option>
                            <option value="Net 15">Net 15 Days</option>
                            <option value="Net 30">Net 30 Days</option>
                            <option value="Net 60">Net 60 Days</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tab Headers Bar -->
            <div class="border-b border-gray-200 mb-4 flex items-center gap-6">
                <button type="button" class="border-b-2 border-odoo-purple text-odoo-purple font-bold text-xs py-2 px-1">Products</button>
                <button type="button" class="text-gray-500 hover:text-gray-800 text-xs py-2 px-1">Other Information</button>
            </div>

            <!-- Items Table Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Purchase Items & Instant Stock In</h3>
                    <button type="button" onclick="addPurchasedRow()" class="text-xs btn-primary px-3 py-1 rounded-sm font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add a product
                    </button>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-left text-xs md:text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase text-[11px]">
                                <th class="py-3 px-3 w-64">Item Name *</th>
                                <th class="py-3 px-3 w-32">Item Code</th>
                                <th class="py-3 px-3 w-32">Category</th>
                                <th class="py-3 px-3 w-24 text-center">Qty Purchased *</th>
                                <th class="py-3 px-3 w-28 text-right">Purchase Price *</th>
                                <th class="py-3 px-3 w-28 text-right">Selling Price</th>
                                <th class="py-3 px-3 w-40">Equipment Serial No.</th>
                                <th class="py-3 px-3 w-28 text-right">Subtotal</th>
                                <th class="py-3 px-3 w-12 text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="purchase-items-tbody" class="divide-y divide-gray-200">
                            <!-- Dynamic Rows -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes & Totals Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-200 pt-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Internal Notes & Terms</label>
                    <textarea id="purchase-notes" rows="4" placeholder="Enter additional receipt details, warranty terms, or notes..." class="w-full p-3 border border-gray-300 rounded-md text-sm outline-none focus:ring-2 focus:ring-[#714B67]"></textarea>
                </div>

                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 space-y-3">
                    <div class="flex justify-between text-sm text-gray-600 font-medium">
                        <span>Subtotal:</span>
                        <span id="summary-subtotal" class="font-bold text-gray-900">AED 0.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 font-medium">
                        <span>Estimated VAT (5%):</span>
                        <span id="summary-vat" class="font-bold text-gray-900">AED 0.00</span>
                    </div>
                    <div class="flex justify-between text-lg text-gray-900 font-extrabold border-t border-gray-300 pt-3">
                        <span>Grand Total:</span>
                        <span id="summary-grand-total" class="text-[#714B67]">AED 0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let existingProducts = [];
let voucherNumber = '';

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('purchase-date').valueAsDate = new Date();
    fetchNewVoucherNumber();
    fetchSuppliers();
    fetchProducts();
});

function fetchNewVoucherNumber() {
    fetch('../api/purchases.php?action=get_new_voucher_number')
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                voucherNumber = res.data.voucher_number;
                document.getElementById('display-voucher-no').textContent = voucherNumber;
            }
        });
}

function fetchSuppliers() {
    fetch('../api/purchases.php?action=get_suppliers')
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const select = document.getElementById('supplier-select');
                select.innerHTML = '<option value="">-- Select Supplier --</option>' + 
                    res.data.map(s => `<option value="${s.id}">${s.supplier_name}</option>`).join('');
            }
        });
}

function fetchProducts() {
    fetch('../api/products.php')
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                existingProducts = Object.values(res.data || {});
                // Add initial empty row
                addPurchasedRow();
            }
        });
}

function addPurchasedRow() {
    const tbody = document.getElementById('purchase-items-tbody');
    const rowId = 'row_' + Date.now() + '_' + Math.random().toString(36).substr(2, 4);

    const tr = document.createElement('tr');
    tr.id = rowId;
    tr.className = 'hover:bg-gray-50';

    tr.innerHTML = `
        <td class="py-2 px-3">
            <input type="text" list="products_list" placeholder="Product name" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs outline-none focus:border-[#714B67]" oninput="onProductSelect(this, '${rowId}')">
            <datalist id="products_list">
                ${existingProducts.map(p => `<option value="${escapeHtml(p.name)}"></option>`).join('')}
            </datalist>
            <input type="hidden" class="row-product-id" value="">
        </td>
        <td class="py-2 px-3">
            <input type="text" placeholder="Item Code" class="row-code w-full px-2 py-1.5 border border-gray-300 rounded text-xs outline-none focus:border-[#714B67]">
        </td>
        <td class="py-2 px-3">
            <input type="text" placeholder="General" class="row-category w-full px-2 py-1.5 border border-gray-300 rounded text-xs outline-none focus:border-[#714B67]">
        </td>
        <td class="py-2 px-3 text-center">
            <input type="number" step="1" min="1" value="1" class="row-qty w-full text-center px-2 py-1.5 border border-gray-300 rounded text-xs outline-none focus:border-[#714B67]" onchange="recalculateTotals()" onkeyup="recalculateTotals()">
        </td>
        <td class="py-2 px-3 text-right">
            <input type="number" step="0.01" value="0.00" class="row-cost w-full text-right px-2 py-1.5 border border-gray-300 rounded text-xs outline-none focus:border-[#714B67]" onchange="recalculateTotals()" onkeyup="recalculateTotals()">
        </td>
        <td class="py-2 px-3 text-right">
            <input type="number" step="0.01" value="0.00" class="row-price w-full text-right px-2 py-1.5 border border-gray-300 rounded text-xs outline-none focus:border-[#714B67]">
        </td>
        <td class="py-2 px-3">
            <input type="text" placeholder="Serial No." class="row-serial w-full px-2 py-1.5 border border-gray-300 rounded text-xs outline-none focus:border-[#714B67]">
        </td>
        <td class="py-2 px-3 text-right font-bold text-gray-800 row-subtotal">
            AED 0.00
        </td>
        <td class="py-2 px-3 text-center">
            <button type="button" onclick="removeRow('${rowId}')" class="text-red-500 hover:text-red-700 font-bold text-base">&times;</button>
        </td>
    `;

    tbody.appendChild(tr);
    recalculateTotals();
}

function removeRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
        recalculateTotals();
    }
}

function onProductSelect(input, rowId) {
    const val = input.value;
    const matched = existingProducts.find(p => p.name.toLowerCase() === val.toLowerCase());
    const row = document.getElementById(rowId);

    if (matched) {
        row.querySelector('.row-product-id').value = matched.id;
        if (matched.cost) row.querySelector('.row-cost').value = matched.cost;
        if (matched.price) row.querySelector('.row-price').value = matched.price;
        if (matched.internal_reference) row.querySelector('.row-code').value = matched.internal_reference;
    } else {
        row.querySelector('.row-product-id').value = '';
    }
    recalculateTotals();
}

function recalculateTotals() {
    const rows = document.querySelectorAll('#purchase-items-tbody tr');
    let grandSubtotal = 0;

    rows.forEach(tr => {
        const qty = parseFloat(tr.querySelector('.row-qty').value || 0);
        const cost = parseFloat(tr.querySelector('.row-cost').value || 0);
        const subtotal = qty * cost;

        tr.querySelector('.row-subtotal').textContent = 'AED ' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        grandSubtotal += subtotal;
    });

    const vat = grandSubtotal * 0.05;
    const grandTotal = grandSubtotal + vat;

    document.getElementById('summary-subtotal').textContent = 'AED ' + grandSubtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('summary-vat').textContent = 'AED ' + vat.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('summary-grand-total').textContent = 'AED ' + grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function savePurchaseVoucher() {
    const supplierId = document.getElementById('supplier-select').value;
    const newSupplierName = document.getElementById('new-supplier-name').value.trim();

    if (!supplierId && !newSupplierName) {
        alert('Please select an existing supplier or enter a new supplier name.');
        return;
    }

    const rows = document.querySelectorAll('#purchase-items-tbody tr');
    const lines = [];

    rows.forEach(tr => {
        const pName = tr.querySelector('input[list="products_list"]').value.trim();
        const pId = tr.querySelector('.row-product-id').value;
        const code = tr.querySelector('.row-code').value.trim();
        const cat = tr.querySelector('.row-category').value.trim();
        const qty = parseFloat(tr.querySelector('.row-qty').value || 0);
        const cost = parseFloat(tr.querySelector('.row-cost').value || 0);
        const price = parseFloat(tr.querySelector('.row-price').value || 0);
        const serial = tr.querySelector('.row-serial').value.trim();

        if (pName || pId) {
            lines.push({
                product_id: pId,
                product_name: pName,
                item_code: code,
                category: cat || 'General',
                quantity: qty,
                purchase_price: cost,
                selling_price: price,
                equipment_serial_number: serial
            });
        }
    });

    if (lines.length === 0) {
        alert('Please add at least one item line to save the purchase voucher.');
        return;
    }

    const payload = {
        action: 'save_direct_purchase',
        voucher_number: voucherNumber,
        supplier_id: supplierId,
        supplier_name: newSupplierName,
        purchase_date: document.getElementById('purchase-date').value,
        reference_no: document.getElementById('reference-no').value,
        payment_terms: document.getElementById('payment-terms').value,
        notes: document.getElementById('purchase-notes').value,
        lines: lines
    };

    fetch('../api/purchases.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            alert(`Direct Purchase Voucher ${res.data.voucher_number} saved! Stock has been added to Inventory.`);
            window.location.href = '../inventory/index.php';
        } else {
            alert('Failed to save purchase voucher: ' + res.error);
        }
    })
    .catch(err => console.error(err));
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>

<?php require_once '../includes/footer.php'; ?>
