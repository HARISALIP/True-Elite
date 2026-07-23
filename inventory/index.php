<?php
$pageTitle = 'Inventory | True Elite Admin';
$moduleName = 'Inventory';
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<?php
$currentTab = $_GET['tab'] ?? 'products';
?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <?php if ($currentTab !== 'products'): ?>
        <!-- Coming Soon View for Overview, Operations, Reporting, Configuration -->
        <main class="flex-1 p-8 flex items-center justify-center">
            <div class="bg-white p-12 rounded-sm shadow-sm border border-gray-200 text-center max-w-md w-full">
                <div class="w-16 h-16 bg-purple-100 text-[#714B67] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 uppercase tracking-wide mb-2"><?php echo htmlspecialchars(ucfirst($currentTab)); ?> Section</h2>
                <p class="text-gray-500 text-sm mb-6">This section is currently under development. Future updates will be deployed here.</p>
                <a href="index.php?tab=products" class="btn-primary px-5 py-2 rounded-sm text-sm font-medium inline-block">GO TO PRODUCTS</a>
            </div>
        </main>
    <?php else: ?>
        <!-- Action Toolbar (Identical to Sales List) -->
        <div class="bg-white border-b border-gray-200 px-4 py-2 flex items-center justify-between shadow-sm z-20 sticky top-12">
            <div class="flex items-center gap-2">
                <button type="button" onclick="openItemModal()" class="btn-primary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm inline-block uppercase">NEW ITEM</button>
                <button type="button" onclick="openStockAdjustModal()" class="btn-secondary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm inline-block uppercase">ADJUST STOCK</button>
            </div>
            
            <!-- Search, Filters and Counter -->
            <div class="flex items-center gap-3">
                <select id="filter-category" onchange="loadInventory()" class="text-xs border border-gray-300 rounded-sm px-2 py-1.5 outline-none bg-white">
                    <option value="">All Categories</option>
                </select>

                <select id="filter-supplier" onchange="loadInventory()" class="text-xs border border-gray-300 rounded-sm px-2 py-1.5 outline-none bg-white">
                    <option value="">All Suppliers</option>
                </select>

                <label class="flex items-center gap-1.5 text-xs text-red-600 font-semibold cursor-pointer border border-gray-300 px-2 py-1 rounded-sm bg-white">
                    <input type="checkbox" id="filter-low-stock" onchange="loadInventory()" class="rounded-sm border-gray-300 text-red-600 focus:ring-red-500">
                    <span>Low Stock</span>
                </label>

                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="list-search" placeholder="Search..." class="w-56 pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded-sm focus:outline-none focus:border-odoo-purple focus:ring-1 focus:ring-odoo-purple" onkeyup="filterInventory()">
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
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden w-full">
            <table class="w-full text-left text-xs md:text-[13px] border-collapse" id="list-table">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold cursor-pointer text-[11px] md:text-[12px]">
                    <tr>
                        <th class="px-1.5 py-2 w-7 cursor-default text-center">
                            <input type="checkbox" class="rounded-sm border-gray-300 text-odoo-purple focus:ring-odoo-purple">
                        </th>
                        <th class="px-2 py-2 hover:bg-gray-100 transition-colors">Item Name ▾</th>
                        <th class="px-2 py-2 hover:bg-gray-100 transition-colors">Item Code ▾</th>
                        <th class="px-2 py-2 hover:bg-gray-100 transition-colors">Category ▾</th>
                        <th class="px-2 py-2 hover:bg-gray-100 transition-colors">Supplier ▾</th>
                        <th class="px-2 py-2 text-right hover:bg-gray-100 transition-colors" title="Purchase Price">Purchase Price ▾</th>
                        <th class="px-2 py-2 text-right hover:bg-gray-100 transition-colors" title="Selling Price">Selling Price ▾</th>
                        <th class="px-1.5 py-2 text-center hover:bg-gray-100 transition-colors">Stock Qty ▾</th>
                        <th class="px-1.5 py-2 text-center hover:bg-gray-100 transition-colors" title="Minimum Stock Alert">Min Stock Alert ▾</th>
                        <th class="px-2 py-2 text-center hover:bg-gray-100 transition-colors">Stock In / Out ▾</th>
                        <th class="px-2 py-2 hover:bg-gray-100 transition-colors">Serial Number ▾</th>
                        <th class="px-2 py-2 text-center cursor-default">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700" id="list-body">
                    <tr>
                        <td colspan="12" class="px-4 py-8 text-center text-gray-500">Loading inventory data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
    <?php endif; ?>
