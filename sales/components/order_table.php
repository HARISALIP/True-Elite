<!-- Order Lines Table -->
<div class="overflow-x-auto min-h-[250px]">
    <table class="w-full text-left text-sm whitespace-nowrap">
        <thead class="border-b border-gray-300 text-gray-700 font-semibold text-[13px]">
            <tr>
                <th class="pb-2 w-10 text-center"></th>
                <th class="pb-2 w-48">Product</th>
                <th class="pb-2 w-16 text-center">Image</th>
                <th class="pb-2">Description</th>
                <th class="pb-2 text-right w-24">Markup %</th>
                <th class="pb-2 text-right w-24">Quantity</th>
                <th class="pb-2 text-right w-32">Unit Price</th>
                <th class="pb-2 w-32">Taxes</th>
                <th class="pb-2 text-right w-24">Disc.%</th>
                <th class="pb-2 text-right w-32">Subtotal</th>
                <th class="pb-2 w-10"></th>
            </tr>
        </thead>
        <tbody id="order-lines-container" class="divide-y divide-gray-100 text-[13px]">
            <!-- JavaScript will populate rows here -->
        </tbody>
        <tbody>
            <!-- Add Line Button -->
            <tr>
                <td colspan="9" class="py-3">
                    <div class="mt-3 flex items-center gap-2 px-1">
                        <button type="button" onclick="addLine()" class="text-odoo-purple hover:text-[#5D3A54] font-medium text-sm transition-colors">Add a product</button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="addSection()" class="text-odoo-purple hover:text-[#5D3A54] font-medium text-sm transition-colors">Add a section</button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="addNote()" class="text-odoo-purple hover:text-[#5D3A54] font-medium text-sm transition-colors">Add a note</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
