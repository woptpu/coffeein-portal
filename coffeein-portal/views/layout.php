<?php
declare(strict_types=1);

use App\Csrf;

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(Csrf::token()) ?>">
    <title><?= e($title ?? 'Кофеин Портал') ?> — Кофеин</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $auth->check() ? '/dashboard' : '/login' ?>">
            <span class="brand-mark">К</span>
            <span>Кофеин <small>Портал</small></span>
        </a>
        <?php if ($auth->check()): ?>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <a class="nav-link" href="/dashboard">Главная</a>
                <a class="nav-link" href="/suppliers">Поставщики</a>
                <span class="user-chip"><?= e($auth->name()) ?></span>
                <form action="/logout" method="post">
                    <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                    <button class="btn btn-sm btn-outline-light" type="submit">Выйти</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</nav>

<main class="container app-shell">
    <?php require $this->basePath . '/partials/flash.php'; ?>
    <?php require $contentTemplate; ?>
</main>

<footer class="container app-footer">
    Внутренний портал сети кофеен «Кофеин»
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/app.js"></script>
</body>
</html>

