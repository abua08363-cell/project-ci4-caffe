<?= $this->extend('layout/template'); ?> <!-- Sesuaikan nama layout admin kamu (misal: 'layout/template' atau 'admin/template') -->

<?= $this->section('content'); ?>

<style>
    /* ===== Animasi masuk (satu urutan saat halaman dibuka) ===== */
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

    /* ===== Input: naik sedikit + glow saat fokus ===== */
    .bn-field {
        transition: border-color .25s, box-shadow .25s, transform .25s, background-color .25s;
    }

    .bn-field:hover {
        border-color: #c9a98f;
    }

    .bn-field:focus {
        border-color: #754a30;
        box-shadow: 0 0 0 4px rgba(117, 74, 48, .15);
        transform: translateY(-2px);
    }

    /* ===== Tombol kembali: panah bergeser ===== */
    .bn-back span {
        display: inline-block;
        transition: transform .25s;
    }

    .bn-back:hover span {
        transform: translateX(-5px);
    }

    /* ===== Dropzone upload ===== */
    .bn-drop {
        transition: transform .3s cubic-bezier(.22, 1, .36, 1), background-color .3s, border-color .3s, box-shadow .3s;
    }

    .bn-drop:hover,
    .bn-drop.is-over {
        border-color: #754a30;
        background-color: #fbf6f2;
        box-shadow: 0 10px 30px -12px rgba(117, 74, 48, .35);
    }

    .bn-drop.is-over {
        transform: scale(1.03);
    }

    .bn-drop svg {
        transition: transform .4s cubic-bezier(.34, 1.56, .64, 1), color .3s;
    }

    .bn-drop:hover svg,
    .bn-drop.is-over svg {
        transform: translateY(-6px) rotate(-6deg) scale(1.1);
        color: #754a30;
    }

    /* ===== Preview gambar ===== */
    @keyframes bn-pop {
        0% {
            opacity: 0;
            transform: scale(.85) rotate(-2deg);
        }

        100% {
            opacity: 1;
            transform: scale(1) rotate(0);
        }
    }

    .bn-pop {
        animation: bn-pop .5s cubic-bezier(.34, 1.56, .64, 1) both;
    }

    .bn-badge {
        transition: background-color .3s, color .3s;
    }

    /* ===== Tombol simpan: kilau + loading ===== */
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

    .bn-btn.is-loading {
        pointer-events: none;
        opacity: .85;
    }

    @keyframes bn-spin {
        to {
            transform: rotate(360deg);
        }
    }

    .bn-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, .4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: bn-spin .7s linear infinite;
    }

    /* ===== Hormati preferensi reduce motion ===== */
    @media (prefers-reduced-motion: reduce) {

        .bn-in,
        .bn-pop {
            animation: none;
            opacity: 1;
        }

        .bn-field,
        .bn-drop,
        .bn-btn,
        .bn-back span,
        .bn-drop svg {
            transition: none;
        }
    }
</style>

<?php
$currentImage = !empty($menu['image'])
    ? base_url('uploads/menus/' . $menu['image'])
    : 'https://via.placeholder.com/300x200?text=No+Image';
?>

