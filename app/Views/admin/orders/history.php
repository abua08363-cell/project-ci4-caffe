<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<?php $canManageOrders = in_array(session()->get('role'), ['admin', 'staff'], true); ?>

<?php
// ===== Ringkasan untuk kartu statistik =====
$totalOrders  = (!empty($orders) && is_array($orders)) ? count($orders) : 0;
$totalRevenue = 0;
$ratingSum    = 0;
$ratingCount  = 0;
if ($totalOrders) {
    foreach ($orders as $o) {
        $totalRevenue += (float) ($o['total'] ?? $o['total_price'] ?? 0);
        if (!empty($o['review']) && isset($o['review']['rating'])) {
            $ratingSum += (float) $o['review']['rating'];
            $ratingCount++;
        }
    }
}
$avgRating = $ratingCount ? round($ratingSum / $ratingCount, 1) : 0;
$colCount  = $canManageOrders ? 7 : 6;
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

    /* Baris tabel masuk satu per satu */
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
        animation-delay: calc(450ms + var(--r, 0) * 50ms);
        transition: background-color .25s, box-shadow .25s;
    }

    .bn-row:hover {
        background-color: #fbf6f2;
        box-shadow: inset 4px 0 0 #754a30;
    }

    .bn-row.is-hidden {
        display: none;
    }

    /* Lencana meja */
    .bn-table-badge {
        display: inline-block;
        transition: transform .35s cubic-bezier(.34, 1.56, .64, 1);
    }

    .bn-row:hover .bn-table-badge {
        transform: scale(1.15) rotate(-4deg);
    }

    /* Bintang rating */
    .bn-stars {
        display: inline-flex;
        gap: 1px;
        letter-spacing: 0;
    }

    .bn-star {
        color: #e5e7eb;
        display: inline-block;
        transition: transform .3s cubic-bezier(.34, 1.56, .64, 1), color .2s;
    }

    .bn-star.is-on {
        color: #f59e0b;
    }

    .bn-row:hover .bn-star.is-on {
        transform: scale(1.25) rotate(-8deg);
        transition-delay: calc(var(--s, 0) * 40ms);
    }

    /* Chip tag ulasan */
    .bn-tag {
        transition: transform .2s, background-color .2s;
    }

    .bn-tag:hover {
        transform: translateY(-2px);
        background-color: #f3e6da;
    }

    /* Tombol nota */
    .bn-btn {
        position: relative;
        overflow: hidden;
        transition: transform .25s, box-shadow .25s, background-color .25s;
    }

    .bn-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(110deg, transparent 30%, rgba(255, 255, 255, .3) 50%, transparent 70%);
        transform: translateX(-120%);
    }

    .bn-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -10px rgba(0, 0, 0, .5);
    }

    .bn-btn:hover::after {
        transform: translateX(120%);
        transition: transform .7s ease;
    }

    .bn-btn:active {
        transform: scale(.96);
    }

    .bn-btn .bn-arrow {
        display: inline-block;
        transition: transform .25s;
    }

    .bn-btn:hover .bn-arrow {
        transform: translateX(4px);
    }

    /* Kartu statistik */
    .bn-stat {
        transition: transform .3s cubic-bezier(.22, 1, .36, 1), box-shadow .3s, border-color .3s;
    }

    .bn-stat:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px -14px rgba(117, 74, 48, .45);
        border-color: #e3cdbd;
    }

    .bn-stat-icon {
        transition: transform .4s cubic-bezier(.34, 1.56, .64, 1);
    }

    .bn-stat:hover .bn-stat-icon {
        transform: scale(1.15) rotate(-8deg);
    }

    /* Search */
    .bn-search {
        transition: border-color .25s, box-shadow .25s;
    }

    .bn-search:focus {
        border-color: #754a30;
        box-shadow: 0 0 0 4px rgba(117, 74, 48, .15);
        outline: none;
    }

    /* Filter chip */
    .bn-filter {
        transition: background-color .25s, color .25s, border-color .25s, transform .25s cubic-bezier(.34, 1.56, .64, 1);
    }

    .bn-filter:hover {
        transform: translateY(-2px);
        border-color: #754a30;
    }

    .bn-filter:active {
        transform: scale(.95);
    }

    .bn-filter.is-active {
        background-color: #754a30;
        border-color: #754a30;
        color: #fff;
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

    @media (prefers-reduced-motion: reduce) {

        .bn-in,
        .bn-row {
            animation: none;
            opacity: 1;
        }

        .bn-float {
            animation: none;
        }

        .bn-row,
        .bn-table-badge,
        .bn-star,
        .bn-tag,
        .bn-btn,
        .bn-btn .bn-arrow,
        .bn-stat,
        .bn-stat-icon,
        .bn-filter {
            transition: none;
        }
    }
</style>

<div class="w-full p-4 md:p-6 lg:p-8">

    <!-- Header Page -->
    <div class="bn-in flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6" style="--i:0">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Riwayat Transaksi (History)</h1>
            <p class="text-sm text-gray-500">Arsip seluruh transaksi dan pesanan yang telah lunas / selesai disajikan.</p>
        </div>
    </div>

    <!-- Kartu Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 w-full">
        <div class="bn-in bn-stat bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4" style="--i:1">
            <div class="bn-stat-icon w-12 h-12 rounded-xl bg-amber-100 text-[#754a30] flex items-center justify-center text-xl">🧾</div>
            <div>
                <p class="text-xs text-gray-500">Total Transaksi</p>
                <p class="text-2xl font-bold text-gray-800 tabular-nums js-count" data-to="<?= $totalOrders; ?>">0</p>
            </div>
        </div>
        <div class="bn-in bn-stat bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4" style="--i:2">
            <div class="bn-stat-icon w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">💰</div>
            <div>
                <p class="text-xs text-gray-500">Total Pendapatan</p>
                <p class="text-2xl font-bold text-emerald-700 tabular-nums js-count" data-to="<?= (int) $totalRevenue; ?>" data-prefix="Rp ">Rp 0</p>
            </div>
        </div>
        <div class="bn-in bn-stat bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4" style="--i:3">
            <div class="bn-stat-icon w-12 h-12 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-xl">⭐</div>
            <div>
                <p class="text-xs text-gray-500">Rata-rata Rating <span class="text-gray-400">(<?= $ratingCount; ?> ulasan)</span></p>
                <p class="text-2xl font-bold text-gray-800 tabular-nums js-count" data-to="<?= $avgRating; ?>" data-decimals="1" data-suffix=" / 5"><?= $ratingCount ? '0.0' : '-'; ?></p>
            </div>
        </div>
    </div>

    <!-- Tabel History -->
    <div class="bn-in w-full bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" style="--i:4">

        <!-- Toolbar: pencarian & filter -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 p-4 md:px-6 border-b border-gray-100">
            <div class="relative w-full lg:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                </span>
                <input id="order-search" type="search" placeholder="Cari meja, pemesan, menu, atau ulasan..."
                    class="bn-search w-full pl-9 pr-4 py-2 rounded-xl border border-gray-300 text-sm text-gray-800">
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" class="bn-filter js-filter is-active px-3.5 py-1.5 rounded-full border border-gray-300 text-xs font-semibold text-gray-700" data-filter="all">Semua</button>
                <button type="button" class="bn-filter js-filter px-3.5 py-1.5 rounded-full border border-gray-300 text-xs font-semibold text-gray-700" data-filter="reviewed">⭐ Sudah diulas</button>
                <button type="button" class="bn-filter js-filter px-3.5 py-1.5 rounded-full border border-gray-300 text-xs font-semibold text-gray-700" data-filter="unreviewed">Belum diulas</button>
                <p class="ml-1 text-xs text-gray-500">Menampilkan <span id="visible-count" class="font-semibold text-gray-700 tabular-nums"><?= $totalOrders; ?></span> dari <?= $totalOrders; ?></p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-100 border-b border-gray-200 text-xs font-semibold text-stone-600 uppercase tracking-wider">
                        <th class="py-4 px-6">No. Meja</th>
                        <th class="py-4 px-6">Nama Pemesan</th>
                        <th class="py-4 px-6">Item Menu</th>
                        <th class="py-4 px-6">Total Bayar</th>
                        <th class="py-4 px-6">Ulasan &amp; Rating</th>
                        <th class="py-4 px-6">Status Pembayaran</th>
                        <?php if ($canManageOrders) : ?>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (!empty($orders) && is_array($orders)) : ?>
                        <?php $rowIndex = 0;
                        foreach ($orders as $order) :
                            $tableLabel  = $order['table_number'] ?? 'Takeaway';
                            $customer    = $order['customer_name'] ?? 'Pelanggan Umum';
                            $statusLabel = $order['payment_status'] ?? $order['status'] ?? 'PAID';
                            $hasReview   = !empty($order['review']);

                            // Teks pencarian: meja, pemesan, item, status, komentar ulasan
                            $itemNames = [];
                            if (!empty($order['items'])) {
                                foreach ($order['items'] as $it) {
                                    $itemNames[] = $it['menu_name'] ?? 'Menu';
                                }
                            }
                            $searchText = strtolower(implode(' ', [
                                $tableLabel,
                                $customer,
                                implode(' ', $itemNames),
                                $statusLabel,
                                $hasReview ? ($order['review']['comment'] ?? '') : '',
                            ]));
                        ?>
                            <tr class="bn-row js-row align-top"
                                style="--r:<?= $rowIndex++; ?>"
                                data-search="<?= esc($searchText, 'attr'); ?>"
                                data-reviewed="<?= $hasReview ? '1' : '0'; ?>">
                                <td class="py-4 px-6 font-semibold text-gray-900">
                                    <span class="bn-table-badge px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-[#754a30]">
                                        <?= esc($tableLabel); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-medium"><?= esc($customer); ?></td>
                                <td class="py-4 px-6">
                                    <?php if (!empty($order['items'])) : ?>
                                        <ul class="space-y-1">
                                            <?php foreach ($order['items'] as $item) : ?>
                                                <li><span class="font-medium"><?= esc($item['menu_name'] ?? 'Menu'); ?></span> <span class="text-xs text-gray-500">(x<?= esc((string) ($item['qty'] ?? $item['quantity'] ?? 1)); ?>)</span></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else : ?>
                                        <span class="text-gray-400 italic">Tidak ada item</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 font-bold text-emerald-700 whitespace-nowrap">Rp <?= number_format($order['total'] ?? $order['total_price'] ?? 0, 0, ',', '.'); ?></td>

                                <!-- Kolom Ulasan Pelanggan -->
                                <td class="py-4 px-6">
                                    <?php if ($hasReview) :
                                        $rating = (float) ($order['review']['rating'] ?? 0);
                                        $filled = (int) round($rating);
                                    ?>
                                        <div class="space-y-1.5 max-w-xs">
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="bn-stars text-sm" aria-hidden="true">
                                                    <?php for ($s = 1; $s <= 5; $s++) : ?>
                                                        <span class="bn-star <?= $s <= $filled ? 'is-on' : ''; ?>" style="--s:<?= $s; ?>">★</span>
                                                    <?php endfor; ?>
                                                </span>
                                                <span class="font-bold text-amber-600"><?= esc($order['review']['rating']); ?>/5</span>
                                                <?php if (!empty($order['review']['is_anonymous']) && $order['review']['is_anonymous'] == 1) : ?>
                                                    <span class="bg-stone-100 text-stone-500 text-[10px] px-1.5 py-0.5 rounded font-normal">Anonim</span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!empty($order['review']['comment'])) : ?>
                                                <p class="text-xs text-stone-600 italic leading-relaxed">"<?= esc($order['review']['comment']); ?>"</p>
                                            <?php endif; ?>
                                            <?php if (!empty($order['review']['tags'])) : ?>
                                                <div class="flex flex-wrap gap-1">
                                                    <?php
                                                    $tags = is_array($order['review']['tags']) ? $order['review']['tags'] : [$order['review']['tags']];
                                                    foreach ($tags as $tag) :
                                                    ?>
                                                        <span class="bn-tag px-2 py-0.5 rounded-full bg-amber-50 border border-amber-100 text-[11px] text-stone-600"><?= esc($tag); ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else : ?>
                                        <span class="text-xs text-stone-400 italic">Belum diulas</span>
                                    <?php endif; ?>
                                </td>

                                <td class="py-4 px-6">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 uppercase whitespace-nowrap">
                                        ✓ <?= esc($statusLabel); ?>
                                    </span>
                                </td>
                                <?php if ($canManageOrders) : ?>
                                    <td class="py-4 px-6 text-center">
                                        <a href="<?= base_url('admin/orders/detail/' . $order['id']); ?>"
                                            class="bn-btn inline-flex items-center gap-1.5 px-4 py-2 bg-stone-700 text-white text-xs font-semibold rounded-lg hover:bg-stone-900 whitespace-nowrap">
                                            Lihat Nota <span class="bn-arrow">→</span>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="<?= $colCount; ?>" class="text-center py-14 text-stone-500">
                                <span class="bn-float text-4xl">🧾</span>
                                <p class="mt-3 italic">Belum ada riwayat transaksi yang selesai.</p>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <!-- Muncul saat pencarian/filter tidak menemukan hasil -->
                    <tr id="no-result" class="hidden">
                        <td colspan="<?= $colCount; ?>" class="text-center py-12 text-stone-500">
                            <span class="bn-float text-3xl">🔍</span>
                            <p class="mt-3 italic">Tidak ada transaksi yang cocok dengan pencarian atau filter kamu.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    (function() {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // ===== Hitung naik pada kartu statistik =====
        document.querySelectorAll('.js-count').forEach(el => {
            const to = parseFloat(el.dataset.to) || 0;
            const decimals = parseInt(el.dataset.decimals || '0', 10);
            const prefix = el.dataset.prefix || '';
            const suffix = el.dataset.suffix || '';

            function format(v) {
                const num = decimals ?
                    v.toFixed(decimals) :
                    Math.round(v).toLocaleString('id-ID');
                return prefix + num + suffix;
            }

            // Belum ada ulasan: biarkan tanda "-"
            if (decimals && to === 0) return;

            if (reduceMotion || to === 0) {
                el.textContent = format(to);
                return;
            }
            const duration = 1000;
            const start = performance.now() + 250;

            function tick(now) {
                const t = Math.min(Math.max((now - start) / duration, 0), 1);
                const eased = 1 - Math.pow(1 - t, 3);
                el.textContent = format(to * eased);
                if (t < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });

        // ===== Pencarian + filter ulasan =====
        const search = document.getElementById('order-search');
        const rows = Array.from(document.querySelectorAll('.js-row'));
        const filters = Array.from(document.querySelectorAll('.js-filter'));
        const noResult = document.getElementById('no-result');
        const visibleCount = document.getElementById('visible-count');
        let activeFilter = 'all';

        function applyFilters() {
            const q = search.value.trim().toLowerCase();
            let shown = 0;

            rows.forEach(row => {
                const matchText = row.dataset.search.includes(q);
                const reviewed = row.dataset.reviewed === '1';
                const matchFilter =
                    activeFilter === 'all' ||
                    (activeFilter === 'reviewed' && reviewed) ||
                    (activeFilter === 'unreviewed' && !reviewed);

                const visible = matchText && matchFilter;
                row.classList.toggle('is-hidden', !visible);

                if (visible) {
                    shown++;
                    // animasi ulang agar hasil terasa "hidup"
                    row.style.animation = 'none';
                    void row.offsetWidth;
                    row.style.animation = '';
                    row.style.animationDelay = (shown * 30) + 'ms';
                }
            });

            visibleCount.textContent = shown;
            noResult.classList.toggle('hidden', shown !== 0 || rows.length === 0);
        }

        search.addEventListener('input', applyFilters);
        filters.forEach(btn => {
            btn.addEventListener('click', () => {
                activeFilter = btn.dataset.filter;
                filters.forEach(b => b.classList.toggle('is-active', b === btn));
                applyFilters();
            });
        });
    })();
</script>
<?= $this->endSection(); ?>