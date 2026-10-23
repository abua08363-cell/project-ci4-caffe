<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<?php
$orderTotal = $order['total'] ?? $order['total_price'] ?? 0;
$hasItems   = !empty($items) && is_array($items);
$itemCount  = 0;
if ($hasItems) {
    foreach ($items as $it) {
        $itemCount += (int) ($it['qty'] ?? $it['quantity'] ?? 1);
    }
}

// Tampilan bintang (5 bintang, terisi sesuai rating)
function bn_stars($rating): string
{
    $filled = (int) round((float) $rating);
    $html = '<span class="bn-stars" aria-hidden="true">';
    for ($s = 1; $s <= 5; $s++) {
        $html .= '<span class="bn-star' . ($s <= $filled ? ' is-on' : '') . '" style="--s:' . $s . '">★</span>';
    }
    return $html . '</span>';
}
?>

<style>
    /* ===== Animasi masuk ===== */
    @keyframes bn-rise {
        from {
            opacity: 0;
            transform: translateY(18px) scale(.985);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .bn-in {
        opacity: 0;
        animation: bn-rise .6s cubic-bezier(.22, 1, .36, 1) forwards;
        animation-delay: calc(var(--i, 0) * 80ms);
    }

    /* Baris item masuk satu per satu */
    @keyframes bn-row {
        from {
            opacity: 0;
            transform: translateX(-16px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .bn-row {
        opacity: 0;
        animation: bn-row .5s cubic-bezier(.22, 1, .36, 1) forwards;
        animation-delay: calc(450ms + var(--r, 0) * 60ms);
        transition: background-color .25s, box-shadow .25s;
    }

    .bn-row:hover {
        background-color: #fbf6f2;
        box-shadow: inset 4px 0 0 #754a30;
    }

    /* Rincian total masuk bergantian */
    .bn-sum {
        opacity: 0;
        animation: bn-row .5s cubic-bezier(.22, 1, .36, 1) forwards;
        animation-delay: calc(650ms + var(--r, 0) * 90ms);
    }

    /* Kartu */
    .bn-card {
        transition: box-shadow .3s, border-color .3s, transform .3s cubic-bezier(.22, 1, .36, 1);
    }

    .bn-card:hover {
        box-shadow: 0 18px 36px -22px rgba(117, 74, 48, .45);
        border-color: #ecd8c8;
    }

    /* Kartu info pemesan */
    .bn-tile {
        transition: transform .3s cubic-bezier(.22, 1, .36, 1), box-shadow .3s, border-color .3s;
    }

    .bn-tile:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px -14px rgba(117, 74, 48, .45);
        border-color: #e3cdbd;
    }

    .bn-tile-icon {
        transition: transform .4s cubic-bezier(.34, 1.56, .64, 1);
    }

    .bn-tile:hover .bn-tile-icon {
        transform: scale(1.15) rotate(-8deg);
    }

    /* Bintang */
    .bn-stars {
        display: inline-flex;
        gap: 1px;
    }

    .bn-star {
        color: #e5e7eb;
        display: inline-block;
        transform: scale(.4);
        opacity: 0;
        animation: bn-star-in .45s cubic-bezier(.34, 1.56, .64, 1) forwards;
        animation-delay: calc(700ms + var(--s, 0) * 90ms);
    }

    .bn-star.is-on {
        color: #f59e0b;
    }

    @keyframes bn-star-in {
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .bn-big-stars .bn-star {
        font-size: 26px;
    }

    /* Bar aspek */
    .bn-bar-track {
        height: 8px;
        border-radius: 9999px;
        background: #f3f4f6;
        overflow: hidden;
    }

    .bn-bar-fill {
        height: 100%;
        width: 0;
        border-radius: 9999px;
        background: linear-gradient(90deg, #e3b98f, #754a30);
        animation: bn-grow 1s cubic-bezier(.22, 1, .36, 1) forwards;
        animation-delay: calc(800ms + var(--b, 0) * 120ms);
    }

    @keyframes bn-grow {
        to {
            width: var(--w, 0%);
        }
    }

    /* Chip tag */
    .bn-tag {
        transition: transform .2s, background-color .2s;
    }

    .bn-tag:hover {
        transform: translateY(-2px) rotate(-1deg);
        background-color: #fde9c9;
    }

    /* Kutipan komentar */
    .bn-quote {
        position: relative;
    }

    .bn-quote::before {
        content: '“';
        position: absolute;
        left: -4px;
        top: -14px;
        font-size: 56px;
        line-height: 1;
        color: #ecd8c8;
        font-family: Georgia, serif;
    }

    /* Tombol */
    .bn-btn {
        position: relative;
        overflow: hidden;
        transition: transform .25s, box-shadow .25s, background-color .25s;
    }

    .bn-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(110deg, transparent 30%, rgba(255, 255, 255, .35) 50%, transparent 70%);
        transform: translateX(-120%);
    }

    .bn-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -10px rgba(117, 74, 48, .6);
    }

    .bn-btn:hover::after {
        transform: translateX(120%);
        transition: transform .7s ease;
    }

    .bn-btn:active {
        transform: translateY(0) scale(.97);
    }

    .bn-back span {
        display: inline-block;
        transition: transform .25s;
    }

    .bn-back:hover span {
        transform: translateX(-5px);
    }

    /* Empty state */
    @keyframes bn-float {

        0%,
        100% {
            transform: translateY(0) rotate(0);
        }

        50% {
            transform: translateY(-8px) rotate(-4deg);
        }
    }

    .bn-float {
        display: inline-block;
        animation: bn-float 3s ease-in-out infinite;
    }

    /* ===== Cetak nota ===== */
    @media print {
        .no-print {
            display: none !important;
        }

        .bn-in,
        .bn-row,
        .bn-sum,
        .bn-star {
            opacity: 1 !important;
            animation: none !important;
            transform: none !important;
        }

        .bn-bar-fill {
            animation: none !important;
            width: var(--w, 0%) !important;
        }

        .bn-card,
        .bn-tile {
            box-shadow: none !important;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .bn-in,
        .bn-row,
        .bn-sum,
        .bn-star {
            animation: none;
            opacity: 1;
            transform: none;
        }

        .bn-bar-fill {
            animation: none;
            width: var(--w, 0%);
        }

        .bn-float {
            animation: none;
        }

        .bn-card,
        .bn-tile,
        .bn-tile-icon,
        .bn-btn,
        .bn-back span,
        .bn-tag,
        .bn-row {
            transition: none;
        }
    }
</style>

<div class="w-full p-4 md:p-6 lg:p-8 space-y-6">

    <!-- Header Page -->
    <div class="bn-in flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3" style="--i:0">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Pesanan #<?= esc($order['id']); ?></h1>
            <p class="text-sm text-gray-500">Nomor Transaksi: <span class="font-semibold text-gray-700"><?= esc(str_replace('#BRW', '', $order['order_number'] ?? '-')); ?></span></p>
        </div>
        <div class="no-print flex flex-wrap items-center gap-2 self-start sm:self-auto">
            <button type="button" id="print-btn" class="bn-btn px-4 py-2 bg-[#754a30] text-white text-xs font-semibold rounded-lg hover:bg-[#21140e] shadow-md">
                🖨️ Cetak Nota
            </button>
            <a href="<?= base_url('admin/orders'); ?>" class="bn-back px-4 py-2 bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-300 transition">
                <span>&larr;</span> Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>

    <!-- Informasi Utama Pesanan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
        <div class="bn-in bn-tile bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4" style="--i:1">
            <div class="bn-tile-icon w-12 h-12 rounded-xl bg-amber-100 text-[#754a30] flex items-center justify-center text-xl">🙋</div>
            <div class="min-w-0">
                <p class="text-xs text-gray-400 uppercase font-medium">Nama Pemesan</p>
                <p class="font-semibold text-gray-800 mt-0.5 truncate"><?= esc($order['customer_name'] ?? 'Pelanggan Umum'); ?></p>
            </div>
        </div>
        <div class="bn-in bn-tile bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4" style="--i:2">
            <div class="bn-tile-icon w-12 h-12 rounded-xl bg-stone-100 text-stone-700 flex items-center justify-center text-xl">🪑</div>
            <div class="min-w-0">
                <p class="text-xs text-gray-400 uppercase font-medium">No. Meja / Tipe</p>
                <p class="font-semibold text-gray-800 mt-0.5 truncate"><?= esc($order['table_number'] ?? 'Takeaway'); ?></p>
            </div>
        </div>
        <div class="bn-in bn-tile bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4" style="--i:3">
            <div class="bn-tile-icon w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">💳</div>
            <div class="min-w-0">
                <p class="text-xs text-gray-400 uppercase font-medium">Metode Pembayaran</p>
                <p class="font-semibold text-gray-800 mt-0.5 truncate"><?= esc($order['payment_method'] ?? 'Tunai'); ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 w-full items-start">

        <!-- Item Pesanan -->
        <div class="bn-in bn-card xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" style="--i:4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between gap-3">
                <h2 class="text-base font-semibold text-gray-800">Daftar Menu Dipesan</h2>
                <?php if ($hasItems) : ?>
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-[#754a30] text-xs font-semibold"><?= $itemCount; ?> item</span>
                <?php endif; ?>
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
                        <?php if ($hasItems) : ?>
                            <?php $r = 0;
                            foreach ($items as $item) : ?>
                                <tr class="bn-row" style="--r:<?= $r++; ?>">
                                    <td class="py-4 px-6 font-medium text-gray-900"><?= esc($item['menu_name'] ?? 'Menu Kopi'); ?></td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">Rp <?= number_format($item['price'] ?? 0, 0, ',', '.'); ?></td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-block min-w-[2rem] px-2 py-0.5 rounded-full bg-stone-100 font-semibold">×<?= esc((string) ($item['qty'] ?? $item['quantity'] ?? 1)); ?></span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-semibold text-gray-900 whitespace-nowrap">Rp <?= number_format($item['subtotal'] ?? 0, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center py-10 text-gray-400">
                                    <span class="bn-float text-3xl">🧾</span>
                                    <p class="mt-2 italic">Rincian item tidak ditemukan.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Rincian Total Harga -->
            <div class="p-6 bg-gray-50/50 border-t border-gray-100 space-y-2 text-sm">
                <div class="bn-sum flex justify-between text-gray-600" style="--r:0">
                    <span>Subtotal</span>
                    <span>Rp <?= number_format($order['subtotal'] ?? 0, 0, ',', '.'); ?></span>
                </div>
                <div class="bn-sum flex justify-between text-gray-600" style="--r:1">
                    <span>Pajak (11%)</span>
                    <span>Rp <?= number_format($order['tax'] ?? 0, 0, ',', '.'); ?></span>
                </div>
                <div class="bn-sum flex justify-between items-center pt-3 border-t border-gray-200 text-base font-bold text-gray-900" style="--r:2">
                    <span>Total Pembayaran</span>
                    <span class="text-xl text-[#754a30] tabular-nums js-count" data-to="<?= (int) $orderTotal; ?>">Rp <?= number_format($orderTotal, 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>

        <!-- Ulasan Pelanggan -->
        <section class="bn-in bn-card xl:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6" style="--i:5">
            <h2 class="text-base font-semibold text-gray-800 mb-4 border-b pb-3">Ulasan Pelanggan</h2>
            <?php if (!empty($review)) : ?>
                <div class="space-y-5 text-sm">

                    <!-- Rating utama -->
                    <div class="flex flex-col items-center text-center py-2">
                        <div class="bn-big-stars"><?= bn_stars($review['rating'] ?? 0); ?></div>
                        <p class="mt-2 text-3xl font-bold text-amber-600 tabular-nums"><?= esc($review['rating']); ?><span class="text-base text-gray-400 font-medium"> / 5</span></p>
                        <div class="mt-2 flex flex-wrap items-center justify-center gap-2">
                            <?php if (!empty($review['is_anonymous'])) : ?>
                                <span class="rounded-full bg-stone-100 px-2 py-1 text-xs text-stone-600">Anonim</span>
                            <?php elseif (!empty($review['customer_name'])) : ?>
                                <span class="text-gray-500">oleh <?= esc($review['customer_name']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($review['comment'])) : ?>
                        <p class="bn-quote text-gray-700 italic leading-relaxed pl-4"><?= esc($review['comment']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($review['tags'])) : ?>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $tags = is_array($review['tags']) ? $review['tags'] : [$review['tags']];
                            foreach ($tags as $tag) :
                            ?>
                                <span class="bn-tag rounded-full bg-amber-50 px-3 py-1 text-xs text-amber-800"><?= esc($tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($review['aspects'])) : ?>
                        <div class="space-y-3 border-t border-gray-100 pt-4">
                            <?php $b = 0;
                            foreach (['rasa' => 'Rasa', 'pelayanan' => 'Pelayanan', 'kecepatan' => 'Kecepatan', 'suasana' => 'Suasana'] as $key => $label) : ?>
                                <?php if (isset($review['aspects'][$key])) :
                                    $val = (float) $review['aspects'][$key];
                                    $pct = max(0, min(100, ($val / 5) * 100));
                                ?>
                                    <div>
                                        <div class="flex justify-between text-xs mb-1">
                                            <span class="text-gray-500"><?= esc($label); ?></span>
                                            <span class="font-semibold text-amber-600">⭐ <?= esc($review['aspects'][$key]); ?>/5</span>
                                        </div>
                                        <div class="bn-bar-track">
                                            <div class="bn-bar-fill" style="--w:<?= $pct; ?>%; --b:<?= $b++; ?>"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (($review['recommend'] ?? null) !== null) : ?>
                        <p class="text-xs rounded-xl px-3 py-2 <?= $review['recommend'] ? 'bg-emerald-50 text-emerald-700' : 'bg-stone-100 text-stone-600'; ?>">
                            <?= $review['recommend'] ? '👍 Merekomendasikan BrewNest.' : 'Belum ingin merekomendasikan BrewNest.'; ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($review['items'])) : ?>
                        <div class="border-t border-gray-100 pt-4">
                            <p class="mb-2 text-xs font-semibold uppercase text-gray-500">Rating menu</p>
                            <ul class="space-y-2">
                                <?php foreach (($items ?? []) as $item) : ?>
                                    <?php $menuId = $item['menu_id'] ?? null; ?>
                                    <?php if ($menuId !== null && isset($review['items'][$menuId])) : ?>
                                        <li class="flex items-center justify-between gap-3">
                                            <span class="truncate"><?= esc($item['menu_name'] ?? 'Menu'); ?></span>
                                            <span class="flex items-center gap-2 whitespace-nowrap">
                                                <?= bn_stars($review['items'][$menuId]); ?>
                                                <span class="text-xs font-semibold text-amber-600"><?= esc($review['items'][$menuId]); ?>/5</span>
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="text-center py-8 text-gray-400">
                    <span class="bn-float text-3xl">💬</span>
                    <p class="mt-2 text-sm italic">Pesanan ini belum memiliki ulasan.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>
</div>

<script>
    (function() {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // ===== Total pembayaran menghitung naik =====
        document.querySelectorAll('.js-count').forEach(el => {
            const to = parseInt(el.dataset.to, 10) || 0;
            if (reduceMotion || to === 0) return;
            const duration = 1100;
            const start = performance.now() + 700;

            function tick(now) {
                const t = Math.min(Math.max((now - start) / duration, 0), 1);
                const eased = 1 - Math.pow(1 - t, 3);
                el.textContent = 'Rp ' + Math.round(to * eased).toLocaleString('id-ID');
                if (t < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });

        // ===== Cetak nota =====
        const printBtn = document.getElementById('print-btn');
        if (printBtn) printBtn.addEventListener('click', () => window.print());
    })();
</script>
<?= $this->endSection(); ?>