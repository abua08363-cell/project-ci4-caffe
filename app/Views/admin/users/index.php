<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="p-6 max-w-7xl mx-auto">

    <!-- Header & Tombol Tambah -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Partners / Users</h1>
            <p class="text-sm text-gray-500">Kelola akun pengguna, staff, atau partner yang terhubung dengan BrewNest Coffee.</p>
        </div>
        <a href="<?= base_url('admin/users/create'); ?>"
            class="px-4 py-2.5 bg-[#754a30] text-white text-sm font-semibold rounded-xl hover:bg-[#21140e] transition flex items-center gap-2">
            <span>➕</span> Tambah User
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

    <!-- Tabel Users -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">No</th>
                        <th class="py-4 px-6">Foto</th> <!-- PERUBAHAN 1: Kolom Foto -->
                        <th class="py-4 px-6">Nama / Username</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Role / Level</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (!empty($users) && is_array($users)) : ?>
                        <?php $no = 1;
                        foreach ($users as $user) : ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 font-semibold text-gray-900"><?= $no++; ?></td>

                                <!-- PERUBAHAN 2: Tampilan Foto Profile / Avatar Inisial -->
                                <td class="py-4 px-6">
                                    <?php if (!empty($user['photo']) && file_exists(FCPATH . 'uploads/users/' . $user['photo'])) : ?>
                                        <img src="<?= base_url('uploads/users/' . $user['photo']); ?>"
                                            alt="Foto Profil"
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    <?php else : ?>
                                        <div class="w-10 h-10 rounded-full bg-amber-100 text-[#754a30] font-bold flex items-center justify-center text-sm border border-amber-200">
                                            <?= strtoupper(substr($user['username'] ?? $user['name'] ?? 'A', 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="py-4 px-6 font-medium text-gray-900"><?= esc($user['username'] ?? $user['name'] ?? 'Admin'); ?></td>
                                <td class="py-4 px-6"><?= esc($user['email'] ?? '-'); ?></td>
                                <td class="py-4 px-6">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full <?= ($user['role'] ?? 'admin') === 'admin' ? 'bg-amber-100 text-[#754a30]' : 'bg-blue-100 text-blue-700'; ?>">
                                        <?= ucfirst(esc($user['role'] ?? 'Administrator')); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= base_url('admin/users/edit/' . $user['id']); ?>"
                                            class="px-3 py-1.5 bg-amber-500 text-white text-xs font-semibold rounded-lg hover:bg-amber-600 transition">
                                            Edit
                                        </a>
                                        <a href="<?= base_url('admin/users/delete/' . $user['id']); ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                                            class="px-3 py-1.5 bg-rose-600 text-white text-xs font-semibold rounded-lg hover:bg-rose-700 transition">
                                            Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center py-10 text-stone-500 italic">
                                Belum ada data partner atau user terdaftar.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>