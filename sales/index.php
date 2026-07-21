<?php
require_once '../config/db.php';

$quotation_id_param = $_GET['id'] ?? null;
$quotation = null;
$quotation_items = [];

if ($quotation_id_param) {
    // Fetch Quotation
    $stmt = $pdo->prepare("SELECT * FROM quotations WHERE quotation_number = ?");
    $stmt->execute([$quotation_id_param]);
    $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($quotation) {
        // Fetch Items
        $itemStmt = $pdo->prepare("SELECT * FROM quotation_items WHERE quotation_id = ? ORDER BY row_order ASC");
        $itemStmt->execute([$quotation['id']]);
        $quotation_items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$pageTitle = $quotation ? 'Quotation ' . $quotation['quotation_number'] . ' | True Elite Admin' : 'Quotation - New | True Elite Admin';
$moduleName = 'Sales';
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<!-- Main Form Area -->
<form class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Toolbar -->
    <div class="bg-white border-b border-gray-200 px-4 py-2 flex items-center justify-between shadow-sm z-50 sticky top-12">
        <div class="flex items-center gap-4 text-gray-700">
            <span class="text-xl">New</span>
            <button type="button" onclick="saveQuotation()" class="text-gray-500 hover:text-black" title="Save">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
            </button>
            <a href="quotations.php" class="text-gray-500 hover:text-black" title="Discard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </a>
        </div>
        
        <div class="flex items-center gap-4 text-[13px] font-medium text-gray-700">
            <div class="relative inline-block text-left">
                <button type="button" onclick="document.getElementById('print-dropdown').classList.toggle('hidden')" class="flex items-center gap-1 hover:text-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                    <svg class="w-3 h-3 ml-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="print-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <a href="#" onclick="printDocument('quote'); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Quotation</a>
                        <a href="#" onclick="printDocument('invoice'); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Tax Invoice</a>
                        <a href="#" onclick="printDocument('delivery'); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Delivery Note</a>
                    </div>
                </div>
            </div>
            <div class="relative inline-block text-left">
                <button type="button" onclick="document.getElementById('action-dropdown').classList.toggle('hidden')" class="flex items-center gap-1 hover:text-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Action
                </button>
                <div id="action-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Duplicate</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Delete</a>
                    </div>
                </div>
            </div>
            <a href="index.php" class="border border-gray-300 px-3 py-1 rounded text-[#714B67] hover:bg-gray-50 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New
            </a>
        </div>
    </div>

    <!-- Scrollable Workspace -->
    <main class="flex-1 p-4 flex justify-center pb-20">
        
        <!-- Paper Sheet -->
        <div class="bg-white border border-gray-300 shadow-sm rounded-sm w-full max-w-6xl self-start flex flex-col">
            
            <!-- Status Bar / Ribbon & Header Actions -->
            <?php require_once 'components/workflow_ribbon.php'; ?>

            <div class="p-6">
                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-6">
                    <?php echo $quotation ? htmlspecialchars($quotation['quotation_number']) : 'New Quotation'; ?>
                </h1>

                <!-- Top Form Section -->
                <?php require_once 'components/customer_section.php'; ?>

                <!-- Tabs -->
                <div class="flex border-b border-gray-200 mb-4">
                    <div class="nav-tab active" onclick="switchMainTab(this, 'order-lines')">Order Lines</div>
                    <div class="nav-tab" onclick="switchMainTab(this, 'optional-products')">Optional Products</div>
                    <div class="nav-tab" onclick="switchMainTab(this, 'other-info')">Other Info</div>
                </div>

                <!-- Tab Content: Order Lines -->
                <div id="tab-order-lines" class="block">
                    <!-- Order Lines Table -->
                    <?php require_once 'components/order_table.php'; ?>
                    
                    <!-- Bottom Area: Terms & Totals -->
                    <?php require_once 'components/totals_panel.php'; ?>
                </div>

                <!-- Tab Content: Optional Products -->
                <div id="tab-optional-products" class="hidden min-h-[300px] text-gray-500 text-sm p-4">
                    <p>Optional products configuration will appear here.</p>
                </div>

                <!-- Tab Content: Other Info -->
                <div id="tab-other-info" class="hidden min-h-[300px] text-gray-500 text-sm p-4">
                    <p>Other information configuration will appear here.</p>
                </div>

            </div>
        </div>
    </main>
</form>

<!-- Toast Notification -->
<div id="toast-container" class="fixed top-16 right-4 z-50 transition-all duration-300 transform translate-y-[-100%] opacity-0">
    <div class="bg-green-50 border border-green-200 rounded-sm shadow-md p-4 flex items-start gap-3 w-80">
        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <div class="flex-1">
            <h4 class="text-sm font-semibold text-green-800" id="toast-title">Success</h4>
            <p class="text-sm text-green-700 mt-1" id="toast-message">Action completed.</p>
        </div>
        <button onclick="document.getElementById('toast-container').classList.add('translate-y-[-100%]', 'opacity-0')" class="text-green-500 hover:text-green-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>

<!-- Email Modal -->
<div id="email-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-sm shadow-xl w-full max-w-2xl flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Compose Email</h3>
            <button onclick="document.getElementById('email-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 space-y-4 flex-1 overflow-y-auto">
            <div class="flex items-center">
                <label class="w-20 text-sm font-medium text-gray-700">To</label>
                <input type="email" id="email-to" value="" class="flex-1 form-input-odoo p-2">
            </div>
            <div class="flex items-center">
                <label class="w-20 text-sm font-medium text-gray-700">Subject</label>
                <input type="text" id="email-subject" value="" class="flex-1 form-input-odoo p-2">
            </div>
            <div class="mt-4">
                <textarea id="email-body" class="w-full form-input-odoo p-3 min-h-[200px] text-sm text-gray-700 resize-none"></textarea>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-2">
            <button onclick="document.getElementById('email-modal').classList.add('hidden')" class="btn-secondary px-4 py-2 rounded-sm text-sm">Discard</button>
            <button onclick="sendEmail()" class="btn-primary px-4 py-2 rounded-sm text-sm">Send</button>
        </div>
    </div>
</div>

<!-- Create Customer Modal -->
<?php require_once 'components/create_customer_modal.php'; ?>

<!-- Create Product Modal -->
<?php require_once 'components/create_product_modal.php'; ?>

<style>
/* Custom clip path for the workflow ribbon arrows */
.clip-arrow {
    clip-path: polygon(0 0, 100% 0, 95% 50%, 100% 100%, 0 100%, 5% 50%);
}
.clip-arrow:first-child {
    clip-path: polygon(0 0, 100% 0, 95% 50%, 100% 100%, 0 100%);
}
.clip-arrow:last-child {
    clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 5% 50%);
}
</style>

<!-- Load Sales Interactivity Script -->
<script>
    // Embed existing quotation data for Javascript
    window.existingQuotation = <?php echo json_encode($quotation ?: null); ?>;
    window.existingQuotationItems = <?php echo json_encode($quotation_items); ?>;
</script>
<script src="../assets/js/sales.js?v=<?= time() ?>"></script>

<?php require_once '../includes/footer.php'; ?>
