<?php
$pageTitle = $pageTitle ?? 'True Elite Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/logo-icon.png">
    <link rel="apple-touch-icon" href="/assets/images/logo-icon.png">
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
        .btn-primary { background-color: #017e84; color: white; transition: background 0.2s, box-shadow 0.2s; }
        .btn-primary:hover { background-color: #016267; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-secondary { background-color: white; color: #374151; border: 1px solid #d1d5db; transition: background 0.2s, box-shadow 0.2s; }
        .btn-secondary:hover { background-color: #f9fafb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        
        .form-input-odoo {
            border: none;
            border-bottom: 1px solid #d1d5db;
            border-radius: 0;
            padding: 2px 0;
            width: 100%;
            font-size: 13px;
            color: #111827;
            background-color: transparent;
            transition: border-color 0.2s, box-shadow 0.2s;
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
            transition: color 0.2s, border-color 0.2s;
        }
        .nav-tab:hover { color: #111827; }
        .nav-tab.active {
            color: #017e84;
            border-bottom-color: #017e84;
            font-weight: 500;
        }
        .table-row-hover:hover { background-color: #f9fafb; cursor: pointer; transition: background-color 0.15s ease-in-out; }

        /* Smoother animations & Scrollbar */
        * { scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="min-h-screen flex flex-col text-[#374151]">
