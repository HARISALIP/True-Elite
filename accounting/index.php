<?php
$pageTitle = 'Accounting | True Elite Admin';
$moduleName = 'Accounting';
require_once '../includes/header.php';
?>

<?php require_once '../includes/sidebar.php'; ?>
<?php require_once '../includes/topbar.php'; ?>

<div class="pt-12 min-h-screen flex flex-col bg-[#F0EFF5]">
    
    <!-- Action Toolbar (Identical to Sales & Inventory List) -->
    <div class="bg-white border-b border-gray-200 px-4 py-2 flex items-center justify-between shadow-sm z-20 sticky top-12">
        <div class="flex items-center gap-2">
            <button type="button" class="btn-primary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm inline-block uppercase">NEW JOURNAL ENTRY</button>
            <button type="button" class="btn-secondary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm inline-block uppercase">RECONCILE</button>
        </div>
        
        <!-- Search and Counter -->
        <div class="flex items-center gap-4">
            <div class="relative">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="list-search" placeholder="Search..." class="w-64 pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded-sm focus:outline-none focus:border-odoo-purple focus:ring-1 focus:ring-odoo-purple">
            </div>
            
            <div class="flex items-center gap-1 text-sm text-gray-600">
                <span>0-0 / 0</span>
                <button class="p-1 hover:bg-gray-100 rounded-sm opacity-50 cursor-not-allowed"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button class="p-1 hover:bg-gray-100 rounded-sm opacity-50 cursor-not-allowed"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
        </div>
    </div>

    <!-- Main Content List Sheet -->
    <main class="flex-1 p-4">
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm whitespace-nowrap" id="list-table">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-medium cursor-pointer">
                    <tr>
                        <th class="px-4 py-2.5 w-10 cursor-default">
                            <input type="checkbox" class="rounded-sm border-gray-300 text-odoo-purple focus:ring-odoo-purple">
                        </th>
                        <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors">Date ▾</th>
                        <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors">Number ▾</th>
                        <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors">Partner / Contact ▾</th>
                        <th class="px-4 py-2.5 hover:bg-gray-100 transition-colors">Journal ▾</th>
                        <th class="px-4 py-2.5 text-right hover:bg-gray-100 transition-colors">Total ▾</th>
                        <th class="px-4 py-2.5 text-right hover:bg-gray-100 transition-colors">Status ▾</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700" id="list-body">
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No journal entries found. Create a new one to get started.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
