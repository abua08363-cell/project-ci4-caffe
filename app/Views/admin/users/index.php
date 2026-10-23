<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<?php $isOwner = $isOwner ?? session()->get('role') === 'staff'; ?>

<?php
// Ringkasan kecil untuk kartu statistik
$totalUsers  = (!empty($users) && is_array($users)) ? count($users) : 0;
$totalOwner  = 0;
$totalAdmin  = 0;
if ($totalUsers) {
    foreach ($users as $u) {
        if (($u['role'] ?? 'admin') === 'staff') {
            $totalOwner++;
        } else {
            $totalAdmin++;
        }
    }
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
        animation-delay: calc(400ms + var(--r, 0) * 55ms);
        transition: background-color .25s, box-shadow .25s;
        position: relative;
    }

    .bn-row:hover {
        background-color: #fbf6f2;
        box-shadow: inset 4px 0 0 #754a30;
    }

    .bn-row.is-hidden {
        display: none;
    }

    /* Avatar */
    .bn-avatar {
        transition: transform .35s cubic-bezier(.34, 1.56, .64, 1), box-shadow .3s;
    }

    .bn-row:hover .bn-avatar {
        transform: scale(1.15) rotate(-4deg);
        box-shadow: 0 6px 16px -6px rgba(117, 74, 48, .5);
    }

    /* Tombol kembali / tambah */
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

    .bn-btn .bn-plus {
        display: inline-block;
        transition: transform .35s cubic-bezier(.34, 1.56, .64, 1);
    }

    .bn-btn:hover .bn-plus {
        transform: rotate(90deg) scale(1.2);
    }

    /* Tombol aksi di baris */
    .bn-act {
        transition: transform .2s, box-shadow .2s, background-color .2s;
    }

    .bn-act:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -8px rgba(0, 0, 0, .35);
    }

    .bn-act:active {
        transform: scale(.95);
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

    /* Search */
    .bn-search {
        transition: border-color .25s, box-shadow .25s, width .3s;
    }

    .bn-search:focus {
        border-color: #754a30;
        box-shadow: 0 0 0 4px rgba(117, 74, 48, .15);
        outline: none;
    }

    /* Alert flash */
    @keyframes bn-alert-in {
        from {
            opacity: 0;
            transform: translateY(-12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bn-alert {
        animation: bn-alert-in .5s cubic-bezier(.34, 1.56, .64, 1) both;
        transition: opacity .4s, transform .4s, max-height .4s, margin .4s, padding .4s;
        max-height: 200px;
    }

    .bn-alert.is-closing {
        opacity: 0;
        transform: translateX(30px);
        max-height: 0;
        margin: 0;
        padding-top: 0;
        padding-bottom: 0;
        overflow: hidden;
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

    /* Modal hapus */
    .bn-modal-backdrop {
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s;
    }

    .bn-modal-backdrop.is-open {
        opacity: 1;
        pointer-events: auto;
    }

    .bn-modal {
        transform: translateY(20px) scale(.92);
        opacity: 0;
        transition: transform .35s cubic-bezier(.34, 1.56, .64, 1), opacity .25s;
    }

    .bn-modal-backdrop.is-open .bn-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    @keyframes bn-shake {

        0%,
        100% {
            transform: rotate(0);
        }

        20% {
            transform: rotate(-12deg);
        }

        40% {
            transform: rotate(10deg);
        }

        60% {
            transform: rotate(-8deg);
        }

        80% {
            transform: rotate(6deg);
        }
    }

    .bn-modal-backdrop.is-open .bn-shake {
        animation: bn-shake .7s .25s ease;
    }

    @media (prefers-reduced-motion: reduce) {

        .bn-in,
        .bn-row,
        .bn-alert {
            animation: none;
            opacity: 1;
        }

        .bn-float,
        .bn-modal-backdrop.is-open .bn-shake {
            animation: none;
        }

        .bn-row,
        .bn-avatar,
        .bn-btn,
        .bn-act,
        .bn-stat,
        .bn-modal,
        .bn-modal-backdrop {
            transition: none;
        }
    }
</style>

<div class="w-full p-4 md:p-6 lg:p-8">

    <!-- Header & Tombol Tambah -->
    <div class="bn-in flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6" style="--i:0">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Daftar Partners / Users</h1>
            <p class="text-sm text-gray-500">Kelola akun pengguna, Owner, atau partner yang terhubung dengan BrewNest Coffee.</p>
        </div>
        <a href="<?= base_url('admin/users/create'); ?>"
            class="bn-btn self-start sm:self-auto px-5 py-2.5 bg-[#754a30] text-white text-sm font-semibold rounded-xl hover:bg-[#21140e] inline-flex items-center gap-2 shadow-md">
            <span class="bn-plus">➕</span> Tambah User
        </a>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bn-alert js-alert mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-xl flex items-start justify-between gap-3">
            <span><?= session()->getFlashdata('success'); ?></span>
            <button type="button" class="js-alert-close text-green-700/70 hover:text-green-900 text-lg leading-none" aria-label="Tutup">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bn-alert js-alert mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-xl flex items-start justify-between gap-3">
            <span><?= session()->getFlashdata('error'); ?></span>
            <button type="button" class="js-alert-close text-red-700/70 hover:text-red-900 text-lg leading-none" aria-label="Tutup">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Kartu Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 w-full">
        <div class="bn-in bn-stat bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4" style="--i:1">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-[#754a30] flex items-center justify-center text-xl">👥</div>
            <div>
                <p class="text-xs text-gray-500">Total User</p>
                <p class="text-2xl font-bold text-gray-800 tabular-nums js-count" data-to="<?= $totalUsers; ?>">0</p>
            </div>
        </div>
        <div class="bn-in bn-stat bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4" style="--i:2">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">☕</div>
            <div>
                <p class="text-xs text-gray-500">Owner</p>
                <p class="text-2xl font-bold text-gray-800 tabular-nums js-count" data-to="<?= $totalOwner; ?>">0</p>
            </div>
        </div>
        <div class="bn-in bn-stat bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4" style="--i:3">
            <div class="w-12 h-12 rounded-xl bg-stone-100 text-stone-700 flex items-center justify-center text-xl">🛠️</div>
            <div>
                <p class="text-xs text-gray-500">Admin &amp; Lainnya</p>
                <p class="text-2xl font-bold text-gray-800 tabular-nums js-count" data-to="<?= $totalAdmin; ?>">0</p>
            </div>
        </div>
    </div>

    <!-- Tabel Users -->
    <div class="bn-in w-full bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" style="--i:4">

        <!-- Toolbar pencarian -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 md:px-6 border-b border-gray-100">
            <div class="relative w-full sm:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                </span>
                <input id="user-search" type="search" placeholder="Cari nama, email, atau role..."
                    class="bn-search w-full pl-9 pr-4 py-2 rounded-xl border border-gray-300 text-sm text-gray-800">
            </div>
            <p class="text-xs text-gray-500">Menampilkan <span id="visible-count" class="font-semibold text-gray-700 tabular-nums"><?= $totalUsers; ?></span> dari <?= $totalUsers; ?> user</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">No</th>
                        <th class="py-4 px-6">Foto</th>
                        <th class="py-4 px-6">Nama / Username</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Role / Level</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="user-body" class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (!empty($users) && is_array($users)) : ?>
                        <?php $no = 1;
                        foreach ($users as $user) :
                            $displayName = $user['username'] ?? $user['name'] ?? 'Admin';
                            $roleKey     = $user['role'] ?? 'admin';
                            $roleLabel   = $roleKey === 'staff' ? 'Owner' : ucfirst($user['role'] ?? 'Administrator');
                            $searchText  = strtolower($displayName . ' ' . ($user['email'] ?? '') . ' ' . $roleLabel);
                        ?>
                            <tr class="bn-row js-row" style="--r:<?= $no - 1; ?>" data-search="<?= esc($searchText, 'attr'); ?>">
                                <td class="py-4 px-6 font-semibold text-gray-900 tabular-nums"><?= $no++; ?></td>

                                <!-- Foto Profile / Avatar Inisial -->
                                <td class="py-4 px-6">
                                    <?php if (!empty($user['photo']) && file_exists(FCPATH . 'uploads/users/' . $user['photo'])) : ?>
                                        <img src="<?= base_url('uploads/users/' . $user['photo']); ?>"
                                            alt="Foto Profil"
                                            class="bn-avatar w-10 h-10 rounded-full object-cover border border-gray-200">
                                    <?php else : ?>
                                        <div class="bn-avatar w-10 h-10 rounded-full bg-amber-100 text-[#754a30] font-bold flex items-center justify-center text-sm border border-amber-200">
                                            <?= strtoupper(substr($user['username'] ?? $user['name'] ?? 'A', 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="py-4 px-6 font-medium text-gray-900"><?= esc($displayName); ?></td>
                                <td class="py-4 px-6"><?= esc($user['email'] ?? '-'); ?></td>
                                <td class="py-4 px-6">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full <?= $roleKey === 'admin' ? 'bg-amber-100 text-[#754a30]' : 'bg-blue-100 text-blue-700'; ?>">
                                        <?= esc($roleLabel); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if ($isOwner || ($user['role'] ?? '') !== 'staff') : ?>
                                            <a href="<?= base_url('admin/users/edit/' . $user['id']); ?>"
                                                class="bn-act px-3 py-1.5 bg-amber-500 text-white text-xs font-semibold rounded-lg hover:bg-amber-600">
                                                Edit
                                            </a>
                                            <a href="<?= base_url('admin/users/delete/' . $user['id']); ?>"
                                                data-name="<?= esc($displayName, 'attr'); ?>"
                                                class="js-delete bn-act px-3 py-1.5 bg-rose-600 text-white text-xs font-semibold rounded-lg hover:bg-rose-700">
                                                Hapus
                                            </a>
                                        <?php else : ?>
                                            <span class="text-xs italic text-gray-400">Khusus Owner</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center py-14 text-stone-500">
                                <span class="bn-float text-4xl">☕</span>
                                <p class="mt-3 italic">Belum ada data partner atau user terdaftar.</p>
                                <a href="<?= base_url('admin/users/create'); ?>" class="inline-block mt-4 px-4 py-2 bg-[#754a30] text-white text-xs font-semibold rounded-lg hover:bg-[#21140e] transition not-italic">Tambah user pertama</a>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <!-- Muncul saat pencarian tidak menemukan hasil -->
                    <tr id="no-result" class="hidden">
                        <td colspan="6" class="text-center py-12 text-stone-500">
                            <span class="bn-float text-3xl">🔍</span>
                            <p class="mt-3 italic">Tidak ada user yang cocok dengan pencarian kamu.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Konfirmasi Hapus -->
<div id="delete-modal" class="bn-modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" aria-hidden="true">
    <div class="bn-modal w-full max-w-sm bg-white rounded-2xl shadow-xl p-6 text-center" role="dialog" aria-modal="true" aria-labelledby="delete-title">
        <div class="bn-shake mx-auto w-14 h-14 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-2xl">🗑️</div>
        <h2 id="delete-title" class="mt-4 text-lg font-bold text-gray-800">Hapus user ini?</h2>
        <p class="mt-1 text-sm text-gray-500">Akun <strong id="delete-name" class="text-gray-800"></strong> akan dihapus dan tidak bisa dikembalikan.</p>
        <div class="mt-6 flex gap-3">
            <button type="button" id="delete-cancel" class="bn-act flex-1 px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-100">Batal</button>
            <a id="delete-confirm" href="#" class="bn-act flex-1 px-4 py-2.5 rounded-xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700">Ya, hapus</a>
        </div>
    </div>
</div>

<script>
    (function() {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // ===== Hitung naik pada kartu statistik =====
        document.querySelectorAll('.js-count').forEach(el => {
            const to = parseInt(el.dataset.to, 10) || 0;
            if (reduceMotion || to === 0) {
                el.textContent = to;
                return;
            }
            const duration = 900;
            const start = performance.now() + 250;

            function tick(now) {
                const t = Math.min(Math.max((now - start) / duration, 0), 1);
                const eased = 1 - Math.pow(1 - t, 3);
                el.textContent = Math.round(to * eased);
                if (t < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });

        // ===== Alert: tutup manual + otomatis hilang =====
        function closeAlert(el) {
            el.classList.add('is-closing');
            setTimeout(() => el.remove(), 450);
        }
        document.querySelectorAll('.js-alert').forEach(el => {
            el.querySelector('.js-alert-close').addEventListener('click', () => closeAlert(el));
            setTimeout(() => closeAlert(el), 6000);
        });

        // ===== Pencarian =====
        const search = document.getElementById('user-search');
        const rows = Array.from(document.querySelectorAll('.js-row'));
        const noResult = document.getElementById('no-result');
        const visibleCount = document.getElementById('visible-count');

        search.addEventListener('input', () => {
            const q = search.value.trim().toLowerCase();
            let shown = 0;
            rows.forEach(row => {
                const match = row.dataset.search.includes(q);
                row.classList.toggle('is-hidden', !match);
                if (match) {
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
        });

        // ===== Modal hapus =====
        const modal = document.getElementById('delete-modal');
        const nameEl = document.getElementById('delete-name');
        const confirmBtn = document.getElementById('delete-confirm');
        const cancelBtn = document.getElementById('delete-cancel');

        function openModal(href, name) {
            nameEl.textContent = name;
            confirmBtn.href = href;
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            cancelBtn.focus();
        }

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('.js-delete').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                openModal(link.getAttribute('href'), link.dataset.name);
            });
        });
        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    })();
</script>
<?= $this->endSection(); ?>