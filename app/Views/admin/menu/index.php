<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <h1 class="text-3xl font-bold text-stone-800">
            Kelola Menu
        </h1>
        <p class="mt-2 text-stone-500">
            Daftar menu BrewNest Coffee
        </p>
    </div>

    <a href="<?= base_url('admin/menus/create') ?>"
        class="inline-flex items-center justify-center rounded-lg bg-amber-800 px-5 py-3 font-semibold text-white transition hover:bg-amber-900">
        + Tambah Menu
    </a>
</div>

<div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm sm:p-6">

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-xl font-bold text-stone-800">
            Data Menu
        </h2>

        <span class="rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-900">
            Total: <?= count($menus) ?> menu
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[750px] text-left text-sm">
            <thead class="bg-stone-100 text-stone-700">
                <tr>
                    <th class="rounded-l-lg p-4 w-12 text-center">No.</th>
                    <th class="p-4 w-20 text-center">Foto</th>
                    <th class="p-4">Nama Menu</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="rounded-r-lg p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-stone-100">
                <?php if (! empty($menus)): ?>
                    <?php foreach ($menus as $i => $menu): ?>
                        <tr class="transition hover:bg-amber-50/50">
                            <td class="p-4 text-stone-600 text-center font-medium">
                                <?= $i + 1 ?>
                            </td>

                            <!-- Kolom Foto Menu (Diperbaiki ke uploads/menus/) -->
                            <td class="p-4 text-center">
                                <div class="h-12 w-12 mx-auto overflow-hidden rounded-xl bg-stone-100 border border-stone-200 shadow-sm flex items-center justify-center">
                                    <?php if (!empty($menu['image'])): ?>
                                        <img src="<?= base_url('uploads/menus/' . $menu['image']) ?>"
                                            alt="<?= esc($menu['title']) ?>"
                                            class="h-full w-full object-cover">
                                    <?php else: ?>
                                        <span class="text-lg">☕</span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="p-4">
                                <div class="font-semibold text-stone-800">
                                    <?= esc($menu['title']) ?>
                                </div>
                                <div class="mt-0.5 text-xs text-stone-500 line-clamp-1">
                                    <?= esc($menu['description'] ?? 'Menu BrewNest Coffee') ?>
                                </div>
                            </td>

                            <td class="p-4">
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900">
                                    <?= esc($menu['category'] ?? 'Umum') ?>
                                </span>
                            </td>

                            <td class="p-4 font-semibold text-stone-800">
                                Rp <?= number_format((float) $menu['price'], 0, ',', '.') ?>
                            </td>

                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('admin/menus/edit/' . $menu['id']) ?>"
                                        class="rounded-lg bg-amber-800 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-900 transition">
                                        Edit
                                    </a>

                                    <form action="<?= base_url('admin/menus/delete/' . $menu['id']) ?>"
                                        method="post"
                                        onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                        <?= csrf_field() ?>

                                        <button type="submit"
                                            class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="p-10 text-center">
                            <div class="text-4xl">☕</div>
                            <p class="mt-3 font-semibold text-stone-700">
                                Belum ada data menu
                            </p>
                            <p class="mt-1 text-sm text-stone-500">
                                Tambahkan menu kopi untuk ditampilkan di sini.
                            </p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>