<?= $this->extend('layout/template'); ?> <!-- Sesuaikan dengan layout admin Anda -->

<?= $this->section('content'); ?>
<div class="p-6 max-w-5xl mx-auto">

    <!-- Header Page -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Menu Baru</h1>
            <p class="text-sm text-gray-500">Isi formulir di bawah untuk menambahkan menu kopi/makanan ke katalog BrewNest.</p>
        </div>
        <a href="<?= base_url('admin/menus'); ?>" class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full transition font-medium">
            ← Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <form action="<?= base_url('admin/menus/store'); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field(); ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Kolom Kiri: Input Data Utama (2 Kolom) -->
                <div class="lg:col-span-2 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                        <input type="text" name="title" placeholder="Contoh: Espresso Macchiato" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#754a30] focus:outline-none text-gray-800">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="category" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#754a30] focus:outline-none bg-white text-gray-800">
                                <option value="" selected disabled>-- Pilih Kategori --</option>
                                <option value="Coffee">Coffee</option>
                                <option value="Non-Coffee">Non-Coffee</option>
                                <option value="Pastry & Snack">Pastry & Snack</option>
                                <option value="Dessert">Dessert</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="price" placeholder="25000" required
                                    class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#754a30] focus:outline-none text-gray-800">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Menu</label>
                        <textarea name="description" rows="4" placeholder="Tulis deskripsi rinci rasa, cita rasa, atau bahan menu..."
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#754a30] focus:outline-none text-gray-800"></textarea>
                    </div>
                </div>

                <!-- Kolom Kanan: Upload Foto -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Menu</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center bg-gray-50 hover:bg-gray-100 transition relative">
                        <div class="space-y-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP max 2MB</p>
                            <label for="image" class="inline-block px-4 py-2 bg-gray-800 text-white text-xs font-semibold rounded-lg cursor-pointer hover:bg-gray-700 transition">
                                Pilih File
                            </label>
                            <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                        </div>
                    </div>

                    <!-- Preview Gambar -->
                    <div id="preview-container" class="hidden mt-3 text-center">
                        <img id="image-preview" src="#" alt="Preview" class="mx-auto h-36 w-full object-cover rounded-xl border border-gray-200 shadow-sm">
                    </div>
                </div>

            </div>

            <hr class="border-gray-100 my-6">

            <!-- Tombol Aksi -->
            <div class="flex justify-end gap-3">
                <a href="<?= base_url('admin/menus'); ?>" class="px-6 py-2.5 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-100 transition text-sm font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-[#754a30] text-white hover:bg-[#21140e] transition text-sm font-semibold shadow-md">
                    Simpan Menu
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Script Preview Gambar -->
<script>
    function previewImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('image-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?= $this->endSection(); ?>