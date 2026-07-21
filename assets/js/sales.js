// assets/js/sales.js

let products = {};

async function fetchProducts() {
    try {
        const response = await fetch('../api/products.php');
        const result = await response.json();
        if (result.success) {
            products = result.data;
        } else {
            console.error('Failed to fetch products:', result.error);
        }
    } catch (error) {
        console.error('Error fetching products:', error);
    }
}

let customers = {};

async function fetchCustomers() {
    try {
        const response = await fetch('../api/customers.php');
        const result = await response.json();
        if (result.success) {
            customers = result.data;
        } else {
            console.error('Failed to fetch customers:', result.error);
        }
    } catch (error) {
        console.error('Error fetching customers:', error);
    }
}

let lineCounter = 0;

function updateCustomerPreview() {
    const customerId = document.getElementById('customer-select').value;
    const previewEl = document.getElementById('customer-preview');
    
    if (customerId && customers[customerId]) {
        const customer = customers[customerId];
        document.getElementById('cp-name').textContent = customer.name;
        if (document.getElementById('cp-email')) {
            document.getElementById('cp-email').textContent = customer.email;
        }
        document.getElementById('cp-phone').textContent = customer.phone;
        document.getElementById('cp-address').textContent = customer.address;
        document.getElementById('cp-initial').textContent = customer.initial;
        
        // Populate delivery address as well
        document.getElementById('delivery-address').value = customer.address;
        
        previewEl.classList.remove('opacity-0', 'pointer-events-none');
    } else {
        previewEl.classList.add('opacity-0', 'pointer-events-none');
    }
}

function addLine() {
    lineCounter++;
    const tbody = document.getElementById('order-lines-container');
    
    const tr = document.createElement('tr');
    tr.className = 'table-row-hover group line-row';
    tr.id = `line-${lineCounter}`;
    
    tr.innerHTML = `
        <td class="py-2 text-center text-gray-400 cursor-move">⋮⋮</td>
        <td class="py-2 pr-2 relative">
            <input type="hidden" class="product-hidden-id" id="product-select-${lineCounter}">
            <input type="text" id="product-search-input-${lineCounter}" placeholder="Select Product..." class="w-full form-input-odoo bg-transparent" autocomplete="off" 
                onfocus="toggleProductDropdown(${lineCounter}, true)" 
                oninput="filterProductDropdown(${lineCounter})" 
                onblur="setTimeout(() => toggleProductDropdown(${lineCounter}, false), 200)">
            <div id="product-dropdown-menu-${lineCounter}" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 shadow-lg z-50 max-h-60 overflow-y-auto rounded-sm py-1">
                <!-- Populated by JS -->
            </div>
        </td>
        <td class="py-2 pr-2 text-center">
            <div class="w-10 h-10 bg-gray-100 border border-gray-200 rounded flex items-center justify-center overflow-hidden mx-auto">
                <img src="" class="hidden object-cover w-full h-full product-image-thumb" id="product-thumb-${lineCounter}">
                <svg class="w-5 h-5 text-gray-400 placeholder-icon" id="product-placeholder-${lineCounter}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </td>
        <td class="py-2 pr-2">
            <input type="text" class="w-full form-input-odoo bg-transparent desc-input" placeholder="Description">
        </td>
        <td class="py-2 pr-2">
            <input type="number" class="w-full text-right form-input-odoo bg-transparent markup-input" value="0.00" step="0.01" oninput="calculateRow(${lineCounter})">
        </td>
        <td class="py-2 pr-2">
            <input type="number" class="w-full text-right form-input-odoo bg-transparent qty-input" value="1.000" step="0.001" oninput="calculateRow(${lineCounter})">
        </td>
        <td class="py-2 pr-2">
            <input type="number" class="w-full text-right form-input-odoo bg-transparent price-input text-gray-500" value="0.00" step="0.01" readonly tabindex="-1">
        </td>
        <td class="py-2 pr-2">
            <select class="w-full form-input-odoo bg-transparent vat-input" onchange="calculateRow(${lineCounter})">
                <option value="5">VAT 5%</option>
                <option value="0">0%</option>
            </select>
        </td>
        <td class="py-2 pr-2">
            <input type="number" class="w-full text-right form-input-odoo bg-transparent disc-input" value="0.00" step="0.01" oninput="calculateRow(${lineCounter})">
        </td>
        <td class="py-2 text-right font-medium text-gray-900 subtotal-text pr-2">0.00</td>
        <td class="py-2 text-center">
            <button type="button" onclick="removeLine(this)" class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </td>
    `;
    
    tbody.appendChild(tr);
    calculateGrandTotals();
}

