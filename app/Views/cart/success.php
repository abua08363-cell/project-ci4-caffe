<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Pembayaran Berhasil - BrewNest Coffee'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        coffee: '#1d110b',
                        brown: '#754a30',
                        caramel: '#b9854f',
                        tan: '#d9a46a',
                        paper: '#fbf8f3',
                        sand: '#ece2d4'
                    },
                    fontFamily: {
                        display: ['"Playfair Display"', 'Georgia', 'serif']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
        }

        /* Latar: gradasi hangat + lingkaran blur yang melayang */
        .bg-glow {
            background:
                radial-gradient(circle at 15% 10%, rgba(217, 164, 106, .28), transparent 40%),
                radial-gradient(circle at 90% 85%, rgba(117, 74, 48, .18), transparent 45%),
                #fbf8f3;
        }

        .blob {
            position: fixed;
            border-radius: 9999px;
            filter: blur(70px);
            opacity: .35;
            pointer-events: none;
            z-index: 0;
            animation: drift 14s ease-in-out infinite alternate;
        }

        @keyframes drift {
            to {
                transform: translate(40px, -30px) scale(1.15);
            }
        }

        /* ---------- Ikon centang ---------- */
        .check-ring {
            stroke-dasharray: 189;
            stroke-dashoffset: 189;
            animation: draw .8s cubic-bezier(.6, .1, .2, 1) .2s forwards;
        }

        .check-mark {
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            animation: draw .5s ease-out .95s forwards;
        }

        @keyframes draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        .badge-pop {
            animation: badgePop .7s cubic-bezier(.3, 1.6, .5, 1) both;
        }

        @keyframes badgePop {
            from {
                transform: scale(0) rotate(-40deg);
                opacity: 0;
            }

            to {
                transform: none;
                opacity: 1;
            }
        }

        .pulse-ring {
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            border: 2px solid #10b981;
            animation: pulseOut 2.4s ease-out 1.2s infinite;
            opacity: 0;
        }

        .pulse-ring.r2 {
            animation-delay: 1.9s;
        }

        @keyframes pulseOut {
            0% {
                transform: scale(1);
                opacity: .6;
            }

            100% {
                transform: scale(1.9);
                opacity: 0;
            }
        }

        /* ---------- Kartu & reveal bertahap ---------- */
        .card-in {
            animation: cardIn .9s cubic-bezier(.2, .8, .2, 1) both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(40px) scale(.96);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .rise {
            opacity: 0;
            animation: rise .7s cubic-bezier(.2, .8, .2, 1) forwards;
            animation-delay: var(--d, 0s);
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* Tepi struk bergerigi */
        .zigzag {
            position: relative;
        }

        .zigzag::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -12px;
            height: 12px;
            background:
                linear-gradient(135deg, #fff 6px, transparent 0) 0 0 / 16px 12px,
                linear-gradient(225deg, #fff 6px, transparent 0) 0 0 / 16px 12px;
            background-repeat: repeat-x;
            filter: drop-shadow(0 4px 3px rgba(29, 17, 11, .05));
        }

        /* Garis putus-putus ala struk */
        .dash {
            border-top: 2px dashed #e7e0d6;
        }

        /* ---------- Stepper status ---------- */
        .progress-fill {
            width: 0;
            animation: fillBar 1.4s cubic-bezier(.6, .1, .2, 1) 1.6s forwards;
        }

        @keyframes fillBar {
            to {
                width: 50%;
            }
        }

        .step-dot-now {
            animation: dotPulse 1.6s ease-out 2.8s infinite;
        }

        @keyframes dotPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(185, 133, 79, .6);
            }

            100% {
                box-shadow: 0 0 0 12px rgba(185, 133, 79, 0);
            }
        }

        /* ---------- Uap kopi ---------- */
        .steam i {
            position: absolute;
            bottom: 0;
            width: 4px;
            height: 22px;
            border-radius: 9px;
            background: rgba(185, 133, 79, .55);
            filter: blur(3px);
            opacity: 0;
            animation: steam 2.6s ease-in infinite;
        }

        .steam i:nth-child(1) {
            left: 6px;
        }

        .steam i:nth-child(2) {
            left: 18px;
            animation-delay: .9s;
        }

        .steam i:nth-child(3) {
            left: 30px;
            animation-delay: 1.8s;
        }

        @keyframes steam {
            0% {
                opacity: 0;
                transform: translateY(0) scaleX(1);
            }

            30% {
                opacity: .7;
            }

            100% {
                opacity: 0;
                transform: translateY(-40px) scaleX(1.8);
            }
        }

        /* ---------- Tombol kilau ---------- */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }

        .btn-shine::after {
            content: '';
            position: absolute;
            top: 0;
            left: -80%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .4), transparent);
            transform: skewX(-20deg);
            animation: shine 3.2s ease-in-out 3s infinite;
        }

        @keyframes shine {
            0% {
                left: -80%;
            }

            40%,
            100% {
                left: 140%;
            }
        }

        /* Confetti */
        .confetti {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 60;
            pointer-events: none;
            will-change: transform, opacity;
        }

        @media print {

            .no-print,
            .blob {
                display: none !important;
            }

            body {
                background: #fff !important;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-delay: 0s !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }

            .check-ring,
            .check-mark {
                stroke-dashoffset: 0;
            }

            .progress-fill {
                width: 50%;
            }
        }
    </style>
</head>

<body class="bg-glow min-h-screen text-coffee antialiased">

    <div class="blob left-[-80px] top-[20%] h-72 w-72 bg-tan"></div>
    <div class="blob bottom-[-60px] right-[-60px] h-80 w-80 bg-emerald-300" style="animation-delay:-6s"></div>

    <!-- Header -->
    <header class="no-print relative z-10 border-b border-stone-200/70 bg-paper/80 backdrop-blur">
        <div class="mx-auto flex max-w-3xl items-center justify-between px-5 py-3.5">
            <a href="<?= base_url('/') ?>" class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl border border-coffee/70">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 2v2M14 2v2M6 2v2" />
                        <path d="M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1" />
                    </svg>
                </span>
                <span>
                    <span class="block font-display text-2xl font-semibold leading-none">BrewNest</span>
                    <span class="mt-1 block text-[10px] font-semibold tracking-[2.5px] text-stone-500">COFFEE &amp; EATERY</span>
                </span>
            </a>
            <a href="<?= base_url('/') ?>" class="text-xs font-semibold text-brown transition hover:text-caramel">← Beranda</a>
        </div>
    </header>

    <main class="relative z-10 mx-auto max-w-xl px-4 pb-20 pt-10 sm:pt-14">

        <!-- Ikon sukses -->
        <div class="relative mx-auto mb-6 grid h-28 w-28 place-items-center">
            <span class="pulse-ring"></span>
            <span class="pulse-ring r2"></span>
            <div class="badge-pop grid h-28 w-28 place-items-center rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-xl shadow-emerald-500/30">
                <svg viewBox="0 0 80 80" class="h-16 w-16" fill="none" stroke="#fff" stroke-width="5" stroke-linecap="round" stroke-linejoin="round">
                    <circle class="check-ring" cx="40" cy="40" r="30" stroke-opacity=".45" />
                    <path class="check-mark" d="M27 41l9 9 18-20" />
                </svg>
            </div>
        </div>

        <div class="mb-8 text-center">
            <h1 class="rise font-display text-4xl font-semibold sm:text-5xl" style="--d:.9s">Pembayaran <span class="italic text-caramel">Berhasil!</span></h1>
            <p class="rise mx-auto mt-3 max-w-sm text-sm leading-relaxed text-stone-600" style="--d:1.05s">Terima kasih sudah memesan di BrewNest. Barista kami sedang menyiapkan pesananmu dengan sepenuh hati.</p>
        </div>

        <!-- Kartu struk -->
        <div class="card-in" style="animation-delay:.5s">
            <div class="zigzag overflow-visible rounded-t-3xl bg-white shadow-xl shadow-coffee/10 ring-1 ring-stone-200/70">

                <!-- Kepala struk -->
                <div class="relative overflow-hidden rounded-t-3xl bg-coffee px-6 py-5 text-white">
                    <div class="steam absolute right-8 top-3 hidden h-10 w-10 sm:block"><i></i><i></i><i></i></div>
                    <svg class="absolute -right-4 -top-4 h-28 w-28 text-white/5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 8H4a1 1 0 0 0-1 1v8a4 4 0 0 0 4 4h6a4 4 0 0 0 4-4V9a1 1 0 0 0-1-1z" />
                    </svg>
                    <p class="text-[10px] font-semibold uppercase tracking-[3px] text-tan">Struk Pesanan</p>
                    <p class="mt-1 font-display text-2xl font-semibold"><?= esc($order['order_number'] ?? $order['id'] ?? '-'); ?></p>
                    <p class="mt-0.5 text-[11px] text-stone-400"><?= date('d M Y, H:i'); ?> WIB</p>
                </div>

                <div class="space-y-4 px-6 pb-10 pt-6">

                    <div class="rise grid grid-cols-2 gap-4 text-xs" style="--d:1.3s">
                        <div>
                            <span class="block text-stone-400">Nama Pemesan</span>
                            <span class="mt-0.5 block text-sm font-bold"><?= esc($order['customer_name'] ?? $order['name'] ?? 'Tamu'); ?></span>
                        </div>
                        <div class="text-right">
                            <span class="block text-stone-400">Meja / Alamat</span>
                            <span class="mt-0.5 block text-sm font-bold"><?= esc($order['table_number'] ?? $order['table'] ?? '-'); ?></span>
                        </div>
                    </div>

                    <div class="dash"></div>

                    <div class="rise flex items-center justify-between text-xs" style="--d:1.45s">
                        <span class="text-stone-500">Metode Pembayaran</span>
                        <span class="rounded-full bg-sand px-3 py-1 font-bold"><?= esc($order['payment_method'] ?? 'Tunai'); ?></span>
                    </div>

                    <div class="rise flex items-center justify-between text-xs" style="--d:1.6s">
                        <span class="text-stone-500">Status</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-bold uppercase text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Lunas (Paid)
                        </span>
                    </div>

                    <div class="dash"></div>

                    <div class="rise flex items-end justify-between" style="--d:1.75s">
                        <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Pembayaran</span>
                        <span class="font-display text-3xl font-semibold text-brown">Rp <span id="totalNum" data-total="<?= (float) ($order['total'] ?? 0); ?>">0</span></span>
                    </div>

                    <!-- Status pesanan -->
                    <div class="rise rounded-2xl bg-paper p-4 ring-1 ring-stone-200/70" style="--d:1.9s">
                        <p class="mb-4 text-[10px] font-bold uppercase tracking-[2px] text-stone-400">Status Pesanan</p>
                        <div class="relative px-3">
                            <div class="absolute left-3 right-3 top-[13px] h-1 rounded-full bg-stone-200"></div>
                            <div class="absolute left-3 top-[13px] h-1 rounded-full bg-gradient-to-r from-emerald-500 to-caramel" style="right:calc(50% - .75rem + 0px)">
                                <div class="progress-fill h-full rounded-full bg-gradient-to-r from-emerald-500 to-caramel" style="width:0"></div>
                            </div>
                            <div class="relative flex justify-between text-center">
                                <div class="w-16">
                                    <span class="mx-auto grid h-7 w-7 place-items-center rounded-full bg-emerald-500 text-xs font-bold text-white">✓</span>
                                    <p class="mt-2 text-[10px] font-bold">Diterima</p>
                                </div>
                                <div class="w-16">
                                    <span class="step-dot-now mx-auto grid h-7 w-7 place-items-center rounded-full bg-caramel text-xs text-white">☕</span>
                                    <p class="mt-2 text-[10px] font-bold">Diproses</p>
                                </div>
                                <div class="w-16">
                                    <span class="mx-auto grid h-7 w-7 place-items-center rounded-full bg-stone-200 text-xs text-stone-400">🍽️</span>
                                    <p class="mt-2 text-[10px] font-semibold text-stone-400">Siap</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Aksi -->
        <div class="no-print rise mt-12 flex flex-col gap-3 sm:flex-row" style="--d:2.2s">
            <a href="<?= base_url('/') ?>" class="btn-shine flex flex-1 items-center justify-center gap-2 rounded-xl bg-coffee px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-white shadow-lg shadow-coffee/20 transition hover:-translate-y-0.5 hover:bg-brown">
                Kembali ke Beranda
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
            <button type="button" onclick="window.print()" class="flex flex-1 items-center justify-center gap-2 rounded-xl border border-stone-300 bg-white/70 px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider transition hover:-translate-y-0.5 hover:border-coffee">
                Cetak Struk
            </button>
        </div>

        <p class="no-print rise mt-6 text-center text-[11px] text-stone-500" style="--d:2.35s">Simpan nomor pesanan ini jika ingin bertanya ke kasir.</p>
    </main>

    <script>
        (function() {
            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            /* Keranjang sudah dibayar -> kosongkan */
            try {
                localStorage.removeItem('brewNestCart');
                localStorage.removeItem('customerName');
                localStorage.removeItem('customerTable');
            } catch (e) {}

            /* Hitung naik total pembayaran */
            const num = document.getElementById('totalNum');
            const total = Number(num.dataset.total) || 0;
            const fmt = n => Math.round(n).toLocaleString('id-ID');
            if (reduce || total === 0) {
                num.textContent = fmt(total);
            } else {
                const start = performance.now() + 1750;
                const dur = 1400;
                (function tick(now) {
                    const p = Math.min(Math.max((now - start) / dur, 0), 1);
                    num.textContent = fmt(total * (1 - Math.pow(1 - p, 3)));
                    if (p < 1) requestAnimationFrame(tick);
                })(performance.now());
            }

            if (reduce) return;

            /* Confetti biji kopi & warna hangat */
            const colors = ['#b9854f', '#d9a46a', '#754a30', '#10b981', '#fbbf24', '#1d110b'];

            function burst(originX, originY, count, spread) {
                for (let i = 0; i < count; i++) {
                    const el = document.createElement('div');
                    el.className = 'confetti';
                    const size = 6 + Math.random() * 7;
                    const round = Math.random() > .55;
                    el.style.width = size + 'px';
                    el.style.height = (round ? size : size * 1.8) + 'px';
                    el.style.background = colors[(Math.random() * colors.length) | 0];
                    el.style.borderRadius = round ? '50%' : '2px';
                    el.style.left = originX + 'px';
                    el.style.top = originY + 'px';
                    document.body.appendChild(el);

                    const angle = (-90 + (Math.random() - .5) * spread) * Math.PI / 180;
                    const v = 220 + Math.random() * 380;
                    const dx = Math.cos(angle) * v;
                    const dy = Math.sin(angle) * v;
                    const fall = window.innerHeight * (.55 + Math.random() * .5);
                    const rot = (Math.random() - .5) * 1080;

                    el.animate([{
                            transform: 'translate(0,0) rotate(0deg)',
                            opacity: 1
                        },
                        {
                            transform: `translate(${dx * .6}px, ${dy * .6}px) rotate(${rot * .5}deg)`,
                            opacity: 1,
                            offset: .35
                        },
                        {
                            transform: `translate(${dx}px, ${dy + fall}px) rotate(${rot}deg)`,
                            opacity: 0
                        }
                    ], {
                        duration: 1800 + Math.random() * 1400,
                        easing: 'cubic-bezier(.2,.6,.4,1)',
                        delay: Math.random() * 150
                    }).onfinish = () => el.remove();
                }
            }

            setTimeout(() => {
                const w = window.innerWidth,
                    h = window.innerHeight;
                burst(w / 2, h * .28, 90, 140);
                setTimeout(() => burst(w * .15, h * .6, 40, 80), 250);
                setTimeout(() => burst(w * .85, h * .6, 40, 80), 400);
            }, 800);
        })();
    </script>

</body>

</html>