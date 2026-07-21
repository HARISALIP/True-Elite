<?php
$pageTitle = 'Home | True Elite Admin';
// No specific module name for the home dashboard
$moduleName = '';
require_once 'includes/header.php';
?>

<!-- Premium Brand Gradient Background -->
<div class="fixed inset-0 bg-gradient-to-br from-[#2997B2] via-[#228299] to-[#1A6478] z-[-1]"></div>

<?php 
require_once 'includes/sidebar.php'; 
require_once 'includes/topbar.php'; 
?>

<!-- Main Content Area -->
<main class="pt-20 pl-16 min-h-screen flex items-center justify-center p-6">
    
    <div class="max-w-5xl w-full mx-auto">
        <div class="text-center mb-12 flex flex-col items-center">
            <div class="bg-white p-4 rounded-xl shadow-lg mb-6 inline-block">
                <img src="/assets/images/logo-full.png" alt="True Elite" class="h-20 object-contain">
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3 tracking-tight drop-shadow-md">Welcome to True Elite ERP</h1>
            <p class="text-white/90 text-lg font-medium drop-shadow-sm">Select an application to get started.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 px-4">
            
            <!-- Sales App -->
            <a href="sales/quotations.php" class="group bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-8 flex flex-col items-center justify-center text-center transition-all duration-300 hover:-translate-y-2 hover:bg-white/20 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.3)] hover:border-white/40 cursor-pointer">
                <div class="w-20 h-20 mb-4 bg-gradient-to-br from-[#017e84] to-[#01595e] rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white tracking-wide">Sales</h3>
            </a>

            <!-- Accounting App -->
            <a href="accounting/index.php" class="group bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-8 flex flex-col items-center justify-center text-center transition-all duration-300 hover:-translate-y-2 hover:bg-white/20 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.3)] hover:border-white/40 cursor-pointer">
                <div class="w-20 h-20 mb-4 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white tracking-wide">Accounting</h3>
            </a>

            <!-- Purchase App -->
            <a href="purchase/index.php" class="group bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-8 flex flex-col items-center justify-center text-center transition-all duration-300 hover:-translate-y-2 hover:bg-white/20 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.3)] hover:border-white/40 cursor-pointer">
                <div class="w-20 h-20 mb-4 bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white tracking-wide">Purchase</h3>
            </a>

            <!-- Inventory App -->
            <a href="inventory/index.php" class="group bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-8 flex flex-col items-center justify-center text-center transition-all duration-300 hover:-translate-y-2 hover:bg-white/20 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.3)] hover:border-white/40 cursor-pointer">
                <div class="w-20 h-20 mb-4 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white tracking-wide">Inventory</h3>
            </a>

        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
