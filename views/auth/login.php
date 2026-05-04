<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BST</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white p-10 rounded-xl shadow-lg">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-emerald-700">Login BST</h2>
            <p class="mt-2 text-sm text-gray-600">Bank Sampah TKM</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 text-sm text-red-700 font-medium">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 text-sm text-emerald-700 font-medium">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- ACTION DIUBAH KE /auth/login -->
        <form action="<?= BASE_URL ?>/auth/login" method="POST" class="space-y-6">
            
            <!-- 🛡️ INJEKSI CSRF TOKEN -->
            <?= Security::csrf_field(); ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border rounded-md focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-md focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <button type="submit" class="w-full py-2.5 px-4 text-white bg-emerald-600 rounded-md hover:bg-emerald-700 font-medium shadow-md shadow-emerald-500/30 transition-all">
                Masuk Sistem
            </button>
        </form>
    </div>
</body>
</html>