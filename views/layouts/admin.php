<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - BST</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
    
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 w-64 bg-emerald-800 text-white transition-transform duration-300 md:relative md:translate-x-0 flex flex-col z-50">
        <div class="h-16 flex items-center justify-center bg-emerald-900 border-b border-emerald-950">
            <h1 class="text-xl font-bold tracking-wider">BST</h1>
        </div>
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="<?= BASE_URL ?>/dashboard" class="block py-2.5 px-4 rounded hover:bg-emerald-700">📊 Dashboard</a>
            
            <?php if($_SESSION['role'] === 'admin'): ?>
                <div class="pt-4 pb-2"><p class="px-4 text-xs font-semibold text-emerald-300 uppercase">Master Data</p></div>
                <a href="<?= BASE_URL ?>/pengaturan" class="block py-2.5 px-4 rounded hover:bg-emerald-700">⚙️ Pengaturan</a>
            <?php endif; ?>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden w-full">
        <header class="h-16 bg-white shadow-sm border-b flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden mr-4 text-gray-500">☰</button>
                <h2 class="text-xl font-semibold"><?= $title ?></h2>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-600"><?= htmlspecialchars($_SESSION['nama']) ?></span>
                <a href="<?= BASE_URL ?>/auth/logout" class="text-sm font-bold text-red-500 hover:underline">Logout</a>
            </div>
        </header>
        
        <main class="flex-1 p-6 overflow-auto">
            <?php require_once $content; ?>
        </main>
    </div>
</body>
</html>