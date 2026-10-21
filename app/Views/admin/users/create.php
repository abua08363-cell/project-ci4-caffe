<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="p-6 max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Partner / User Baru</h1>
        <p class="text-sm text-gray-500">Masukkan data akun untuk staff atau admin baru.</p>
    </div>

    <!-- Tambahkan enctype="multipart/form-data" agar form bisa mengirim file foto -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="<?= base_url('admin/users/store'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <!-- Input Upload Foto & Live Preview -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Profil</label>
                <div class="flex items-center gap-4">
                    <div id="photoPreviewContainer">
                        <div class="w-16 h-16 rounded-full bg-amber-100 text-[#754a30] font-bold text-lg flex items-center justify-center border-2 border-amber-200">
                            👤
                        </div>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="photo" id="photoInput" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-[#754a30] hover:file:bg-amber-100 transition cursor-pointer">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG (Maks. 2MB).</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <input type="text" name="username" value="<?= old('username'); ?>" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="<?= old('email'); ?>" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role / Level</label>
                <select name="role" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="<?= base_url('admin/users'); ?>"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-[#754a30] text-white text-sm font-semibold rounded-xl hover:bg-[#21140e] transition">
                    Simpan User
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Script Live Preview Foto saat Dipilih -->
<script>
    const photoInput = document.getElementById('photoInput');
    const photoPreviewContainer = document.getElementById('photoPreviewContainer');

    photoInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreviewContainer.innerHTML = `<img src="${e.target.result}" class="w-16 h-16 rounded-full object-cover border-2 border-amber-500 shadow-sm">`;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
<?= $this->endSection(); ?>