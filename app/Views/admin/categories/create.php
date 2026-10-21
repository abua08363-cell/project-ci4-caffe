<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="p-6 max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Kategori Baru</h1>
        <p class="text-sm text-gray-500">Tambahkan kelompok menu baru untuk produk kedai kopi.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="<?= base_url('admin/categories/store'); ?>" method="post">
            <?= csrf_field(); ?>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" value="<?= old('name'); ?>" placeholder="Contoh: Coffee, Non-Coffee, Pastry" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
            </div>

            <div class="flex justify-end gap-3">
                <a href="<?= base_url('admin/categories'); ?>"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-[#754a30] text-white text-sm font-semibold rounded-xl hover:bg-[#21140e] transition">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>

</div>
<?= $this->endSection(); ?>