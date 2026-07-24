<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $sales_price = $_POST['sales_price'] ?? 0;
    $cost_price = $_POST['cost_price'] ?? 0;
    $product_type = $_POST['product_type'] ?? 'Consumable';
    $brand = $_POST['brand'] ?? '';
    $dimension = $_POST['dimension'] ?? '';
    $model = $_POST['model'] ?? '';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, sales_price, cost_price, product_type, brand, dimension, model) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $sales_price, $cost_price, $product_type, $brand, $dimension, $model]);
        
        header("Location: sales.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error saving product: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Product | True Elite Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { inter: ['Inter', 'sans-serif'] },
                    colors: {
                        odoo: {
                            purple: '#714B67',
                            light: '#F0EFF5',
                            border: '#d1d5db',
                            text: '#374151',
                            link: '#017e84'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F0EFF5; }
        .odoo-nav { background-color: #714B67; }
        .btn-primary { background-color: #017e84; color: white; transition: background 0.2s; }
        .btn-primary:hover { background-color: #016267; }
        .btn-secondary { background-color: white; color: #374151; border: 1px solid #d1d5db; transition: background 0.2s; }
        .btn-secondary:hover { background-color: #f9fafb; }
        
        .form-input-odoo {
            border: none;
            border-bottom: 1px solid #d1d5db;
            border-radius: 0;
            padding: 2px 0;
            width: 100%;
            font-size: 13px;
            color: #111827;
            background-color: transparent;
        }
        .form-input-odoo:focus {
            outline: none;
            border-bottom-color: #017e84;
            box-shadow: 0 1px 0 0 #017e84;
        }
        .form-label-odoo {
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            width: 160px;
            flex-shrink: 0;
        }
        
        .nav-tab {
            padding: 8px 16px;
            font-size: 13px;
            color: #4b5563;
            border-bottom: 2px solid transparent;
            cursor: pointer;
        }
        .nav-tab:hover { color: #111827; }
        .nav-tab.active {
            color: #017e84;
            border-bottom-color: #017e84;
            font-weight: 500;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-[#374151]">

    <!-- Navbar -->
    <header class="odoo-nav h-14 flex items-center justify-between px-4 fixed top-0 left-0 right-0 z-50 text-white shadow-sm">
        <div class="flex items-center gap-4">
            <a href="index.php" class="p-1.5 hover:bg-white/10 rounded-md transition-colors" title="App Launcher">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"/>
                </svg>
            </a>
            <div class="flex items-center gap-2 text-sm">
                <a href="sales.php" class="opacity-70 hover:opacity-100">Products</a>
                <span class="opacity-50">/</span>
                <span class="font-semibold">New</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">A</div>
        </div>
    </header>

    <form method="POST" action="create_product.php" class="flex-1 flex flex-col pt-14">
        <!-- Toolbar -->
        <div class="bg-white border-b border-gray-200 px-4 py-2.5 flex items-center gap-2 shadow-sm z-40 relative">
            <button type="submit" class="btn-primary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm">
                SAVE & CLOSE
            </button>
            <button type="button" class="btn-secondary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm">
                PRINT LABELS
            </button>
            <button type="button" class="btn-secondary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm">
                REPLENISH
            </button>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 m-4 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 flex justify-center">
            <!-- Paper Sheet -->
            <div class="bg-white border border-gray-300 shadow-sm rounded-sm w-full max-w-5xl self-start">
                
                <!-- Header Section -->
                <div class="p-6 pb-2 border-b border-gray-200">
                    <div class="flex items-start gap-4">
                        <!-- Product Image Placeholder -->
                        <div class="w-24 h-24 border border-gray-300 border-dashed rounded-sm flex flex-col items-center justify-center text-gray-400 bg-gray-50 cursor-pointer hover:bg-gray-100">
                            <svg class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[10px] uppercase font-semibold">Image</span>
                        </div>
                        
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Product Name</label>
                            <input type="text" name="name" required placeholder="e.g. Zebra ZQ630 Mobile Printer" 
                                class="w-full text-2xl font-bold text-gray-900 border-none border-b-2 border-transparent hover:border-gray-200 focus:border-odoo-purple focus:ring-0 px-0 py-1 bg-transparent transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex px-4 border-b border-gray-200 bg-white">
                    <div class="nav-tab active" onclick="switchTab(this, 'general')">General Information</div>
                    <div class="nav-tab" onclick="switchTab(this, 'sales')">Sales</div>
                    <div class="nav-tab" onclick="switchTab(this, 'purchase')">Purchase</div>
                    <div class="nav-tab" onclick="switchTab(this, 'inventory')">Inventory</div>
                    <div class="nav-tab" onclick="switchTab(this, 'accounting')">Accounting</div>
                </div>

                <!-- Tab Content: General Information -->
                <div id="tab-general" class="p-6 min-h-[400px]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <label class="form-label-odoo">Product Type</label>
                                <select name="product_type" class="form-input-odoo cursor-pointer">
                                    <option value="Consumable" selected>Consumable</option>
                                    <option value="Storable Product">Storable Product</option>
                                    <option value="Service">Service</option>
                                </select>
                            </div>
                            <div class="flex items-start">
                                <label class="form-label-odoo mt-1">Invoicing Policy</label>
                                <div class="flex flex-col gap-1 pt-1 text-sm">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="invoice_policy" checked class="text-odoo-purple focus:ring-odoo-purple">
                                        <span>Ordered quantities</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer text-gray-500">
                                        <input type="radio" name="invoice_policy" disabled>
                                        <span>Delivered quantities</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <label class="form-label-odoo">Sales Price</label>
                                <div class="flex items-center flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                                    <span class="text-[13px] text-gray-500 pr-2">AED</span>
                                    <input type="number" step="0.01" name="sales_price" value="0.00" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <label class="form-label-odoo">Customer Taxes</label>
                                <div class="flex-1 flex items-center border-b border-gray-300 py-0.5 min-h-[26px]">
                                    <span class="inline-flex items-center bg-gray-100 text-gray-800 text-[11px] font-medium px-2 py-0.5 rounded-sm border border-gray-200">
                                        VAT 5% (Dubai)
                                        <button type="button" class="ml-1 text-gray-500 hover:text-gray-700">&times;</button>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <label class="form-label-odoo">Cost</label>
                                <div class="flex items-center flex-1 border-b border-gray-300 focus-within:border-odoo-purple focus-within:shadow-[0_1px_0_0_#017e84]">
                                    <span class="text-[13px] text-gray-500 pr-2">AED</span>
                                    <input type="number" step="0.01" name="cost_price" value="0.00" class="w-full border-none focus:ring-0 p-0 py-0.5 text-[13px] bg-transparent">
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <label class="form-label-odoo">Product Category</label>
                                <select name="category" class="form-input-odoo cursor-pointer">
                                    <option value="All">All</option>
                                    <option value="All / Saleable">All / Saleable</option>
                                    <option value="All / Expenses">All / Expenses</option>
                                </select>
                            </div>

                            <div class="flex items-center pt-2">
                                <label class="form-label-odoo">Brand</label>
                                <input type="text" name="brand" class="form-input-odoo">
                            </div>
                            
                            <div class="flex items-center">
                                <label class="form-label-odoo">Dimension</label>
                                <input type="text" name="dimension" class="form-input-odoo">
                            </div>
                            
                            <div class="flex items-center">
                                <label class="form-label-odoo">Model</label>
                                <input type="text" name="model" class="form-input-odoo">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Empty Tabs Placeholders -->
                <div id="tab-sales" class="p-6 hidden min-h-[400px] text-sm text-gray-500">Sales configuration will go here.</div>
                <div id="tab-purchase" class="p-6 hidden min-h-[400px] text-sm text-gray-500">Purchase configuration will go here.</div>
                <div id="tab-inventory" class="p-6 hidden min-h-[400px] text-sm text-gray-500">Inventory configuration will go here.</div>
                <div id="tab-accounting" class="p-6 hidden min-h-[400px] text-sm text-gray-500">Accounting configuration will go here.</div>

            </div>
        </main>
    </form>

    <script>
        // Simple tab switcher
        function switchTab(clickedTab, tabId) {
            // Remove active class from all tabs
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            // Add active class to clicked tab
            clickedTab.classList.add('active');
            
            // Hide all tab contents
            const contents = ['general', 'sales', 'purchase', 'inventory', 'accounting'];
            contents.forEach(id => {
                document.getElementById('tab-' + id).classList.add('hidden');
            });
            // Show target tab
            document.getElementById('tab-' + tabId).classList.remove('hidden');
        }
    </script>
</body>
</html>
