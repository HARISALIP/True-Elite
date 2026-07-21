<?php
require_once '../db.php';

try {
    $stmt = $pdo->query("SELECT * FROM quotes ORDER BY created_at DESC");
    $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales | True Elite Admin</title>
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
                            text: '#374151'
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
        .table-row-hover:hover { background-color: #f9fafb; cursor: pointer; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Navbar -->
    <header class="odoo-nav h-14 flex items-center justify-between px-4 fixed top-0 left-0 right-0 z-50 text-white shadow-sm">
        <div class="flex items-center gap-4">
            <a href="index.php" class="p-1.5 hover:bg-white/10 rounded-md transition-colors" title="App Launcher">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"/>
                </svg>
            </a>
            <span class="font-semibold text-sm">Sales</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">A</div>
        </div>
    </header>

    <!-- Toolbar -->
    <div class="pt-14 bg-white border-b border-gray-200 sticky top-14 z-40 px-4 py-2.5 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="create_product.php" class="btn-primary px-3 py-1.5 rounded-sm text-sm font-medium shadow-sm">
                NEW PRODUCT
            </a>
            <button class="px-3 py-1.5 bg-white border border-gray-300 rounded-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                GENERATE INVOICES
            </button>
        </div>
        <div class="relative w-64">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" placeholder="Search..." class="w-full pl-9 pr-3 py-1 text-sm border border-gray-300 rounded-sm focus:outline-none focus:border-odoo-purple focus:ring-1 focus:ring-odoo-purple">
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-4 overflow-auto">
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-medium">
                    <tr>
                        <th class="px-4 py-2.5 w-10">
                            <input type="checkbox" class="rounded-sm border-gray-300 text-odoo-purple focus:ring-odoo-purple">
                        </th>
                        <th class="px-4 py-2.5">Quotation Date</th>
                        <th class="px-4 py-2.5">Customer</th>
                        <th class="px-4 py-2.5">WhatsApp Number</th>
                        <th class="px-4 py-2.5">Product of Interest</th>
                        <th class="px-4 py-2.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <?php if (empty($quotes)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                No quotations found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($quotes as $quote): ?>
                            <tr class="table-row-hover">
                                <td class="px-4 py-2.5">
                                    <input type="checkbox" class="rounded-sm border-gray-300 text-odoo-purple focus:ring-odoo-purple">
                                </td>
                                <td class="px-4 py-2.5"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($quote['created_at']))); ?></td>
                                <td class="px-4 py-2.5 font-medium"><?php echo htmlspecialchars($quote['customer_name']); ?></td>
                                <td class="px-4 py-2.5"><?php echo htmlspecialchars($quote['customer_phone']); ?></td>
                                <td class="px-4 py-2.5"><?php echo htmlspecialchars($quote['product_name']); ?></td>
                                <td class="px-4 py-2.5">
                                    <?php 
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        if (strtolower($quote['status']) === 'sent') $statusClass = 'bg-green-100 text-green-800';
                                        if (strtolower($quote['status']) === 'cancelled') $statusClass = 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-medium tracking-wide uppercase <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($quote['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
