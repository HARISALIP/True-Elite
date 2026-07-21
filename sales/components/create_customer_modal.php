<!-- Create Customer Modal -->
<div id="create-customer-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-sm shadow-xl w-full max-w-2xl flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-[#714B67] text-white rounded-t-sm">
            <h3 class="text-lg font-semibold">Create Customer</h3>
            <button type="button" onclick="document.getElementById('create-customer-modal').classList.add('hidden')" class="text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 space-y-4 flex-1 overflow-y-auto bg-[#F0EFF5]">
            <div class="bg-white p-6 border border-gray-300 shadow-sm rounded-sm space-y-4">
                <div class="flex items-center">
                    <label class="form-label-odoo w-32">Customer Name *</label>
                    <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                        <input type="text" id="new-customer-name" class="w-full border-none focus:ring-0 p-0 py-0.5 text-lg font-semibold text-gray-900 bg-transparent" placeholder="e.g. Acme Corp">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-x-8 gap-y-4 pt-4">
                    <div class="flex items-center">
                        <label class="form-label-odoo w-24">Phone</label>
                        <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                            <input type="text" id="new-customer-phone" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="form-label-odoo w-24">Email</label>
                        <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                            <input type="email" id="new-customer-email" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="form-label-odoo w-24">Address</label>
                        <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                            <input type="text" id="new-customer-address" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="form-label-odoo w-24">City</label>
                        <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                            <input type="text" id="new-customer-city" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="form-label-odoo w-24">Country</label>
                        <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                            <input type="text" id="new-customer-country" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="form-label-odoo w-24">TRN</label>
                        <div class="flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                            <input type="text" id="new-customer-trn" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-white flex items-center gap-2 rounded-b-sm">
            <button type="button" onclick="saveCustomer()" class="btn-primary px-4 py-2 rounded-sm text-sm font-medium shadow-sm">Save & Close</button>
            <button type="button" onclick="document.getElementById('create-customer-modal').classList.add('hidden')" class="btn-secondary px-4 py-2 rounded-sm text-sm font-medium shadow-sm">Discard</button>
        </div>
    </div>
</div>
