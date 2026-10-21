
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title ?? 'Admin BrewNest') ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-stone-100">

    <div class="flex min-h-screen">

        <?= $this->include('layout/sidebar') ?>

        <div class="flex min-w-0 flex-1 flex-col">

            <?= $this->include('layout/navbar') ?>

            <main class="flex-1 p-6">
                <?= $this->renderSection('content') ?>
            </main>

            <?= $this->include('layout/bottomav') ?>

        </div>

    </div>

</body>
</html>