</div>

<!-- Add / Edit Item Modal -->
<div id="item-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-sm shadow-xl w-full max-w-2xl overflow-hidden border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white text-gray-900">
            <h3 id="modal-title" class="text-base font-bold">Add Inventory Item</h3>
            <button type="button" onclick="closeItemModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>
        <form id="item-form" onsubmit="saveItem(event)" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" id="item-id">
            
            <div class="col-span-2">
                <label class="form-label-odoo">ITEM NAME *</label>
                <input type="text" id="item-name" required class="form-input-odoo mt-1">
            </div>

            <div>
                <label class="form-label-odoo">ITEM CODE</label>
                <input type="text" id="item-code" placeholder="PROD-1001" class="form-input-odoo mt-1">
            </div>

            <div>
                <label class="form-label-odoo">CATEGORY</label>
                <input type="text" id="item-category" placeholder="Hardware, Electronics" class="form-input-odoo mt-1">
            </div>

            <div>
                <label class="form-label-odoo">SUPPLIER</label>
                <select id="item-supplier-id" class="form-input-odoo mt-1">
                    <option value="">-- Select Supplier --</option>
                </select>
            </div>

            <div>
                <label class="form-label-odoo">EQUIPMENT SERIAL NO.</label>
                <input type="text" id="item-serial" placeholder="SN-8849202" class="form-input-odoo mt-1">
            </div>

            <div>
                <label class="form-label-odoo">PURCHASE PRICE (COST)</label>
                <input type="number" step="0.01" id="item-cost" value="0.00" class="form-input-odoo mt-1">
            </div>

            <div>
                <label class="form-label-odoo">SELLING PRICE</label>
                <input type="number" step="0.01" id="item-price" value="0.00" class="form-input-odoo mt-1">
            </div>

            <div>
                <label class="form-label-odoo">QUANTITY IN STOCK</label>
                <input type="number" step="1" id="item-qty" value="0" class="form-input-odoo mt-1">
            </div>

            <div>
                <label class="form-label-odoo">MINIMUM STOCK ALERT</label>
                <input type="number" step="1" id="item-min-alert" value="5" class="form-input-odoo mt-1">
            </div>

            <div class="col-span-2 flex justify-end gap-2 mt-4 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeItemModal()" class="btn-secondary px-4 py-1.5 rounded-sm text-sm font-medium">DISCARD</button>
                <button type="submit" class="btn-primary px-4 py-1.5 rounded-sm text-sm font-medium">SAVE ITEM</button>
            </div>
        </form>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div id="stock-adjust-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-sm shadow-xl w-full max-w-md overflow-hidden border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white text-gray-900">
            <h3 class="text-base font-bold">Adjust Stock</h3>
            <button type="button" onclick="closeStockAdjustModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>
        <form onsubmit="submitStockAdjust(event)" class="p-6 space-y-4">
            <div>
                <label class="form-label-odoo">SELECT ITEM *</label>
                <select id="adjust-item-id" required class="form-input-odoo mt-1">
                    <option value="">-- Choose Item --</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label-odoo">MOVEMENT</label>
                    <select id="adjust-type" class="form-input-odoo mt-1">
                        <option value="IN">Stock IN (+)</option>
                        <option value="OUT">Stock OUT (-)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label-odoo">QUANTITY *</label>
                    <input type="number" step="1" id="adjust-qty" min="1" required class="form-input-odoo mt-1">
                </div>
            </div>

            <div>
                <label class="form-label-odoo">REASON / NOTES</label>
                <textarea id="adjust-notes" rows="2" placeholder="Reconciliation, audit..." class="form-input-odoo mt-1"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeStockAdjustModal()" class="btn-secondary px-4 py-1.5 rounded-sm text-sm font-medium">DISCARD</button>
                <button type="submit" class="btn-primary px-4 py-1.5 rounded-sm text-sm font-medium">SUBMIT</button>
            </div>
        </form>
    </div>
</div>

<script>
let inventoryItems = [];

document.addEventListener('DOMContentLoaded', () => {
    loadInventory();
});

