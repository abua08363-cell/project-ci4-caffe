<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="p-6 max-w-7xl mx-auto space-y-6">

    <!-- Header Dashboard -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard BrewNest ☕</h1>
        <p class="text-sm text-gray-500">Selamat datang kembali di panel admin BrewNest Coffee.</p>
    </div>

    <!-- Stat Cards Dynamic -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Card 1: Total Menu -->
        <a href="<?= base_url('admin/menus'); ?>" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Menu</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1"><?= $total_menu; ?></h3>
            </div>
            <div class="p-3.5 bg-amber-50 rounded-2xl text-2xl">
                ☕
            </div>
        </a>

        <!-- Card 2: Total Users / Partners -->
        <a href="<?= base_url('admin/users'); ?>" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Users</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1"><?= $total_users; ?></h3>
            </div>
            <div class="p-3.5 bg-blue-50 rounded-2xl text-2xl">
                👥
            </div>
        </a>

        <!-- Card 3: Categories -->
        <a href="<?= base_url('admin/categories'); ?>" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Categories</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1"><?= $total_category; ?></h3>
            </div>
            <div class="p-3.5 bg-emerald-50 rounded-2xl text-2xl">
                📁
            </div>
        </a>

        <!-- Card 4: Total Orders -->
        <a href="<?= base_url('admin/orders'); ?>" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Orders</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1"><?= $total_orders; ?></h3>
            </div>
            <div class="p-3.5 bg-orange-50 rounded-2xl text-2xl">
                🛒
            </div>
        </a>

    </div>

    <!-- Ringkasan Pesanan Terbaru -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Pesanan Masuk Terbaru</h2>
                <p class="text-xs text-gray-500">Pantau transaksi pesanan yang baru dilakukan oleh pelanggan.</p>
            </div>
            <a href="<?= base_url('admin/orders'); ?>" class="text-xs font-bold text-[#754a30] hover:underline">
                Lihat Semua Orders →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3 px-4">ID Order</th>
                        <th class="py-3 px-4">Nama Pemesan</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (!empty($recent_orders) && is_array($recent_orders)) : ?>
                        <?php
                        $no = 1; // Inisialisasi nomor urut
                        foreach ($recent_orders as $order) :
                        ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <!-- ID Order diubah menjadi nomor urut 1, 2, 3, dst -->
                                <td class="py-3 px-4 font-semibold text-gray-900">#<?= $no++; ?></td>
                                <td class="py-3 px-4"><?= esc($order['customer_name'] ?? 'Pelanggan'); ?></td>
                                <td class="py-3 px-4"><?= esc($order['table_number'] ?? 'Dine In'); ?></td>
                                <td class="py-3 px-4 font-bold text-gray-900">Rp <?= number_format($order['total'] ?? 0, 0, ',', '.'); ?></td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-[#754a30]">
                                        <?= esc(ucfirst($order['payment_status'] ?? $order['status'] ?? 'Pending')); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center py-6 text-stone-400 italic text-xs">
                                Belum ada transaksi pesanan masuk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>