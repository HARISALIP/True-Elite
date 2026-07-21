<!-- Create Product Modal -->
<div id="create-product-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-sm shadow-xl w-full max-w-5xl flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white text-gray-900 rounded-t-sm">
            <h3 class="text-lg font-semibold">Create Product</h3>
            <button type="button" onclick="document.getElementById('create-product-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto bg-white flex flex-col relative">
            
            <!-- Toolbar & Stat Buttons -->
            <div class="px-6 py-2 border-b border-gray-200 flex justify-between items-start gap-4">
                <div class="flex items-center gap-4 text-xs font-semibold uppercase tracking-wide text-gray-600 mt-2">
                    <button type="button" class="hover:text-gray-900">PRINT LABELS</button>
                    <button type="button" class="hover:text-gray-900">REPLENISH</button>
                </div>
                
                <!-- Stat Buttons -->
                <div class="flex items-center">
                    <!-- Extra Prices -->
                    <button class="flex items-center gap-2 border border-gray-300 px-3 py-1 bg-white hover:bg-gray-50 h-[42px] border-r-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        <div class="text-left leading-tight">
                            <span class="block text-gray-900 font-semibold text-[13px]">0</span>
                            <span class="block text-gray-500 text-[11px]">Extra Prices</span>
                        </div>
                    </button>
                    <!-- In / Out -->
                    <button class="flex items-center gap-2 border border-gray-300 px-3 py-1 bg-white hover:bg-gray-50 h-[42px] border-r-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        <div class="text-left leading-tight">
                            <span class="block text-gray-500 text-[11px]">In: <span class="text-gray-900 font-semibold">0</span></span>
                            <span class="block text-gray-500 text-[11px]">Out: <span class="text-gray-900 font-semibold">0</span></span>
                        </div>
                    </button>
                    <!-- Sold -->
                    <button class="flex items-center gap-2 border border-gray-300 px-3 py-1 bg-white hover:bg-gray-50 h-[42px] border-r-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <div class="text-left leading-tight">
                            <span class="block text-gray-900 font-semibold text-[13px]">0.00</span>
                            <span class="block text-gray-500 text-[11px]">Sold</span>
                        </div>
                    </button>
                    <!-- Purchased -->
                    <button class="flex items-center gap-2 border border-gray-300 px-3 py-1 bg-white hover:bg-gray-50 h-[42px]">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <div class="text-left leading-tight">
                            <span class="block text-gray-900 font-semibold text-[13px]">0.00</span>
                            <span class="block text-gray-500 text-[11px]">Purchased</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Product Title Area -->
            <div class="px-6 py-4 flex items-start justify-between gap-6">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Product Name</label>
                    <div class="flex items-center gap-2">
                        <svg class="w-8 h-8 text-yellow-400 cursor-pointer" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <input type="text" id="new-product-name" required placeholder="e.g. EEE" 
                            class="w-full text-3xl font-bold text-gray-900 border-none border-b-2 border-transparent hover:border-gray-200 focus:border-odoo-purple focus:ring-0 px-0 py-1 bg-transparent transition-colors">
                    </div>
                    <div class="flex items-center gap-6 mt-3 pl-10 text-sm text-gray-700">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" checked class="text-odoo-purple rounded-sm focus:ring-odoo-purple border-gray-400">
                            Can be Sold
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" checked class="text-odoo-purple rounded-sm focus:ring-odoo-purple border-gray-400">
                            Can be Purchased
                        </label>
                    </div>
                </div>
                <!-- Product Image Upload -->
                <div class="relative w-24 h-24 border border-gray-300 border-dashed rounded-sm flex flex-col items-center justify-center text-gray-400 bg-gray-50 cursor-pointer hover:bg-gray-100 flex-shrink-0 overflow-hidden group" onclick="document.getElementById('new-product-image').click()">
                    <input type="file" id="new-product-image" class="hidden" accept="image/jpeg, image/png, image/webp" onchange="previewProductImage(this)">
                    
                    <div id="product-image-preview-container" class="hidden absolute inset-0 w-full h-full bg-white z-10">
                        <img id="product-image-preview" class="w-full h-full object-cover">
                        <!-- Overlay actions -->
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity gap-2">
                            <button type="button" class="text-white hover:text-gray-300" title="Replace" onclick="event.stopPropagation(); document.getElementById('new-product-image').click()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                            <button type="button" class="text-red-400 hover:text-red-300" title="Remove" onclick="event.stopPropagation(); removeProductImage()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <svg id="product-image-icon" class="w-10 h-10 mb-1 z-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div id="product-image-badge" class="absolute bottom-1 right-1 w-5 h-5 bg-gray-200 rounded-full flex items-center justify-center z-0">
                        <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex px-6 mt-4 border-b border-gray-200 bg-white">
                <div class="nav-tab active">General Information</div>
                <div class="nav-tab">Sales</div>
                <div class="nav-tab">Purchase</div>
                <div class="nav-tab">Inventory</div>
                <div class="nav-tab">Accounting</div>
            </div>

            <!-- Tab Content: General Information -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                    
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <label class="form-label-odoo w-32">Product Type</label>
                            <select id="new-product-type" class="flex-1 form-input-odoo cursor-pointer bg-transparent">
                                <option value="Consumable" selected>Consumable</option>
                                <option value="Storable Product">Storable Product</option>
                                <option value="Service">Service</option>
                            </select>
                        </div>
                        <div class="flex items-start">
                            <label class="form-label-odoo w-32 mt-1">Invoicing Policy</label>
                            <div class="flex flex-col gap-1 pt-1 text-sm flex-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="modal_invoice_policy" checked class="text-odoo-purple focus:ring-odoo-purple">
                                    <span>Ordered quantities</span>
                                </label>
                                <p class="text-xs text-gray-500 italic mt-1 leading-relaxed">
                                    Consumables are physical products for which you don't manage the inventory level: they are always available.
                                    <br><br>
                                    You can invoice them before they are delivered.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <label class="form-label-odoo w-32">Sales Price</label>
                            <div class="flex items-center flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                                <span class="text-[13px] text-gray-500 pr-2">AED</span>
                                <input type="number" step="0.01" id="new-product-price" value="0.00" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <label class="form-label-odoo w-32">Customer Taxes</label>
                            <div class="flex-1 flex items-center border-b border-gray-300 py-0.5 min-h-[26px]">
                                <span class="inline-flex items-center bg-gray-100 text-gray-800 text-[11px] font-medium px-2 py-0.5 rounded-xl border border-gray-200">
                                    VAT 5% (Dubai)
                                    <button type="button" class="ml-1 text-gray-500 hover:text-gray-700">&times;</button>
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <label class="form-label-odoo w-32">Cost</label>
                            <div class="flex items-center flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                                <span class="text-[13px] text-gray-500 pr-2">AED</span>
                                <input type="number" step="0.01" value="0.00" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent text-gray-500">
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <label class="form-label-odoo w-32">Product Category</label>
                            <select class="flex-1 form-input-odoo cursor-pointer bg-transparent">
                                <option value="All">All</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center mt-6">
                            <label class="form-label-odoo w-32">Internal Reference</label>
                            <input type="text" class="flex-1 form-input-odoo bg-transparent">
                        </div>
                        
                        <div class="flex items-center">
                            <label class="form-label-odoo w-32">Barcode</label>
                            <input type="text" class="flex-1 form-input-odoo bg-transparent">
                        </div>
                        
                        <div class="flex items-center">
                            <label class="form-label-odoo w-32">Product Tags</label>
                            <input type="text" class="flex-1 form-input-odoo bg-transparent">
                        </div>

                        <div class="flex items-center mt-6">
                            <label class="form-label-odoo w-32">Brand</label>
                            <input type="text" id="new-product-brand" class="flex-1 form-input-odoo bg-transparent">
                        </div>
                        
                        <div class="flex items-center">
                            <label class="form-label-odoo w-32">Dimension</label>
                            <input type="text" id="new-product-dimension" class="flex-1 form-input-odoo bg-transparent">
                        </div>
                        
                        <div class="flex items-center">
                            <label class="form-label-odoo w-32">Model</label>
                            <input type="text" id="new-product-model" class="flex-1 form-input-odoo bg-transparent" placeholder="Enter Model">
                        </div>
                    </div>

                </div>
                
                <!-- Internal Notes -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <label class="form-label-odoo block uppercase tracking-wide text-xs mb-2">INTERNAL NOTES</label>
                    <textarea class="w-full border-none border-b border-gray-300 focus:border-odoo-purple focus:ring-0 p-0 text-[13px] bg-transparent text-gray-400 placeholder-gray-400 italic min-h-[60px]" placeholder="This note is only for internal purposes."></textarea>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-white flex items-center gap-2 rounded-b-sm">
            <button type="button" onclick="saveProduct()" class="btn-primary px-4 py-2 rounded-sm text-sm font-medium shadow-sm bg-odoo-purple hover:bg-[#5D3A54] border-odoo-purple text-white">SAVE & CLOSE</button>
            <button type="button" onclick="document.getElementById('create-product-modal').classList.add('hidden')" class="btn-secondary px-4 py-2 rounded-sm text-sm font-medium shadow-sm">DISCARD</button>
        </div>
    </div>
</div>
