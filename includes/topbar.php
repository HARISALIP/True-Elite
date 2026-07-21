<!-- Navbar (Topbar) -->
<header class="bg-[#2997B2] h-12 flex items-center justify-between px-4 fixed top-0 left-0 right-0 z-40 text-white shadow-sm">
    <div class="flex items-center gap-6 text-[13px] font-medium">
        <a href="/index.php" class="hover:bg-white/20 p-1.5 rounded-md transition-colors -ml-2" title="App Launcher">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"/>
            </svg>
        </a>
        <a href="/index.php" class="flex items-center gap-2">
            <img src="/assets/images/logo-icon.png" alt="True Elite" class="h-8 object-contain bg-white rounded-sm p-1">
        </a>
        <span class="text-[16px] font-normal cursor-pointer -ml-2"><?php echo htmlspecialchars($moduleName ?? ''); ?></span>
        
        <!-- Module Links -->
        <nav class="hidden md:flex items-center gap-1 text-white/90">
            <a href="#" class="hover:text-white hover:bg-white/10 px-3 py-1.5 rounded transition-colors">Transaction</a>
            <a href="#" class="hover:text-white hover:bg-white/10 px-3 py-1.5 rounded transition-colors">Master Data</a>
            <a href="#" class="hover:text-white hover:bg-white/10 px-3 py-1.5 rounded transition-colors">Reports</a>
        </nav>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 cursor-pointer hover:bg-white/10 p-1 rounded-md transition-colors">
            <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold shadow-inner">A</div>
            <span class="text-xs font-medium hidden md:block mr-1">Admin</span>
        </div>
    </div>
</header>