function loadInventory() {
    const q = document.getElementById('list-search').value;
    const cat = document.getElementById('filter-category').value;
    const sup = document.getElementById('filter-supplier').value;
    const low = document.getElementById('filter-low-stock').checked ? '1' : '';

    fetch(`../api/inventory.php?q=${encodeURIComponent(q)}&category=${encodeURIComponent(cat)}&supplier_id=${encodeURIComponent(sup)}&low_stock=${low}`)
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                inventoryItems = res.data.items || [];
                populateDropdowns(res.data.categories || [], res.data.suppliers || []);
                renderTable(inventoryItems);
            } else {
                alert('Error loading inventory: ' + res.error);
            }
        })
        .catch(err => console.error(err));
}

function populateDropdowns(categories, suppliers) {
    const catSelect = document.getElementById('filter-category');
    const supSelect = document.getElementById('filter-supplier');
    const itemSupSelect = document.getElementById('item-supplier-id');
    const adjItemSelect = document.getElementById('adjust-item-id');

    const currCat = catSelect.value;
    const currSup = supSelect.value;

    catSelect.innerHTML = '<option value="">All Categories</option>' + 
        categories.map(c => `<option value="${c}" ${c === currCat ? 'selected' : ''}>${c}</option>`).join('');

    supSelect.innerHTML = '<option value="">All Suppliers</option>' + 
        suppliers.map(s => `<option value="${s.id}" ${s.id == currSup ? 'selected' : ''}>${s.supplier_name}</option>`).join('');

    itemSupSelect.innerHTML = '<option value="">-- Select Supplier --</option>' + 
        suppliers.map(s => `<option value="${s.id}">${s.supplier_name}</option>`).join('');

    adjItemSelect.innerHTML = '<option value="">-- Choose Item --</option>' + 
        inventoryItems.map(i => `<option value="${i.id}">${i.product_name} (${i.item_code || 'No Code'}) - Stock: ${i.quantity_in_stock}</option>`).join('');
}