<div class="w-full p-4 md:p-6 lg:p-8">

    <!-- Header Page -->
    <div class="bn-in flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6" style="--i:0">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Menu</h1>
            <p class="text-sm text-gray-500">Ubah informasi menu <strong><?= esc($menu['title']); ?></strong> di katalog BrewNest.</p>
        </div>
        <a href="<?= base_url('admin/menus'); ?>" class="bn-back self-start sm:self-auto px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full transition font-medium">
            <span>←</span> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bn-in w-full bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-8" style="--i:1">
        <form id="menu-form" action="<?= base_url('admin/menus/update/' . $menu['id']); ?>" method="POST" enctype="multipart/form-data" class="w-full space-y-6">
            <?= csrf_field(); ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 w-full">

                <!-- Kolom Kiri: Input Data Utama (2 Kolom) -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="bn-in" style="--i:2">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="<?= esc($menu['title']); ?>" placeholder="Contoh: Espresso Macchiato" required
                            class="bn-field w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none text-gray-800">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bn-in" style="--i:3">
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select id="category" name="category" required
                                class="bn-field w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none bg-white text-gray-800">
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <option value="Coffee" <?= ($menu['category'] == 'Coffee') ? 'selected' : ''; ?>>Coffee</option>
                                <option value="Non-Coffee" <?= ($menu['category'] == 'Non-Coffee') ? 'selected' : ''; ?>>Non-Coffee</option>
                                <option value="Pastry & Snack" <?= ($menu['category'] == 'Pastry & Snack') ? 'selected' : ''; ?>>Pastry & Snack</option>
                                <option value="Dessert" <?= ($menu['category'] == 'Dessert') ? 'selected' : ''; ?>>Dessert</option>
                            </select>
                        </div>
                        <div class="bn-in" style="--i:4">
                            <label for="price" class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">Rp</span>
                                <input type="number" id="price" name="price" value="<?= esc($menu['price']); ?>" placeholder="25000" min="0" required
                                    class="bn-field w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none text-gray-800">
                            </div>
                            <p id="price-hint" class="mt-1 text-xs text-[#754a30] font-medium h-4 transition-opacity duration-300 opacity-0"></p>
                        </div>
                    </div>

                    <div class="bn-in" style="--i:5">
                        <div class="flex justify-between items-end mb-1">
                            <label for="description" class="block text-sm font-semibold text-gray-700">Deskripsi Menu</label>
                            <span id="desc-count" class="text-xs text-gray-400 tabular-nums">0 / 300</span>
                        </div>
                        <textarea id="description" name="description" rows="6" maxlength="300" placeholder="Tulis deskripsi rinci rasa, cita rasa, atau bahan menu..."
                            class="bn-field w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none text-gray-800 resize-y"><?= esc($menu['description']); ?></textarea>
                    </div>
                </div>

                <!-- Kolom Kanan: Upload & Preview Foto -->
                <div class="bn-in space-y-4" style="--i:3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Menu</label>

                    <label for="image" id="dropzone"
                        class="bn-drop block border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center bg-gray-50 cursor-pointer">
                        <div class="space-y-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-sm font-medium text-gray-700">Tarik foto baru ke sini atau klik untuk mengganti</p>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP max 2MB</p>
                        </div>
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" class="hidden">

                    <p id="image-error" class="hidden text-xs text-red-500 font-medium"></p>

                    <!-- Gambar Saat Ini & Preview -->
                    <div id="preview-wrap" class="relative">
                        <img id="image-preview" src="<?= $currentImage; ?>" alt="Foto Menu"
                            data-original="<?= $currentImage; ?>"
                            class="mx-auto h-48 w-full object-cover rounded-xl border border-gray-200 shadow-sm">

                        <span id="image-badge" class="bn-badge absolute top-2 left-2 px-2.5 py-1 rounded-full bg-black/60 text-white text-xs font-medium">
                            Foto saat ini
                        </span>

                        <button type="button" id="reset-image" aria-label="Kembalikan foto lama"
                            class="hidden absolute top-2 right-2 px-3 h-8 rounded-full bg-white/90 text-gray-700 text-xs font-semibold hover:bg-white hover:scale-105 transition duration-300 shadow">
                            Batalkan
                        </button>

                        <p id="file-name" class="mt-2 text-xs text-gray-500 truncate text-center"></p>
                    </div>
                </div>

            </div>

            <hr class="border-gray-100 my-6">

            <!-- Tombol Aksi -->
            <div class="bn-in flex flex-col-reverse sm:flex-row sm:justify-end gap-3" style="--i:6">
                <a href="<?= base_url('admin/menus'); ?>" class="px-6 py-2.5 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-100 transition text-sm font-semibold text-center">
                    Batal
                </a>
                <button type="submit" id="submit-btn" class="bn-btn px-6 py-2.5 rounded-full bg-[#754a30] text-white hover:bg-[#21140e] text-sm font-semibold shadow-md inline-flex items-center justify-center gap-2">
                    <span id="submit-spinner" class="bn-spinner hidden"></span>
                    <span id="submit-label">Update Menu</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    (function() {
        const MAX_SIZE = 2 * 1024 * 1024; // 2MB

        const input = document.getElementById('image');
        const dropzone = document.getElementById('dropzone');
        const preview = document.getElementById('image-preview');
        const badge = document.getElementById('image-badge');
        const fileName = document.getElementById('file-name');
        const errorBox = document.getElementById('image-error');
        const resetBtn = document.getElementById('reset-image');
        const original = preview.dataset.original;

        function pop() {
            preview.classList.remove('bn-pop');
            void preview.offsetWidth;
            preview.classList.add('bn-pop');
        }

        function showError(msg) {
            errorBox.textContent = msg;
            errorBox.classList.remove('hidden');
        }

        function resetImage() {
            input.value = '';
            preview.src = original;
            badge.textContent = 'Foto saat ini';
            badge.classList.remove('bg-[#754a30]');
            badge.classList.add('bg-black/60');
            fileName.textContent = '';
            resetBtn.classList.add('hidden');
            errorBox.classList.add('hidden');
            pop();
        }

        function handleFile(file) {
            errorBox.classList.add('hidden');
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                input.value = '';
                return showError('File harus berupa gambar (PNG, JPG, atau WEBP).');
            }
            if (file.size > MAX_SIZE) {
                input.value = '';
                return showError('Ukuran foto melebihi 2MB. Pilih foto yang lebih kecil.');
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                badge.textContent = 'Foto baru';
                badge.classList.remove('bg-black/60');
                badge.classList.add('bg-[#754a30]');
                fileName.textContent = file.name + ' · ' + (file.size / 1024).toFixed(0) + ' KB';
                resetBtn.classList.remove('hidden');
                pop();
            };
            reader.readAsDataURL(file);
        }

        input.addEventListener('change', e => handleFile(e.target.files[0]));
        resetBtn.addEventListener('click', resetImage);

        // Drag & drop
        ['dragenter', 'dragover'].forEach(evt =>
            dropzone.addEventListener(evt, e => {
                e.preventDefault();
                dropzone.classList.add('is-over');
            })
        );
        ['dragleave', 'drop'].forEach(evt =>
            dropzone.addEventListener(evt, e => {
                e.preventDefault();
                dropzone.classList.remove('is-over');
            })
        );
        dropzone.addEventListener('drop', e => {
            const file = e.dataTransfer.files[0];
            if (!file) return;
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            handleFile(file);
        });

        // Format harga langsung (Rp 25.000)
        const price = document.getElementById('price');
        const priceHint = document.getElementById('price-hint');

        function updatePrice() {
            const v = parseInt(price.value, 10);
            if (isNaN(v)) {
                priceHint.classList.add('opacity-0');
                return;
            }
            priceHint.textContent = 'Rp ' + v.toLocaleString('id-ID');
            priceHint.classList.remove('opacity-0');
        }
        price.addEventListener('input', updatePrice);
        updatePrice();

        // Counter deskripsi
        const desc = document.getElementById('description');
        const count = document.getElementById('desc-count');

        function updateCount() {
            count.textContent = desc.value.length + ' / 300';
            count.classList.toggle('text-red-500', desc.value.length >= 290);
        }
        desc.addEventListener('input', updateCount);
        updateCount();

        // Loading state saat submit
        const form = document.getElementById('menu-form');
        form.addEventListener('submit', () => {
            if (!form.checkValidity()) return;
            const btn = document.getElementById('submit-btn');
            btn.classList.add('is-loading');
            document.getElementById('submit-spinner').classList.remove('hidden');
            document.getElementById('submit-label').textContent = 'Menyimpan...';
        });
    })();
</script>
<?= $this->endSection(); ?>