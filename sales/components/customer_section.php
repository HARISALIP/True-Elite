<?php
require_once '../config/db.php';
$year = date('y');
$stmt = $pdo->prepare("SELECT COUNT(*) FROM quotations WHERE quotation_number LIKE ?");
$stmt->execute(["TEK-$year-%"]);
$next = $stmt->fetchColumn() + 1;
$defaultQuotationNumber = "TEK-$year-" . str_pad($next, 4, '0', STR_PAD_LEFT);
?>
<!-- Top Form Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-6 mb-8">
    <!-- Left Column: Customer & Address -->
    <div class="space-y-4 lg:col-span-1">
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Customer</label>
            <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84] relative">
                <input type="hidden" id="customer-select" onchange="updateCustomerPreview()">
                <input type="text" id="customer-search-input" placeholder="Customer Name" class="form-input-odoo w-full border-none p-0 py-0.5 bg-transparent" autocomplete="off" onfocus="toggleCustomerDropdown(true)" oninput="filterCustomerDropdown()" onblur="setTimeout(() => toggleCustomerDropdown(false), 200)">
                <div id="customer-dropdown-menu" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 shadow-lg z-50 max-h-60 overflow-y-auto rounded-sm py-1">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Address</label>
            <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                <input type="text" id="delivery-address" placeholder="e.g. Dubai, UAE" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
            </div>
        </div>
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Attn</label>
            <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                <input type="text" id="attention" placeholder="Contact person" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
            </div>
        </div>
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Sub</label>
            <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                <input type="text" id="subject" placeholder="Quotation subject" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
            </div>
        </div>
    </div>

    <!-- Middle Column: Quotation Details -->
    <div class="space-y-4 lg:col-span-1">
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Quotation No.</label>
            <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                <input type="text" id="quotation-number" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent text-gray-700 font-bold" readonly value="<?php echo $defaultQuotationNumber; ?>">
            </div>
        </div>
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Quotation Date</label>
            <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                <input type="date" id="quotation-date" value="<?php echo date('Y-m-d'); ?>" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent text-gray-700">
            </div>
        </div>
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Expiration</label>
            <select id="expiry-date" class="flex-1 form-input-odoo cursor-pointer w-full border-none p-0 py-0.5 bg-transparent text-gray-700">
                <option value="7">7 Days</option>
                <option value="14">14 Days (2 Weeks)</option>
                <option value="30">30 Days (1 Month)</option>
                <option value="45">45 Days</option>
                <option value="60">60 Days</option>
                <option value="90">90 Days</option>
            </select>
        </div>
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Payment Terms</label>
            <select id="payment-terms" class="flex-1 form-input-odoo cursor-pointer w-full border-none p-0 py-0.5 bg-transparent">
                <option value="Immediate Payment">Immediate Payment</option>
                <option value="End of Month (EOM)">End of Month (EOM)</option>
                <option value="30 Days">30 Days</option>
                <option value="45 Days">45 Days</option>
                <option value="60 Days">60 Days</option>
                <option value="Cash">Cash</option>
                <option value="Cheque">Cheque</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
        </div>
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Department</label>
            <select id="department" class="flex-1 form-input-odoo cursor-pointer w-full border-none p-0 py-0.5 bg-transparent">
                <option value="Purchase Department">Purchase Department</option>
                <option value="Sales Department">Sales Department</option>
                <option value="Service Department">Service Department</option>
            </select>
        </div>
        <div class="flex items-center">
            <label class="form-label-odoo w-32">Salesperson</label>
            <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                <input type="text" id="salesperson" value="Administrator" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
            </div>
        </div>
    </div>

    <!-- Right Column: Customer Preview -->
    <div class="lg:col-span-1 flex justify-end">
        <?php require_once 'customer_preview.php'; ?>
    </div>
</div>
