<?php
require_once '../config/db.php';
require_once '../api/helpers.php';

$quotationNumber = $_GET['id'] ?? '';
$type = $_GET['type'] ?? 'invoice';
$docTitle = 'TAX INVOICE';
if ($type === 'quote') $docTitle = 'QUOTATION';
if ($type === 'delivery') $docTitle = 'DELIVERY NOTE';
$hidePricing = ($type === 'delivery');

$invoice = [
    'customerName' => 'N/A',
    'customerAddress' => 'N/A',
    'project' => 'N/A',
    'tel' => 'N/A',
    'trnCustomer' => 'N/A',
    
    'invNo' => 'N/A',
    'date' => 'N/A',
    'lpoNo' => 'N/A',
    'orderNo' => $quotationNumber,
    'trnCompany' => '104264202300003',
    'department' => 'N/A',
    'subject' => 'N/A',
    
    'subTotal' => '0.00',
    'vat' => '0.00',
    'total' => '0.00',
    'amountInWords' => 'ZERO ONLY',
    
    'paymentTerms' => 'N/A',
    'validity' => 'N/A'
];
$lines = [];

if ($quotationNumber) {
    try {
        $stmt = $pdo->prepare("
            SELECT q.*, c.customer_name, c.address as cust_address, c.phone, c.trn, c.company 
            FROM quotations q 
            LEFT JOIN customers c ON q.customer_id = c.id 
            WHERE q.quotation_number = ?
        ");
        $stmt->execute([$quotationNumber]);
        $data = $stmt->fetch();
        
        if ($data) {
            $invoice['customerName'] = strtoupper($data['company'] ?: $data['customer_name']);
            $invoice['customerAddress'] = strtoupper($data['address'] ?: $data['cust_address']);
            $invoice['tel'] = $data['phone'];
            $invoice['trnCustomer'] = $data['trn'] ?: 'N/A';
            $invoice['project'] = strtoupper($data['subject'] ?: 'N/A');
            $invoice['department'] = strtoupper($data['department'] ?: 'N/A');
            $invoice['subject'] = strtoupper($data['subject'] ?: 'N/A');
            
            if ($type === 'quote') {
                $invoice['invNo'] = $data['quotation_number'];
            } elseif ($type === 'delivery') {
                $invoice['invNo'] = str_replace('TEK-', 'DO-', $data['quotation_number']);
            } else {
                $invoice['invNo'] = str_replace('TEK-', 'INV-', $data['quotation_number']);
            }
            
            $invoice['date'] = date('d/m/Y', strtotime($data['quotation_date']));
            $invoice['lpoNo'] = 'NA';
            $invoice['subTotal'] = number_format($data['subtotal'], 2);
            $invoice['vat'] = number_format($data['tax_total'], 2);
            $invoice['total'] = number_format($data['grand_total'], 2);
            
            $invoice['paymentTerms'] = $data['payment_terms'] ?: 'Immediate Payment';
            $invoice['validity'] = $data['expiry_date'] ? date('d/m/Y', strtotime($data['expiry_date'])) : '7 Days';
            
            $invoice['amountInWords'] = strtoupper(getAmountInWords($data['grand_total']));
            
            // Fetch items
            $lineStmt = $pdo->prepare("
                SELECT qi.*, p.product_name, p.brand, p.model 
                FROM quotation_items qi 
                LEFT JOIN products p ON qi.product_id = p.id 
                WHERE qi.quotation_id = ? 
                ORDER BY qi.row_order ASC
            ");
            $lineStmt->execute([$data['id']]);
            $lines = $lineStmt->fetchAll();
        }
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice - <?php echo $invoice['invNo']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background-color: #f3f4f6; color: #000; }
        
        /* Print Styles */
        @media print {
            @page { size: A4 portrait; margin: 5mm 10mm; }
            body { background-color: white; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .print-container { 
                box-shadow: none !important; 
                margin: 0 !important; 
                padding: 0 !important; 
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                height: 100% !important;
                border: none !important;
                page-break-inside: avoid;
            }
            .cyan-bg { background-color: #5CE1E6 !important; -webkit-print-color-adjust: exact; color-adjust: exact; }
            table, th, td { border-color: #000 !important; }
            table { page-break-inside: avoid; }
            .totals-section, .signature-section { page-break-inside: avoid; }
        }
        
        .cyan-bg { background-color: #5CE1E6; }
        
        .invoice-table { width: 100%; border-collapse: collapse; }
        .invoice-table th, .invoice-table td { border: 1px solid #000; font-size: 11px; }
        .invoice-table th { color: #000; font-weight: bold; text-align: center; font-size: 11px; }
        .invoice-table td { padding: 4px 6px; }
        .invoice-table .empty-row td { border-top: none; border-bottom: none; }
        
        /* Web Preview Styling */
        .print-container {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 20px auto;
            padding: 10mm;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            color: #000;
        }

        .header-text { font-size: 11px; color: #1a4a6e; font-weight: bold; }
        .font-arial { font-family: Arial, Helvetica, sans-serif; }
    </style>
</head>
<body>

    <!-- Non-Printable Toolbar -->
    <div class="no-print bg-gray-800 text-white p-4 sticky top-0 z-50 shadow-md flex justify-between items-center">
        <div>
            <h2 class="font-semibold text-lg">Tax Invoice Print Preview</h2>
            <p class="text-xs text-gray-300">Layout: A4 Portrait</p>
        </div>
        <div class="flex gap-3">
            <a href="index.php" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded text-sm font-medium transition">Back to Quotation</a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded text-sm font-medium transition shadow">
                Download PDF
            </button>
            <button onclick="window.print()" class="px-4 py-2 bg-[#017e84] hover:bg-[#016267] rounded text-sm font-medium transition shadow flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Invoice
            </button>
        </div>
    </div>

    <!-- Printable Canvas -->
    <div class="print-container font-arial">
        
        <!-- Header Section -->
        <div class="text-center mb-4">
            <div class="flex justify-center items-center mb-1">
                <img src="../assets/images/logo-full.png" alt="True Elite" class="h-24 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <div class="hidden justify-center items-center gap-4">
                    <!-- Fallback Logo if image not found -->
                    <div class="w-16 h-16 bg-[#2997B2] rounded-full flex items-center justify-center text-white text-[32px] font-bold tracking-tighter">TE</div>
                    <div class="text-left mt-1">
                        <p class="text-[13px] font-bold text-[#2997B2] mb-0" dir="rtl">الاسم التجاري: تروي إيليت لتجارة معدات المطابخ - ذ.م.م - ش.ش.و</p>
                        <h1 class="text-[40px] font-bold text-[#2997B2] tracking-wide leading-none uppercase">TRUE ELITE</h1>
                        <h2 class="text-[14px] font-bold text-black tracking-wide mt-1 uppercase">KITCHEN EQUIPMENT TRADING - L.L.C - S.P.C</h2>
                    </div>
                </div>
            </div>
            <p class="text-[13px] font-bold text-black mt-1">Email: info@tureelite-kitchen.com, Phone No: +971-50-7885526</p>
            <h3 class="text-[20px] font-bold mt-2 underline underline-offset-4 decoration-2"><?php echo htmlspecialchars($docTitle); ?></h3>
        </div>

        <!-- Info Grid -->
        <div class="flex justify-between text-[11px] font-bold leading-tight mb-2 px-1">
            <!-- Left: Customer Info -->
            <div class="w-[60%] pr-4">
                <table class="whitespace-nowrap w-full">
                    <tr><td class="pr-1 pb-2 w-20">NAME</td><td class="pb-2 w-2">:</td><td class="pb-2 whitespace-normal break-words"><?php echo $invoice['customerName']; ?></td></tr>
                    <tr><td class="pr-1 pb-2 align-top">ADDRESS</td><td class="pb-2 align-top">:</td><td class="pb-2 whitespace-normal break-words"><?php echo $invoice['customerAddress']; ?></td></tr>
                    <tr><td class="pr-1 pb-2">TEL</td><td class="pb-2">:</td><td class="pb-2"><?php echo $invoice['tel']; ?></td></tr>
                    <?php if ($type === 'quote'): ?>
                    <tr><td class="pr-1 pb-2 align-top">DEPARTMENT (TO)</td><td class="pb-2 align-top">:</td><td class="pb-2 whitespace-normal break-words"><?php echo $invoice['department']; ?></td></tr>
                    <tr><td class="pr-1 pb-2 align-top">SUBJECT</td><td class="pb-2 align-top">:</td><td class="pb-2 whitespace-normal break-words"><?php echo $invoice['subject']; ?></td></tr>
                    <?php else: ?>
                    <tr><td class="pr-1 pb-2">TRN</td><td class="pb-2">:</td><td class="pb-2"><?php echo $invoice['trnCustomer']; ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
            <!-- Right: Invoice Info -->
            <div class="w-[40%] pl-4">
                <table class="whitespace-nowrap w-full">
                    <tr><td class="pr-1 pb-2 w-24"><?php 
                        if ($type === 'quote') echo 'QUOTATION REF';
                        elseif ($type === 'delivery') echo 'DELIVERY NOTE NO';
                        else echo 'INV NO';
                    ?></td><td class="pb-2 w-2">:</td><td class="pb-2"><?php echo $invoice['invNo']; ?></td></tr>
                    <tr><td class="pr-1 pb-2">DATE</td><td class="pb-2">:</td><td class="pb-2"><?php echo $invoice['date']; ?></td></tr>
                    <?php if ($type === 'delivery'): ?>
                    <tr><td class="pr-1 pb-2">LPO NO</td><td class="pb-2">:</td><td class="pb-2"><?php echo $invoice['lpoNo']; ?></td></tr>
                    <tr><td class="pr-1 pb-2">ORDER NO</td><td class="pb-2">:</td><td class="pb-2"><?php echo $invoice['orderNo']; ?></td></tr>
                    <?php endif; ?>
                    <?php if ($type !== 'quote'): ?>
                    <tr><td class="pr-1 pb-2">TRN</td><td class="pb-2">:</td><td class="pb-2"><?php echo $invoice['trnCompany']; ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Main Items & Totals Table -->
        <div class="flex-1 min-h-[150px] mb-2 flex flex-col">
            <table class="invoice-table flex-1 w-full">
                <thead>
                    <tr class="cyan-bg">
                        <th class="py-2 w-8 uppercase">SL</th>
                        <th class="py-2 uppercase">ITEM DESCRIPTION</th>
                        <th class="py-2 w-16 uppercase">PCS</th>
                        <?php if (!$hidePricing): ?>
                        <th class="py-2 w-24 uppercase">UNIT PRICE</th>
                        <th class="py-2 w-24 uppercase">VAT 5 %</th>
                        <th class="py-2 w-28 uppercase">NET AMOUNT</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="align-top font-bold">
                    <?php if (!empty($lines)): ?>
                        <?php $sl = 1; foreach ($lines as $line): ?>
                            <?php if ($line['is_section'] || $line['is_note']): ?>
                            <tr>
                                <td class="border-t-0 border-b-0 text-center py-2"><?php echo $sl++; ?></td>
                                <td colspan="<?php echo $hidePricing ? '2' : '5'; ?>" class="border-t-0 border-b-0 py-2 <?php echo $line['is_section'] ? 'font-bold' : 'italic font-normal text-gray-600'; ?>">
                                    <?php echo htmlspecialchars($line['description']); ?>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <td class="text-center border-t-0 border-b-0 py-2"><?php echo $sl++; ?></td>
                                <td class="border-t-0 border-b-0 py-2">
                                    <?php 
                                        $descParts = explode(' | ', $line['description']);
                                        echo htmlspecialchars($descParts[0]); 
                                        if (count($descParts) > 1) {
                                            array_shift($descParts);
                                            echo '<br><span class="font-normal">' . htmlspecialchars(implode(' | ', $descParts)) . '</span>';
                                        }
                                    ?>
                                </td>
                                <td class="text-center border-t-0 border-b-0 py-2"><?php echo rtrim(rtrim($line['quantity'], '0'), '.'); ?></td>
                                <?php if (!$hidePricing): ?>
                                <td class="text-center border-t-0 border-b-0 py-2"><?php echo number_format($line['unit_price'], 2); ?></td>
                                <td class="text-center border-t-0 border-b-0 py-2"><?php echo number_format($line['total'] - $line['subtotal'], 2); ?></td>
                                <td class="text-center border-t-0 border-b-0 py-2"><?php echo number_format($line['total'], 2); ?></td>
                                <?php endif; ?>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Empty Spacer Row if no items -->
                        <tr>
                            <td class="border-t-0 border-b-0"></td>
                            <td class="border-t-0 border-b-0 text-center text-gray-400 font-normal">No items found or Quotation not saved yet.</td>
                            <td class="border-t-0 border-b-0"></td>
                            <?php if (!$hidePricing): ?>
                            <td class="border-t-0 border-b-0"></td>
                            <td class="border-t-0 border-b-0"></td>
                            <td class="border-t-0 border-b-0"></td>
                            <?php endif; ?>
                        </tr>
                    <?php endif; ?>
                    <!-- This row stretches to fill the remaining height -->
                    <tr style="height: 100%;">
                        <td class="border-t-0 border-b-0"></td>
                        <td class="border-t-0 border-b-0"></td>
                        <td class="border-t-0 border-b-0"></td>
                        <?php if (!$hidePricing): ?>
                        <td class="border-t-0 border-b-0"></td>
                        <td class="border-t-0 border-b-0"></td>
                        <td class="border-t-0 border-b-0"></td>
                        <?php endif; ?>
                    </tr>
                </tbody>
                <?php if (!$hidePricing): ?>
                <tfoot class="font-bold">
                    <tr>
                        <td colspan="4" class="align-middle px-2 py-1 border-b border-t text-[11px]">
                            COMPANY NAME: TRUE ELITE KITCHEN EQUIPMENT TRADING LLC SPC
                        </td>
                        <td class="text-center py-1 text-[11px] border-b">SUB TOTAL</td>
                        <td class="text-center py-1 text-[11px] border-b"><?php echo $invoice['subTotal']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="align-middle px-2 py-1 border-r border-b border-t-0 text-[11px] w-[50%]">
                            BANK NAME: ABU DHABI COMMERCIAL BANK
                        </td>
                        <td colspan="2" class="align-middle px-2 py-1 border-l-0 border-b border-t-0 text-[11px]">
                            ACCOUNT NO: 14459422920001
                        </td>
                        <td class="text-center py-1 text-[11px] border-b">DISCOUNT</td>
                        <td class="text-center py-1 text-[11px] border-b"><?php echo isset($data['discount_amount']) ? number_format($data['discount_amount'], 2) : '0.00'; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="align-middle px-2 py-1 border-r border-b border-t-0 text-[11px]">
                            IBAN: AE790030014459422920001
                        </td>
                        <td colspan="2" class="align-middle px-2 py-1 border-l-0 border-b border-t-0 text-[11px]">
                            SWIFT: ADCBAEAAXXX
                        </td>
                        <td class="text-center py-1 text-[11px] border-b">VAT 5 %</td>
                        <td class="text-center py-1 text-[11px] border-b"><?php echo $invoice['vat']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="align-middle px-2 py-1 border-t-0 text-[11px]">
                            IN WORDS: <?php echo $invoice['amountInWords']; ?>
                        </td>
                        <td class="text-center py-1 text-[11px]">TOTAL</td>
                        <td class="text-center py-1 text-[11px]"><?php echo $invoice['total']; ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>

        <!-- Signature Section -->
        <?php if ($type === 'quote'): ?>
        <div class="mt-6 font-arial px-1">
            <h3 class="text-[11px] font-bold underline mb-1">AGREEMENT & CONDITIONS:</h3>
            <p class="text-[11px] mb-4">The agreement and conditions will be standard as per the company policies unless specified otherwise.</p>
            
            <h3 class="text-[11px] font-bold underline mb-1">OTHER TERMS & CONDITIONS:</h3>
            <ul class="text-[11px] list-disc pl-5 mb-8">
                <li>Payment: <?php echo htmlspecialchars($invoice['paymentTerms']); ?></li>
                <li>Validity: Valid until <?php echo htmlspecialchars($invoice['validity']); ?></li>
                <li>Delivery: Subject to availability.</li>
            </ul>
            
            <div class="flex justify-between items-start mt-12">
                <div class="w-[45%] flex flex-col">
                    <p class="text-[11px] font-bold mb-10">For True Elite Kitchen Equipment Trading LLC-SPC</p>
                    <div class="border-t border-black w-full mt-2"></div>
                    <p class="text-[11px] font-bold mt-1 text-center">Authorized Signatory</p>
                </div>
                <div class="w-[45%] flex flex-col">
                    <p class="text-[11px] font-bold mb-10">Client Acceptance</p>
                    <div class="border-t border-black w-full mt-2"></div>
                    <p class="text-[11px] font-bold mt-1 text-center">Sign & Company Seal</p>
                </div>
            </div>
        </div>
        
        <?php elseif ($type === 'delivery'): ?>
        <div class="signature-section flex justify-between items-start mt-10 px-1">
            <!-- Left Side -->
            <div class="w-1/2">
                <p class="text-[11px] font-bold mb-4">Received the above goods in full and good condition</p>
                <div class="w-[260px] flex flex-col gap-4 text-[11px] font-bold">
                    <div class="flex items-end"><span class="w-20">NAME</span><span class="mr-2">:</span><span class="flex-1 border-b border-black border-dashed"></span></div>
                    <div class="flex items-end"><span class="w-20">PHONE</span><span class="mr-2">:</span><span class="flex-1 border-b border-black border-dashed"></span></div>
                    <div class="flex items-end"><span class="w-20">SIGN/SEAL</span><span class="mr-2">:</span><span class="flex-1 border-b border-black border-dashed"></span></div>
                </div>
            </div>
            
            <!-- Right Side -->
            <div class="w-1/2 flex flex-col items-end">
                <p class="text-[11px] font-bold">True Elite Kitchen Equipment Trading LLC-SPC</p>
            </div>
        </div>
        
        <?php else: ?>
        <div class="signature-section flex justify-between items-start mt-10 px-1">
            <div class="w-1/2">
                <p class="text-[11px] font-bold mb-8">Customer Signature & Seal</p>
                <div class="w-48 border-b border-black"></div>
            </div>
            <div class="w-1/2 flex flex-col items-end">
                <p class="text-[11px] font-bold">True Elite Kitchen Equipment Trading LLC-SPC</p>
            </div>
        </div>
        <?php endif; ?>
        
    </div>

</body>
</html>
