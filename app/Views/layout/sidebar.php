<aside class="flex min-h-screen w-64 shrink-0 flex-col bg-[#1c120c] text-stone-200 border-r border-[#2d1e15]">
    <?php $isAdmin = session()->get('role') === 'admin'; ?>

    <!-- Logo -->
    <div class="px-6 py-6 flex items-center gap-3 border-b border-[#2d1e15]">
        <span class="text-3xl text-amber-500">☕</span>
        <div>
            <h1 class="text-xl font-bold font-display text-white">
                BrewNest
            </h1>
            <p class="text-xs text-amber-500/80 tracking-wider font-medium">
                Coffee Admin
            </p>
        </div>
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex-1 space-y-1.5 px-4 py-6">

        <?php if ($isAdmin) : ?>
            <a href="<?= site_url('admin/dashboard') ?>"
                class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium transition <?= url_is('admin/dashboard') ? 'bg-[#754a30] text-white font-semibold' : 'text-stone-300 hover:bg-[#2c1d14] hover:text-white' ?>">
                <span>🏠</span> Home page
            </a>

            <a href="<?= site_url('admin/menus') ?>"
                class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium transition <?= url_is('admin/menus*') ? 'bg-[#754a30] text-white font-semibold' : 'text-stone-300 hover:bg-[#2c1d14] hover:text-white' ?>">
                <span>☕</span> Menu
            </a>

            <a href="<?= site_url('admin/users') ?>"
                class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium transition <?= url_is('admin/users*') ? 'bg-[#754a30] text-white font-semibold' : 'text-stone-300 hover:bg-[#2c1d14] hover:text-white' ?>">
                <span>👥</span> Partners
            </a>

            <a href="<?= site_url('admin/categories') ?>"
                class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium transition <?= url_is('admin/categories*') ? 'bg-[#754a30] text-white font-semibold' : 'text-stone-300 hover:bg-[#2c1d14] hover:text-white' ?>">
                <span>🗂️</span> Categories
            </a>

            <a href="<?= site_url('admin/settings') ?>"
                class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium transition <?= url_is('admin/settings*') ? 'bg-[#754a30] text-white font-semibold' : 'text-stone-300 hover:bg-[#2c1d14] hover:text-white' ?>">
                <span>⚙️</span> Settings
            </a>
        <?php endif; ?>

        <a href="<?= site_url('admin/orders') ?>"
            class="flex items-center gap-3 rounded-xl px-4 py-3 font-medium transition <?= url_is('admin/orders*') ? 'bg-[#754a30] text-white font-semibold' : 'text-stone-300 hover:bg-[#2c1d14] hover:text-white' ?>">
            <span>🕒</span> History
        </a>

    </nav>

    <!-- Menu Bawah (Donate & Logout) -->
    <div class="border-t border-[#2d1e15] p-4 space-y-1">
        <a href="<?= site_url('logout') ?>" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-stone-400 hover:bg-[#2c1d14] hover:text-white transition">
            <span>🚪</span> Log out
        </a>
    </div>

</aside>