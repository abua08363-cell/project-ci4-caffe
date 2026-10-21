<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="p-6 max-w-6xl mx-auto">

    <!-- Header Page -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan Sistem & Kedai</h1>
            <p class="text-sm text-gray-500">Konfigurasi operasional kedai, informasi kontak, pembayaran, dan sistem BrewNest.</p>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-2xl border border-green-200 flex items-center gap-2">
            <span>✅</span> <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/settings/update'); ?>" method="post">
        <?= csrf_field(); ?>

        <div class="space-y-6">

            <!-- 1. Operasional Kedai (Status Buka/Tutup & Pajak) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                    <span class="text-xl">⚙️</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Status Operasional & Pesanan</h2>
                        <p class="text-xs text-gray-500">Atur ketersediaan penerimaan pesanan online.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Kedai (Online Order)</label>
                        <select name="store_status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                            <option value="open">🟢 Buka (Menerima Pesanan)</option>
                            <option value="closed">🔴 Tutup (Toko Libur / Pemesanan Dibatasi)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Operasional</label>
                        <input type="text" name="opening_hours" value="08:00 - 22:00 WIB"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pajak / PB1 (%)</label>
                        <input type="number" name="tax_percentage" value="10"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    </div>
                </div>
            </div>

            <!-- 2. Informasi Kedai & Kontak -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                    <span class="text-xl">☕</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Profil & Kontak Kedai</h2>
                        <p class="text-xs text-gray-500">Informasi ini akan ditampilkan di header/footer website pembeli.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kedai</label>
                        <input type="text" name="store_name" value="BrewNest Coffee" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Slogan / Tagline</label>
                        <input type="text" name="store_tagline" value="Hangatnya Kopi, Nyamannya Rumah"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp / Order Hotline</label>
                        <input type="text" name="phone_number" value="+62 812-3456-7890"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Kontak</label>
                        <input type="email" name="contact_email" value="hello@brewnest.com"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Kedai</label>
                    <textarea name="store_address" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">Jl. Kampus Unilam No. 12, Serang, Banten</textarea>
                </div>
            </div>

            <!-- 3. Metode Pembayaran -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                    <span class="text-xl">💳</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Metode Pembayaran Ditampilkan</h2>
                        <p class="text-xs text-gray-500">Pilih opsi pembayaran yang diizinkan untuk pelanggan saat checkout.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center gap-3 p-3.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-stone-50">
                        <input type="checkbox" name="pay_qris" checked class="w-4 h-4 text-[#754a30] rounded border-gray-300 focus:ring-[#754a30]">
                        <span class="text-sm font-medium text-gray-700">QRIS / E-Wallet</span>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-stone-50">
                        <input type="checkbox" name="pay_cash" checked class="w-4 h-4 text-[#754a30] rounded border-gray-300 focus:ring-[#754a30]">
                        <span class="text-sm font-medium text-gray-700">Bayar di Kasir (Cash)</span>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-stone-50">
                        <input type="checkbox" name="pay_transfer" checked class="w-4 h-4 text-[#754a30] rounded border-gray-300 focus:ring-[#754a30]">
                        <span class="text-sm font-medium text-gray-700">Transfer Bank</span>
                    </label>
                </div>
            </div>

            <!-- 4. Keamanan Akun Admin -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                    <span class="text-xl">🔒</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Akun Administrator</h2>
                        <p class="text-xs text-gray-500">Pengaturan akses login akun kamu saat ini.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" value="<?= esc($user['username'] ?? 'admin'); ?>" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Admin</label>
                        <input type="email" name="email" value="<?= esc($user['email'] ?? 'admin@caffe.com'); ?>" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Password <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#754a30]">
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end gap-3 pt-2">
                <button type="submit"
                    class="px-8 py-3 bg-[#754a30] text-white text-sm font-bold rounded-xl hover:bg-[#21140e] transition shadow-md">
                    💾 Simpan Seluruh Pengaturan
                </button>
            </div>

        </div>
    </form>

</div>
<?= $this->endSection(); ?>