function removeLine(btn) {
    btn.closest('tr').remove();
    calculateGrandTotals();
}

function onProductChange(productId, lineId) {
    const tr = document.getElementById(`line-${lineId}`);
    
    if (productId && products[productId]) {
        const product = products[productId];
        const brandStr = product.brand ? ` | Brand: ${product.brand}` : '';
        const modelStr = product.model ? ` | Model: ${product.model}` : '';
        const dimStr = product.dimension ? ` | Dim: ${product.dimension}` : '';
        const fullDesc = `${product.description}${brandStr}${modelStr}${dimStr}`;
        
        tr.dataset.cost = product.cost || 0;
        tr.querySelector('.desc-input').value = fullDesc;
        tr.querySelector('.markup-input').value = '0.00';
        tr.querySelector('.price-input').value = product.cost ? product.cost.toFixed(2) : '0.00';
        
        // Handle image thumbnail
        const thumbImg = document.getElementById(`product-thumb-${lineId}`);
        const placeholder = document.getElementById(`product-placeholder-${lineId}`);
        
        if (product.image) {
            thumbImg.src = '../' + product.image;
            thumbImg.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            thumbImg.src = '';
            thumbImg.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
        
        // Update taxes if available
        if (product.tax) {
            tr.querySelector('.vat-input').value = product.tax;
        }
        
        calculateRow(lineId);
    }
}

function addSection() {
    lineCounter++;
    const tbody = document.getElementById('order-lines-container');
    const tr = document.createElement('tr');
    tr.className = 'table-row-hover group line-row bg-gray-50';
    tr.id = `line-${lineCounter}`;
    tr.innerHTML = `
        <td class="py-2 text-center text-gray-400 cursor-move">⋮⋮</td>
        <td colspan="9" class="py-2 pr-2">
            <input type="text" class="w-full form-input-odoo bg-transparent font-bold text-gray-900" placeholder="Section Name...">
        </td>
        <td class="py-2 text-center">
            <button type="button" onclick="removeLine(this)" class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function addNote() {
    lineCounter++;
    const tbody = document.getElementById('order-lines-container');
    const tr = document.createElement('tr');
    tr.className = 'table-row-hover group line-row';
    tr.id = `line-${lineCounter}`;
    tr.innerHTML = `
        <td class="py-2 text-center text-gray-400 cursor-move">⋮⋮</td>
        <td colspan="9" class="py-2 pr-2">
            <input type="text" class="w-full form-input-odoo bg-transparent italic text-gray-600" placeholder="Note...">
        </td>
        <td class="py-2 text-center">
            <button type="button" onclick="removeLine(this)" class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function showToast(title, message) {
    const toast = document.getElementById('toast-container');
    document.getElementById('toast-title').textContent = title;
    document.getElementById('toast-message').textContent = message;
    
    toast.classList.remove('translate-y-[-100%]', 'opacity-0');
    
    setTimeout(() => {
        toast.classList.add('translate-y-[-100%]', 'opacity-0');
    }, 3000);
}

function calculateRow(lineId) {
    const tr = document.getElementById(`line-${lineId}`);
    if (!tr) return;
    
    const cost = parseFloat(tr.dataset.cost) || 0;
    const markup = parseFloat(tr.querySelector('.markup-input') ? tr.querySelector('.markup-input').value : 0) || 0;
    const qty = parseFloat(tr.querySelector('.qty-input') ? tr.querySelector('.qty-input').value : 0) || 0;
    const disc = parseFloat(tr.querySelector('.disc-input') ? tr.querySelector('.disc-input').value : 0) || 0;
    
    let price = cost + (cost * (markup / 100));
    if (tr.querySelector('.price-input')) {
        tr.querySelector('.price-input').value = price.toFixed(2);
    }
    
    let subtotal = qty * price;
    if (disc > 0) {
        subtotal = subtotal - (subtotal * (disc / 100));
    }
    
    tr.querySelector('.subtotal-text').textContent = subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    // Store values for grand total calc
    tr.dataset.subtotal = subtotal;
    tr.dataset.vat = parseFloat(tr.querySelector('.vat-input').value) || 0;
    
    calculateGrandTotals();
}

function calculateGrandTotals() {
    let untaxed = 0;
    let taxes = 0;
    
    const rows = document.querySelectorAll('.line-row');
    rows.forEach(row => {
        const subtotal = parseFloat(row.dataset.subtotal) || 0;
        const vatRate = parseFloat(row.dataset.vat) || 0;
        
        untaxed += subtotal;
        taxes += subtotal * (vatRate / 100);
    });
    
    const grandTotal = untaxed + taxes;
    
    document.getElementById('total-untaxed').textContent = untaxed.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('total-taxes').textContent = taxes.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('total-grand').textContent = grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
    await fetchProducts();
    await fetchCustomers();
    
    if (window.existingQuotation && window.existingQuotation.id) {
        populateExistingQuotation();
    } else {
        addLine();
    }
    
    setupRibbonInteractions();
});

function populateExistingQuotation() {
    const q = window.existingQuotation;
    currentQuotationId = q.id;
    currentQuotationNumber = q.quotation_number;
    
    // Set field values
    if (q.customer_id) selectCustomer(q.customer_id, customers[q.customer_id] ? customers[q.customer_id].name : 'Unknown');
    document.getElementById('quotation-number').value = q.quotation_number;
    document.getElementById('delivery-address').value = q.address || '';
    document.getElementById('quotation-date').value = q.quotation_date;
    
    // Calculate expiry days dropdown from dates
    if (q.expiry_date) {
        const diffTime = Math.abs(new Date(q.expiry_date) - new Date(q.quotation_date));
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const expirySelect = document.getElementById('expiry-date');
        // Check if diffDays matches one of our options (7, 14, 30, 45, 60, 90)
        let found = false;
        for (let i = 0; i < expirySelect.options.length; i++) {
            if (parseInt(expirySelect.options[i].value) === diffDays) {
                expirySelect.selectedIndex = i;
                found = true;
                break;
            }
        }
        if (!found && diffDays > 0) {
            // Add custom option if needed, though ui says fixed preset. 
            // We'll leave it as is if it doesn't match perfectly.
        }
    }
    
    document.getElementById('payment-terms').value = q.payment_terms || '';
    document.getElementById('department').value = q.department || '';
    document.getElementById('attention').value = q.attention || '';
    document.getElementById('subject').value = q.subject || '';
    document.getElementById('payment-method').value = q.payment_method || 'Bank Transfer';
    
    // Populate Lines
    if (window.existingQuotationItems && window.existingQuotationItems.length > 0) {
        window.existingQuotationItems.forEach(item => {
            if (item.is_section == 1) {
                addSection();
                const tr = document.getElementById(`line-${lineCounter}`);
                tr.querySelector('input').value = item.description;
            } else if (item.is_note == 1) {
                addNote();
                const tr = document.getElementById(`line-${lineCounter}`);
                tr.querySelector('input').value = item.description;
            } else {
                addLine();
                const tr = document.getElementById(`line-${lineCounter}`);
                
                if (item.product_id && products[item.product_id]) {
                    document.getElementById(`product-search-input-${lineCounter}`).value = products[item.product_id].name;
                    document.getElementById(`product-select-${lineCounter}`).value = item.product_id;
                    
                    // Handle image thumbnail
                    const thumbImg = document.getElementById(`product-thumb-${lineCounter}`);
                    const placeholder = document.getElementById(`product-placeholder-${lineCounter}`);
                    if (products[item.product_id].image) {
                        thumbImg.src = '../' + products[item.product_id].image;
                        thumbImg.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                }
                
                tr.dataset.cost = item.cost;
                tr.querySelector('.desc-input').value = item.description;
                tr.querySelector('.markup-input').value = parseFloat(item.markup_percent).toFixed(2);
                tr.querySelector('.qty-input').value = parseFloat(item.quantity).toFixed(3);
                tr.querySelector('.price-input').value = parseFloat(item.unit_price).toFixed(2);
                tr.querySelector('.vat-input').value = parseFloat(item.tax_rate);
                tr.querySelector('.disc-input').value = parseFloat(item.discount_percent).toFixed(2);
                
                calculateRow(lineCounter);
            }
        });
    } else {
        addLine();
    }
    
    // Update Ribbon Status
    const statusMap = {
        'Quotation': 'ribbon-quotation',
        'Quotation Sent': 'ribbon-sent',
        'Sales Order': 'ribbon-order'
    };
    
    if (q.workflow_status === 'Cancelled') {
        ['ribbon-quotation', 'ribbon-sent', 'ribbon-order'].forEach(id => document.getElementById(id).classList.add('hidden'));
        document.getElementById('ribbon-cancelled').classList.remove('hidden');
    } else if (statusMap[q.workflow_status]) {
        const targetId = statusMap[q.workflow_status];
        const stages = ['ribbon-quotation', 'ribbon-sent', 'ribbon-order'];
        let found = false;
        stages.forEach((sId) => {
            const sEl = document.getElementById(sId);
            if (!found) {
                sEl.classList.remove('bg-gray-200', 'text-gray-500');
                sEl.classList.add('bg-[#017e84]', 'text-white');
                if (sId === targetId) found = true;
            } else {
                sEl.classList.remove('bg-[#017e84]', 'text-white');
                sEl.classList.add('bg-gray-200', 'text-gray-500');
            }
        });
    }
}

function setupRibbonInteractions() {
    const stages = ['ribbon-quotation', 'ribbon-sent', 'ribbon-order'];
    stages.forEach((stageId, index) => {
        const el = document.getElementById(stageId);
        if (el) {
            el.addEventListener('click', () => {
                // If it's cancelled, we don't allow changing stages easily
                if (!document.getElementById('ribbon-cancelled').classList.contains('hidden')) return;
                
                // Set this stage as active, others as inactive
                stages.forEach((sId, i) => {
                    const sEl = document.getElementById(sId);
                    if (i <= index) {
                        sEl.classList.remove('bg-gray-200', 'text-gray-500');
                        sEl.classList.add('bg-[#017e84]', 'text-white');
                    } else {
                        sEl.classList.remove('bg-[#017e84]', 'text-white');
                        sEl.classList.add('bg-gray-200', 'text-gray-500');
                    }
                });
            });
        }
    });
}

// Quotation API Integration
let currentQuotationId = null;
let currentQuotationNumber = null;

async function saveQuotation() {
    const customerId = document.getElementById('customer-select').value;
    if (!customerId) {
        alert("Please select a customer before saving.");
        return false;
    }
    
    // Extract Lines
    const lines = [];
    const rows = document.querySelectorAll('.line-row');
    rows.forEach(row => {
        const isSection = row.querySelector('.font-bold.text-gray-900') !== null;
        const isNote = row.querySelector('.italic.text-gray-600') !== null;
        
        if (isSection || isNote) {
            lines.push({
                product_id: null,
                description: row.querySelector('input').value,
                quantity: 1,
                cost: 0,
                markup_percent: 0,
                unit_price: 0,
                tax_rate: 0,
                discount_percent: 0,
                subtotal: 0,
                total: 0,
                is_section: isSection,
                is_note: isNote
            });
        } else {
            const productInput = row.querySelector('.product-hidden-id');
            const cost = parseFloat(row.dataset.cost) || 0;
            const markup = parseFloat(row.querySelector('.markup-input').value) || 0;
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const tax = parseFloat(row.querySelector('.vat-input').value) || 0;
            const disc = parseFloat(row.querySelector('.disc-input').value) || 0;
            const subtotal = parseFloat(row.dataset.subtotal) || 0;
            const total = subtotal + (subtotal * (tax / 100));
            
            lines.push({
                product_id: productInput ? productInput.value : null,
                description: row.querySelector('.desc-input').value,
                quantity: qty,
                cost: cost,
                markup_percent: markup,
                unit_price: price,
                tax_rate: tax,
                discount_percent: disc,
                subtotal: subtotal,
                total: total,
                is_section: false,
                is_note: false
            });
        }
    });
    
    const expiryDays = parseInt(document.getElementById('expiry-date').value) || 0;
    let calculatedExpiry = null;
    if (expiryDays > 0) {
        const date = new Date();
        date.setDate(date.getDate() + expiryDays);
        calculatedExpiry = date.toISOString().split('T')[0];
    }
    
    const payload = {
        action: 'save',
        quotation_id: currentQuotationId,
        customer_id: customerId,
        quotation_number: document.getElementById('quotation-number') ? document.getElementById('quotation-number').value : '',
        address: document.getElementById('delivery-address').value,
        quotation_date: document.getElementById('quotation-date').value,
        expiry_date: calculatedExpiry,
        payment_terms: document.getElementById('payment-terms').value,
        department: document.getElementById('department') ? document.getElementById('department').value : '',
        attention: document.getElementById('attention').value,
        subject: document.getElementById('subject').value,
        payment_method: document.getElementById('payment-method') ? document.getElementById('payment-method').value : 'Bank Transfer',
        salesperson: document.getElementById('salesperson').value,
        subtotal: parseFloat(document.getElementById('total-untaxed').textContent.replace(/,/g, '')) || 0,
        tax_total: parseFloat(document.getElementById('total-taxes').textContent.replace(/,/g, '')) || 0,
        grand_total: parseFloat(document.getElementById('total-grand').textContent.replace(/,/g, '')) || 0,
        lines: lines
    };
    
    try {
        const response = await fetch('../api/quotations.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await response.json();
        
        if (result.success) {
            currentQuotationId = result.data.quotation_id;
            currentQuotationNumber = result.data.quotation_number;
            showToast('Saved', `Quotation ${result.data.quotation_number} saved to MySQL.`);
            return true;
        } else {
            alert("Error: " + result.error);
            return false;
        }
    } catch (e) {
        alert("Failed to save quotation.");
        console.error(e);
        return false;
    }
}

async function updateQuotationStatus(status) {
    if (!currentQuotationId) {
        const saved = await saveQuotation();
        if (!saved) return false;
    }
    
    try {
        const response = await fetch('../api/quotations.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'update_status', quotation_id: currentQuotationId, status: status })
        });
        const result = await response.json();
        return result.success;
    } catch (e) {
        console.error(e);
        return false;
    }
}

async function confirmQuotation() {
    if (await updateQuotationStatus('Quotation Sent')) {
        document.getElementById('ribbon-sent').click();
        showToast('Confirmed', 'Quotation marked as Sent in DB.');
    }
}

async function cancelQuotation() {
    if (confirm("Are you sure you want to cancel this quotation?")) {
        if (await updateQuotationStatus('Cancelled')) {
            ['ribbon-quotation', 'ribbon-sent', 'ribbon-order'].forEach(id => document.getElementById(id).classList.add('hidden'));
            document.getElementById('ribbon-cancelled').classList.remove('hidden');
            document.querySelectorAll('input, select, button:not(#btn-cancel):not(.nav-tab)').forEach(el => {
                if (el.closest('.no-print')) return;
                el.disabled = true;
                el.classList.add('opacity-70', 'cursor-not-allowed');
            });
            showToast('Cancelled', 'Quotation has been cancelled in DB.');
        }
    }
}

async function openEmailModal() {
    if (!currentQuotationId) {
        const saved = await saveQuotation();
        if (!saved) return;
    }
    document.getElementById('email-modal').classList.remove('hidden');
}

async function printQuotation() {
    if (!currentQuotationId) {
        const saved = await saveQuotation();
        if (!saved) return;
    }
    window.open('invoice_print.php?id=' + currentQuotationNumber, '_blank');
}


function switchMainTab(clickedTab, tabId) {
    // Remove active class from all main tabs
    const tabs = clickedTab.parentElement.querySelectorAll('.nav-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Add active class to clicked tab
    clickedTab.classList.add('active');
    
    // Hide all tab contents
    const contents = ['order-lines', 'optional-products', 'other-info'];
    contents.forEach(id => {
        const el = document.getElementById('tab-' + id);
        if (el) {
            el.classList.remove('block');
            el.classList.add('hidden');
        }
    });
    
    // Show target tab
    const targetEl = document.getElementById('tab-' + tabId);
    if (targetEl) {
        targetEl.classList.remove('hidden');
        targetEl.classList.add('block');
    }
}

// Customer Dropdown Logic
function renderCustomerDropdown(filter = '') {
    const menu = document.getElementById('customer-dropdown-menu');
    menu.innerHTML = '';
    
    let hasMatches = false;
    for (const [id, customer] of Object.entries(customers)) {
        if (customer.name.toLowerCase().includes(filter.toLowerCase())) {
            hasMatches = true;
            const div = document.createElement('div');
            div.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm text-gray-700';
            div.textContent = customer.name;
            div.onmousedown = () => selectCustomer(id, customer.name); // mousedown fires before blur
            menu.appendChild(div);
        }
    }
    
    // Add "Create" option
    const createDiv = document.createElement('div');
    createDiv.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm text-odoo-purple font-medium border-t border-gray-100 mt-1 pt-1 flex items-center gap-2';
    
    if (filter.length > 0) {
        let exactMatch = null;
        for (const [id, customer] of Object.entries(customers)) {
            if (customer.name.toLowerCase() === filter.toLowerCase()) {
                exactMatch = customer;
                break;
            }
        }
        
        if (exactMatch) {
            createDiv.innerHTML = `Edit "<strong>${filter}</strong>"`;
            createDiv.onmousedown = () => {
                document.getElementById('new-customer-name').value = exactMatch.name;
                
                let idField = document.getElementById('new-customer-id');
                if (!idField) {
                    idField = document.createElement('input');
                    idField.type = 'hidden';
                    idField.id = 'new-customer-id';
                    document.getElementById('new-customer-name').parentNode.appendChild(idField);
                }
                idField.value = exactMatch.id;
                
                document.getElementById('create-customer-modal').classList.remove('hidden');
            };
        } else {
            createDiv.innerHTML = `Create "<strong>${filter}</strong>"`;
            createDiv.onmousedown = () => {
                document.getElementById('new-customer-name').value = filter;
                let idField = document.getElementById('new-customer-id');
                if (idField) idField.value = '';
                document.getElementById('create-customer-modal').classList.remove('hidden');
            };
        }
    } else {
        createDiv.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create and edit...
        `;
        createDiv.onmousedown = () => {
            document.getElementById('new-customer-name').value = '';
            let idField = document.getElementById('new-customer-id');
            if (idField) idField.value = '';
            document.getElementById('create-customer-modal').classList.remove('hidden');
        };
    }
    menu.appendChild(createDiv);
}

function toggleCustomerDropdown(show) {
    const menu = document.getElementById('customer-dropdown-menu');
    if (show) {
        renderCustomerDropdown(document.getElementById('customer-search-input').value);
        menu.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
    }
}

function filterCustomerDropdown() {
    renderCustomerDropdown(document.getElementById('customer-search-input').value);
}

function selectCustomer(id, name) {
    document.getElementById('customer-search-input').value = name;
    document.getElementById('customer-select').value = id;
    document.getElementById('customer-dropdown-menu').classList.add('hidden');
    updateCustomerPreview();
}

async function saveCustomer() {
    const name = document.getElementById('new-customer-name').value;
    if (!name) {
        alert("Customer Name is required");
        return;
    }
    
    const formData = new FormData();
    const idField = document.getElementById('new-customer-id');
    if (idField && idField.value) {
        formData.append('id', idField.value);
    }
    formData.append('name', name);
    formData.append('email', document.getElementById('new-customer-email').value);
    formData.append('phone', document.getElementById('new-customer-phone').value);
    formData.append('address', document.getElementById('new-customer-address').value);
    
    // Additional fields if they exist in the modal, else they default to empty
    const cityEl = document.getElementById('new-customer-city');
    if (cityEl) formData.append('city', cityEl.value);
    
    const countryEl = document.getElementById('new-customer-country');
    if (countryEl) formData.append('country', countryEl.value);
    
    const trnEl = document.getElementById('new-customer-trn');
    if (trnEl) formData.append('trn', trnEl.value);
    
    const companyEl = document.getElementById('new-customer-company');
    if (companyEl) formData.append('company', companyEl.value);
    
    try {
        const response = await fetch('../api/customers.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            const newCustomer = result.data;
            customers[newCustomer.id] = newCustomer; // Add to local cache
            
            // Select and close
            selectCustomer(newCustomer.id, newCustomer.name);
            document.getElementById('create-customer-modal').classList.add('hidden');
            showToast('Customer Created', `Customer ${name} saved successfully.`);
        } else {
            alert('Error: ' + result.error);
        }
    } catch (error) {
        console.error('Error saving customer:', error);
        alert('An error occurred while saving the customer.');
    }
}

// Product Dropdown Logic
let activeProductLineId = null;

function renderProductDropdown(lineId, filter = '') {
    const menu = document.getElementById(`product-dropdown-menu-${lineId}`);
    if (!menu) return;
    
    menu.innerHTML = '';
    
    let hasMatches = false;
    for (const [id, product] of Object.entries(products)) {
        if (product.name.toLowerCase().includes(filter.toLowerCase())) {
            hasMatches = true;
            const div = document.createElement('div');
            div.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm text-gray-700';
            div.textContent = product.name;
            div.onmousedown = () => selectProduct(lineId, id, product.name);
            menu.appendChild(div);
        }
    }
    // Add "Create" option
    const createDiv = document.createElement('div');
    createDiv.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm text-odoo-purple font-medium border-t border-gray-100 mt-1 pt-1 flex items-center gap-2';
    
    if (filter.length > 0) {
        let exactMatch = null;
        for (const [id, product] of Object.entries(products)) {
            if (product.name.toLowerCase() === filter.toLowerCase()) {
                exactMatch = product;
                break;
            }
        }
        
        if (exactMatch) {
            createDiv.innerHTML = `Edit "<strong>${filter}</strong>"`;
            createDiv.onmousedown = () => {
                activeProductLineId = lineId;
                document.getElementById('new-product-name').value = exactMatch.name;
                
                let idField = document.getElementById('new-product-id');
                if (!idField) {
                    idField = document.createElement('input');
                    idField.type = 'hidden';
                    idField.id = 'new-product-id';
                    document.getElementById('new-product-name').parentNode.appendChild(idField);
                }
                idField.value = exactMatch.id;
                
                document.getElementById('create-product-modal').classList.remove('hidden');
            };
        } else {
            createDiv.innerHTML = `Create "<strong>${filter}</strong>"`;
            createDiv.onmousedown = () => {
                activeProductLineId = lineId;
                document.getElementById('new-product-name').value = filter;
                let idField = document.getElementById('new-product-id');
                if (idField) idField.value = '';
                document.getElementById('create-product-modal').classList.remove('hidden');
            };
        }
    } else {
        createDiv.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create and edit...
        `;
        createDiv.onmousedown = () => {
            activeProductLineId = lineId;
            document.getElementById('new-product-name').value = '';
            let idField = document.getElementById('new-product-id');
            if (idField) idField.value = '';
            document.getElementById('create-product-modal').classList.remove('hidden');
        };
    }
    menu.appendChild(createDiv);
}

function toggleProductDropdown(lineId, show) {
    const menu = document.getElementById(`product-dropdown-menu-${lineId}`);
    if (!menu) return;
    
    if (show) {
        renderProductDropdown(lineId, document.getElementById(`product-search-input-${lineId}`).value);
        menu.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
    }
}

function filterProductDropdown(lineId) {
    renderProductDropdown(lineId, document.getElementById(`product-search-input-${lineId}`).value);
}

function selectProduct(lineId, id, name) {
    document.getElementById(`product-search-input-${lineId}`).value = name;
    document.getElementById(`product-select-${lineId}`).value = id;
    document.getElementById(`product-dropdown-menu-${lineId}`).classList.add('hidden');
    onProductChange(id, lineId);
}

async function saveProduct() {
    const name = document.getElementById('new-product-name').value;
    if (!name) {
        alert("Product Name is required");
        return;
    }
    
    const formData = new FormData();
    const idField = document.getElementById('new-product-id');
    if (idField && idField.value) {
        formData.append('id', idField.value);
    }
    formData.append('name', name);
    formData.append('price', document.getElementById('new-product-price').value);
    formData.append('type', document.getElementById('new-product-type').value);
    formData.append('brand', document.getElementById('new-product-brand').value);
    formData.append('dimension', document.getElementById('new-product-dimension').value);
    formData.append('model', document.getElementById('new-product-model').value);
    // Add file if selected
    const fileInput = document.getElementById('new-product-image');
    if (fileInput.files.length > 0) {
        formData.append('image', fileInput.files[0]);
    }
    
    try {
        const response = await fetch('../api/products.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            const newProduct = result.data;
            products[newProduct.id] = newProduct; // Add to local JS cache
            
            if (activeProductLineId) {
                selectProduct(activeProductLineId, newProduct.id, newProduct.name);
            }
            
            document.getElementById('create-product-modal').classList.add('hidden');
            showToast('Product Created', `Product ${name} saved successfully.`);
            
            // Clear inputs for next time
            document.getElementById('new-product-name').value = '';
            document.getElementById('new-product-price').value = '0.00';
            removeProductImage();
        } else {
            alert('Error: ' + result.error);
        }
    } catch (error) {
        console.error('Error saving product:', error);
        alert('An error occurred while saving the product.');
    }
}

// Product Image Handlers
function previewProductImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        // Validate client side
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!allowed.includes(file.type)) {
            alert('Invalid image type. Please select a JPG, PNG, or WEBP image.');
            input.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('Image is too large. Maximum size is 5MB.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('product-image-preview').src = e.target.result;
            document.getElementById('product-image-preview-container').classList.remove('hidden');
            document.getElementById('product-image-icon').classList.add('hidden');
            document.getElementById('product-image-badge').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removeProductImage() {
    document.getElementById('new-product-image').value = '';
    document.getElementById('product-image-preview').src = '';
    document.getElementById('product-image-preview-container').classList.add('hidden');
    document.getElementById('product-image-icon').classList.remove('hidden');
    document.getElementById('product-image-badge').classList.remove('hidden');
}

// Workflow Ribbon Status
document.addEventListener('DOMContentLoaded', () => {
    if (window.existingQuotation && window.existingQuotation.id) {
        currentQuotationId = window.existingQuotation.id;
        currentQuotationNumber = window.existingQuotation.quotation_number;
        updateWorkflowRibbonUI(window.existingQuotation.workflow_status);
    }
});

function updateWorkflowRibbonUI(status) {
    const qEl = document.getElementById('ribbon-quotation');
    const sEl = document.getElementById('ribbon-sent');
    const oEl = document.getElementById('ribbon-order');
    const cEl = document.getElementById('ribbon-cancelled');
    
    // Reset all
    [qEl, sEl, oEl].forEach(el => {
        if (el) {
            el.classList.remove('bg-[#017e84]', 'text-white');
            el.classList.add('bg-gray-200', 'text-gray-500');
            el.classList.remove('hidden');
        }
    });
    if (cEl) cEl.classList.add('hidden');
    
    // Toggle action buttons
    const btnConfirm = document.getElementById('btn-confirm');
    const btnCancel = document.getElementById('btn-cancel');
    const btnEmail = document.getElementById('btn-email');
    
    if (btnConfirm) btnConfirm.classList.remove('hidden');
    if (btnCancel) btnCancel.classList.remove('hidden');
    
    if (status === 'Cancelled' && cEl) {
        [qEl, sEl, oEl].forEach(el => { if (el) el.classList.add('hidden'); });
        cEl.classList.remove('hidden');
        if (btnConfirm) btnConfirm.classList.add('hidden');
        if (btnCancel) btnCancel.classList.add('hidden');
        if (btnEmail) btnEmail.classList.add('hidden');
    } else if (status === 'Sales Order' && oEl) {
        oEl.classList.add('bg-[#017e84]', 'text-white');
        oEl.classList.remove('bg-gray-200', 'text-gray-500');
        if (btnConfirm) btnConfirm.classList.add('hidden');
        if (btnCancel) btnCancel.classList.add('hidden');
    } else if (status === 'Quotation Sent' && sEl) {
        sEl.classList.add('bg-[#017e84]', 'text-white');
        sEl.classList.remove('bg-gray-200', 'text-gray-500');
    } else if (qEl) {
        // Default Quotation
        qEl.classList.add('bg-[#017e84]', 'text-white');
        qEl.classList.remove('bg-gray-200', 'text-gray-500');
    }
}

async function updateStatus(newStatus) {
    if (!currentQuotationId) {
        const saved = await saveQuotation();
        if (!saved) return;
    }
    
    try {
        const response = await fetch('../api/quotations.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'update_status',
                quotation_id: currentQuotationId,
                status: newStatus
            })
        });
        const result = await response.json();
        
        if (result.success) {
            updateWorkflowRibbonUI(newStatus);
            if (window.existingQuotation) {
                window.existingQuotation.workflow_status = newStatus;
            }
            showToast('Status Updated', `Quotation marked as ${newStatus}`);
        } else {
            alert('Error: ' + result.error);
        }
    } catch (error) {
        console.error('Error updating status:', error);
        alert('An error occurred while updating the status.');
    }
}

async function confirmQuotation() {
    await updateStatus('Sales Order');
}

async function cancelQuotation() {
    await updateStatus('Cancelled');
}

async function openEmailModal() {
    if (!currentQuotationId) {
        const saved = await saveQuotation();
        if (!saved) return;
    }
    
    // Attempt to pre-fill email
    let customerEmail = '';
    const customerSelect = document.getElementById('customer_id');
    if (customerSelect && customerSelect.value) {
        const c = customers[customerSelect.value];
        if (c && c.email) {
            customerEmail = c.email;
        }
    }
    
    document.getElementById('email-to').value = customerEmail;
    document.getElementById('email-subject').value = `Quotation (Ref ${currentQuotationNumber || 'New'})`;
    
    const grandTotal = document.getElementById('grand-total').textContent;
    document.getElementById('email-body').value = `Hello,\n\nYour quotation ${currentQuotationNumber || 'New'} amounting to ${grandTotal} AED is ready for review.\n\nDo not hesitate to contact us if you have any questions.`;
    
    document.getElementById('email-modal').classList.remove('hidden');
}

async function sendEmail() {
    document.getElementById('email-modal').classList.add('hidden');
    await updateStatus('Quotation Sent');
}
