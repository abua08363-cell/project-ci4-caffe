<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="p-6 max-w-4xl mx-auto space-y-6">

    <!-- Header Page -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan #<?= $order['id']; ?></h1>
            <p class="text-sm text-gray-500">Nomor Transaksi: <span class="font-semibold text-gray-700"><?= esc($order['order_number'] ?? '-'); ?></span></p>
        </div>
        <a href="<?= base_url('admin/orders'); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-300 transition">
            &larr; Kembali ke Daftar Pesanan
        </a>
    </div>

    <!-- Informasi Utama Pesanan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pemesan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400 uppercase font-medium">Nama Pemesan</p>
                <p class="font-semibold text-gray-800 mt-1"><?= esc($order['customer_name'] ?? 'Pelanggan Umum'); ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-medium">No. Meja / Tipe</p>
                <p class="font-semibold text-gray-800 mt-1"><?= esc($order['table_number'] ?? 'Takeaway'); ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-medium">Metode Pembayaran</p>
                <p class="font-semibold text-gray-800 mt-1"><?= esc($order['payment_method'] ?? 'Tunai'); ?></p>
            </div>
        </div>
    </div>

    <!-- Item Pesanan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Daftar Menu Dipesan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3 px-6">Nama Menu</th>
                        <th class="py-3 px-6 text-center">Harga Satuan</th>
                        <th class="py-3 px-6 text-center">Jumlah</th>
                        <th class="py-3 px-6 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <?php if (!empty($items) && is_array($items)) : ?>
                        <?php foreach ($items as $item) : ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 font-medium text-gray-900"><?= esc($item['menu_name'] ?? 'Menu Kopi'); ?></td>
                                <td class="py-4 px-6 text-center">Rp <?= number_format($item['price'] ?? 0, 0, ',', '.'); ?></td>
                                <td class="py-4 px-6 text-center font-semibold"><?= esc($item['qty'] ?? $item['quantity'] ?? 1); ?></td>
                                <td class="py-4 px-6 text-right font-semibold text-gray-900">Rp <?= number_format($item['subtotal'] ?? 0, 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400 italic">Rincian item tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Rincian Total Harga -->
        <div class="p-6 bg-gray-50/50 border-t border-gray-100 space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>Rp <?= number_format($order['subtotal'] ?? 0, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Pajak (11%)</span>
                <span>Rp <?= number_format($order['tax'] ?? 0, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between pt-3 border-t border-gray-200 text-base font-bold text-gray-900">
                <span>Total Pembayaran</span>
                <span class="text-[#754a30]">Rp <?= number_format($order['total'] ?? $order['total_price'] ?? 0, 0, ',', '.'); ?></span>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>