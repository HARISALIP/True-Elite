<?php
$pageTitle = 'Sales Configuration | True Elite ERP';
$moduleName = 'Sales';
$subSection = 'configuration';
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shadow-sm sticky top-12 z-20">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Sales Settings & Terms</h1>
            <p class="text-xs text-gray-500">Default payment terms, VAT rates, and quotation prefix configuration</p>
        </div>
        <a href="quotations.php" class="btn-primary px-3.5 py-1.5 rounded-sm text-xs font-semibold uppercase">BACK TO QUOTATIONS</a>
    </div>

    <!-- Main Container -->
    <main class="flex-1 p-6 max-w-4xl w-full mx-auto space-y-6">
        
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6 space-y-6">
            <div>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2 border-b border-gray-100 pb-2">Sales Order Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Quotation Number Prefix</label>
                        <input type="text" value="QT-2026-" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-50 text-gray-700 font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Default Currency</label>
                        <input type="text" value="AED (United Arab Emirates Dirham)" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-50 text-gray-700 font-semibold">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2 border-b border-gray-100 pb-2">Tax & VAT Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Standard UAE VAT Rate</label>
                        <input type="text" value="5.0% Standard VAT" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-50 text-gray-700 font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Stock Deduction Policy</label>
                        <input type="text" value="Automatic Stock Out on Sales Order Confirmation" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-50 text-gray-700 font-semibold">
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
