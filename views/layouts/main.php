<?php
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= h($pageTitle ?? 'Médiathèque interne') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<main>
    <?php if (!empty($flash)): ?>
        <div class="flash <?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <?= $content ?>
</main>
</body>
</html>
