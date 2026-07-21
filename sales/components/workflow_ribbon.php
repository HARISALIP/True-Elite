<div class="px-6 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <button type="button" id="btn-email" onclick="openEmailModal()" class="btn-primary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm">SEND BY EMAIL</button>
        <button type="button" id="btn-confirm" onclick="confirmQuotation()" class="btn-secondary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm">CONFIRM</button>
        <button type="button" id="btn-cancel" onclick="cancelQuotation()" class="btn-secondary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm">CANCEL</button>
    </div>
    
    <!-- Ribbon -->
    <div class="flex text-xs font-semibold uppercase tracking-wide">
        <div id="ribbon-quotation" class="px-4 py-1.5 bg-[#017e84] text-white rounded-l-sm clip-arrow relative z-30 cursor-pointer transition-colors">Quotation</div>
        <div id="ribbon-sent" class="px-4 py-1.5 bg-gray-200 text-gray-500 border-l border-white clip-arrow relative -ml-3 z-20 pl-6 cursor-pointer hover:bg-gray-300 transition-colors">Quotation Sent</div>
        <div id="ribbon-order" class="px-4 py-1.5 bg-gray-200 text-gray-500 border-l border-white rounded-r-sm relative -ml-3 z-10 pl-6 cursor-pointer hover:bg-gray-300 transition-colors">Sales Order</div>
        <div id="ribbon-cancelled" class="hidden px-4 py-1.5 bg-red-600 text-white rounded-sm relative z-40">Cancelled</div>
    </div>
</div>