function renderTable(items) {
    const tbody = document.getElementById('list-body');
    const counterText = document.getElementById('counter-text');

    updateDashboardStats(items || []);

    if (!items || items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="12" class="px-4 py-8 text-center text-gray-500">No inventory items found. Create a new one to get started.</td></tr>`;
        counterText.textContent = "0-0 / 0";
        return;
    }

    counterText.textContent = `1-${items.length} / ${items.length}`;

    tbody.innerHTML = items.map(item => {
        const qty = parseFloat(item.quantity_in_stock || 0);
        const minAlert = parseFloat(item.min_stock_alert || 5);
        const isLowStock = qty <= minAlert;

        const cost = parseFloat(item.cost || 0).toFixed(2);
        const price = parseFloat(item.sales_price || 0).toFixed(2);
        const stockIn = parseFloat(item.stock_in_total || 0);
        const stockOut = parseFloat(item.stock_out_total || 0);

        return `
            <tr class="hover:bg-gray-50 cursor-pointer border-b border-gray-100 text-[12px]" onclick='editItem(${JSON.stringify(item).replace(/'/g, "&#39;")})'>
                <td class="px-1.5 py-2 text-center" onclick="event.stopPropagation()">
                    <input type="checkbox" class="rounded-sm border-gray-300 text-odoo-purple focus:ring-odoo-purple">
                </td>
                <td class="px-2 py-2 font-semibold text-gray-900 truncate max-w-[140px]" title="${escapeHtml(item.product_name)}">${escapeHtml(item.product_name)}</td>
                <td class="px-2 py-2 text-gray-600 font-mono text-[11px]">${escapeHtml(item.item_code || item.internal_reference || '-')}</td>
                <td class="px-2 py-2 text-gray-700">${escapeHtml(item.category || 'General')}</td>
                <td class="px-2 py-2 font-medium text-gray-700 truncate max-w-[130px]" title="${escapeHtml(item.supplier_name || item.linked_supplier_name || '-')}">${escapeHtml(item.supplier_name || item.linked_supplier_name || '-')}</td>
                <td class="px-2 py-2 text-right font-medium">AED ${cost}</td>
                <td class="px-2 py-2 text-right font-medium text-emerald-700">AED ${price}</td>
                <td class="px-1.5 py-2 text-center">
                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold ${isLowStock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                        ${qty}
                    </span>
                </td>
                <td class="px-1.5 py-2 text-center text-gray-600">${minAlert}</td>
                <td class="px-2 py-2 text-center font-mono text-[11px]">
                    <span class="text-green-700 font-bold">+${stockIn}</span>/<span class="text-red-700 font-bold">-${stockOut}</span>
                </td>
                <td class="px-2 py-2 text-gray-600 font-mono text-[11px]">${escapeHtml(item.equipment_serial_number || '-')}</td>
                <td class="px-2 py-2 text-center" onclick="event.stopPropagation()">
                    <button onclick='editItem(${JSON.stringify(item).replace(/'/g, "&#39;")})' class="btn-secondary px-2 py-0.5 text-[11px] rounded-sm">Edit</button>
                </td>
            </tr>
        `;
    }).join('');
}

function filterInventory() {
    loadInventory();
}

function updateDashboardStats(items) {
    let totalItems = items.length;
    let lowStock = 0;
    let totalQty = 0;
    let totalVal = 0;

    items.forEach(i => {
        const q = parseFloat(i.quantity_in_stock || 0);
        const minA = parseFloat(i.min_stock_alert || 5);
        const cost = parseFloat(i.cost || 0);

        if (q <= minA) lowStock++;
        totalQty += q;
        totalVal += (q * cost);
    });

    const elTotalItems = document.getElementById('stat-total-items');
    if (elTotalItems) elTotalItems.textContent = totalItems;

    const elLowStock = document.getElementById('stat-low-stock');
    if (elLowStock) elLowStock.textContent = lowStock;

    const elTotalQty = document.getElementById('stat-total-qty');
    if (elTotalQty) elTotalQty.textContent = totalQty.toLocaleString();

    const elTotalVal = document.getElementById('stat-total-val');
    if (elTotalVal) elTotalVal.textContent = 'AED ' + totalVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function openItemModal() {
    document.getElementById('item-form').reset();
    document.getElementById('item-id').value = '';
    document.getElementById('modal-title').textContent = 'Add Inventory Item';
    document.getElementById('item-modal').classList.remove('hidden');
}

function closeItemModal() {
    document.getElementById('item-modal').classList.add('hidden');
}

function editItem(item) {
    document.getElementById('modal-title').textContent = 'Edit Inventory Item';
    document.getElementById('item-id').value = item.id;
    document.getElementById('item-name').value = item.product_name || '';
    document.getElementById('item-code').value = item.item_code || item.internal_reference || '';
    document.getElementById('item-category').value = item.category || '';
    document.getElementById('item-supplier-id').value = item.supplier_id || '';
    document.getElementById('item-serial').value = item.equipment_serial_number || '';
    document.getElementById('item-cost').value = item.cost || 0;
    document.getElementById('item-price').value = item.sales_price || 0;
    document.getElementById('item-qty').value = item.quantity_in_stock || 0;
    document.getElementById('item-min-alert').value = item.min_stock_alert || 5;

    document.getElementById('item-modal').classList.remove('hidden');
}

function saveItem(e) {
    e.preventDefault();
    const payload = {
        action: 'update_item',
        id: document.getElementById('item-id').value,
        product_name: document.getElementById('item-name').value,
        item_code: document.getElementById('item-code').value,
        category: document.getElementById('item-category').value,
        supplier_id: document.getElementById('item-supplier-id').value,
        equipment_serial_number: document.getElementById('item-serial').value,
        cost: document.getElementById('item-cost').value,
        sales_price: document.getElementById('item-price').value,
        quantity_in_stock: document.getElementById('item-qty').value,
        min_stock_alert: document.getElementById('item-min-alert').value
    };

    fetch('../api/inventory.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            closeItemModal();
            loadInventory();
        } else {
            alert('Error saving item: ' + res.error);
        }
    });
}

function openStockAdjustModal() {
    document.getElementById('adjust-item-id').value = '';
    document.getElementById('adjust-qty').value = '';
    document.getElementById('adjust-notes').value = '';
    document.getElementById('stock-adjust-modal').classList.remove('hidden');
}

function closeStockAdjustModal() {
    document.getElementById('stock-adjust-modal').classList.add('hidden');
}

function submitStockAdjust(e) {
    e.preventDefault();
    const payload = {
        action: 'adjust_stock',
        id: document.getElementById('adjust-item-id').value,
        type: document.getElementById('adjust-type').value,
        quantity: document.getElementById('adjust-qty').value,
        notes: document.getElementById('adjust-notes').value
    };

    fetch('../api/inventory.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            closeStockAdjustModal();
            loadInventory();
        } else {
            alert('Error adjusting stock: ' + res.error);
        }
    });
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>

<?php require_once '../includes/footer.php'; ?>
