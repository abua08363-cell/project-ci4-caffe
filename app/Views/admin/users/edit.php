<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="p-6 max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Partner / User</h1>
        <p class="text-sm text-gray-500">Perbarui informasi akun partner atau staff.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <!-- PERUBAHAN 1: Tambahkan enctype="multipart/form-data" agar form bisa upload file -->
        <form action="<?= base_url('admin/users/update/' . $user['id']); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <!-- PERUBAHAN 2: Input Upload Foto & Preview -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Profil</label>
                <div class="flex items-center gap-4">
                    <!-- Preview Foto Lama / Inisial -->
                    <div id="photoPreviewContainer">
                        <?php if (!empty($user['photo']) && file_exists(FCPATH . 'uploads/users/' . $user['photo'])) : ?>
                            <img id="currentPhoto" src="<?= base_url('uploads/users/' . $user['photo']); ?>"
                                alt="Foto Profil"
                                class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                        <?php else : ?>
                            <div id="currentPhoto" class="w-16 h-16 rounded-full bg-amber-100 text-[#754a30] font-bold text-lg flex items-center justify-center border-2 border-amber-200">
                                <?= strtoupper(substr($user['username'] ?? 'A', 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1">
                        <input type="file" name="photo" id="photoInput" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-[#754a30] hover:file:bg-amber-100 transition cursor-pointer">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG (Maks. 2MB). Kosongkan jika tidak ingin mengubah foto.</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <input type="text" name="username" value="<?= esc($user['username']); ?>" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="<?= esc($user['email']); ?>" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
            </div>

            <!-- Input Password Baru dengan Tombol Mata (Eye Toggle) -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ingin mengubah password)</span></label>
                <div class="relative">
                    <input type="password" name="password" id="passwordInput"
                        class="w-full px-4 py-2.5 pr-12 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">

                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <!-- Icon Mata Tertutup (Default) -->
                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role / Level</label>
                <select name="role" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="staff" <?= ($user['role'] ?? '') === 'staff' ? 'selected' : ''; ?>>Staff</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="<?= base_url('admin/users'); ?>"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-[#754a30] text-white text-sm font-semibold rounded-xl hover:bg-[#21140e] transition">
                    Perbarui User
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Script JavaScript untuk Toggle Show/Hide Password & Live Preview Foto -->
<script>
    // Toggle Password Visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'text') {
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
        } else {
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>`;
        }
    });

    // Live Preview Image Upload
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