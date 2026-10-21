<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="p-6 max-w-7xl mx-auto">

    <!-- Header & Tombol Tambah -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Kategori Menu</h1>
            <p class="text-sm text-gray-500">Kelola kategori produk untuk menu BrewNest Coffee.</p>
        </div>
        <a href="<?= base_url('admin/categories/create'); ?>"
            class="px-4 py-2.5 bg-[#754a30] text-white text-sm font-semibold rounded-xl hover:bg-[#21140e] transition flex items-center gap-2">
            <span>➕</span> Tambah Kategori
        </a>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-xl">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-xl">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Tabel Kategori -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6 w-20">#</th>
                        <th class="py-4 px-6">Nama Kategori</th>
                        <th class="py-4 px-6 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (!empty($categories) && is_array($categories)) : ?>
                        <?php $no = 1;
                        foreach ($categories as $category) : ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 font-semibold text-gray-900"><?= $no++; ?></td>
                                <td class="py-4 px-6 font-medium text-gray-900"><?= esc($category['name']); ?></td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= base_url('admin/categories/edit/' . $category['id']); ?>"
                                            class="px-3 py-1.5 bg-amber-500 text-white text-xs font-semibold rounded-lg hover:bg-amber-600 transition">
                                            Edit
                                        </a>
                                        <a href="<?= base_url('admin/categories/delete/' . $category['id']); ?>"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                            class="px-3 py-1.5 bg-rose-600 text-white text-xs font-semibold rounded-lg hover:bg-rose-700 transition">
                                            Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3" class="text-center py-10 text-stone-500 italic">
                                Belum ada kategori menu yang ditambahkan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>