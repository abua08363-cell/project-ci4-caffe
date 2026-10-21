<header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-stone-200 bg-white px-6 shadow-sm">
    <div>
        <h1 class="text-lg font-bold text-stone-800">
            BrewNest Coffee
        </h1>
        <p class="mt-1 text-sm text-stone-500">
            Sistem Pengelolaan Kedai Kopi
        </p>
    </div>

    <div class="flex items-center gap-4">
        <div class="hidden text-right sm:block">
            <p class="font-semibold text-stone-800">
                <?= esc(session()->get('username') ?? 'Admin') ?>
            </p>
            <p class="text-xs text-stone-500">
                <?= esc(ucfirst(session()->get('role') ?? 'User')) ?>
            </p>
        </div>

        <!-- Foto Profil / Inisial di Navbar -->
        <?php if (session()->get('photo') && file_exists(FCPATH . 'uploads/users/' . session()->get('photo'))) : ?>
            <img src="<?= base_url('uploads/users/' . session()->get('photo')); ?>"
                alt="Foto Profil"
                class="h-11 w-11 rounded-full object-cover border border-stone-200 shadow-sm">
        <?php else : ?>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-100 font-bold text-amber-900 border border-amber-200">
                <?= strtoupper(substr(session()->get('username') ?? 'A', 0, 1)) ?>
            </div>
        <?php endif; ?>
    </div>
</header>