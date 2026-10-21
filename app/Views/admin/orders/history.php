<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<?php $isAdmin = session()->get('role') === 'admin'; ?>
<div class="p-6 max-w-7xl mx-auto">

    <!-- Header Page -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi (History)</h1>
            <p class="text-sm text-gray-500">Arsip seluruh transaksi dan pesanan yang telah lunas / selesai disajikan.</p>
        </div>
    </div>

    <!-- Tabel History -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-100 border-b border-gray-200 text-xs font-semibold text-stone-600 uppercase tracking-wider">
                        <th class="py-4 px-6">No. Meja</th>
                        <th class="py-4 px-6">Nama Pemesan</th>
                        <th class="py-4 px-6">Item Menu</th>
                        <th class="py-4 px-6">Total Bayar</th>
                        <th class="py-4 px-6">Status Pembayaran</th>
                        <?php if ($isAdmin) : ?>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (!empty($orders) && is_array($orders)) : ?>
                        <?php foreach ($orders as $order) : ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 font-semibold text-gray-900">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-[#754a30]">
                                        <?= esc($order['table_number'] ?? 'Takeaway'); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-medium"><?= esc($order['customer_name'] ?? 'Pelanggan Umum'); ?></td>
                                <td class="py-4 px-6">
                                    <?php if (!empty($order['items'])) : ?>
                                        <ul class="space-y-1">
                                            <?php foreach ($order['items'] as $item) : ?>
                                                <li><span class="font-medium"><?= esc($item['menu_name'] ?? 'Menu'); ?></span> <span class="text-xs text-gray-500">(x<?= $item['qty'] ?? $item['quantity'] ?? 1; ?>)</span></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else : ?>
                                        <span class="text-gray-400 italic">Tidak ada item</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 font-bold text-emerald-700">Rp <?= number_format($order['total'] ?? $order['total_price'] ?? 0, 0, ',', '.'); ?></td>
                                <td class="py-4 px-6">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 uppercase">
                                        ✓ <?= esc($order['payment_status'] ?? $order['status'] ?? 'PAID'); ?>
                                    </span>
                                </td>
                                <?php if ($isAdmin) : ?>
                                    <td class="py-4 px-6 text-center">
                                        <a href="<?= base_url('admin/orders/detail/' . $order['id']); ?>"
                                            class="inline-block px-4 py-2 bg-stone-700 text-white text-xs font-semibold rounded-lg hover:bg-stone-900 transition">
                                            Lihat Nota
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="<?= $isAdmin ? 6 : 5 ?>" class="text-center py-10 text-stone-500 italic">
                                Belum ada riwayat transaksi yang selesai.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>