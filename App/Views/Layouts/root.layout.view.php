<?php
/**
 * @var string $contentHTML
 * @var \Framework\Auth\AppUser $user
 * @var \Framework\Support\LinkGenerator $link
 */

$currentController = $_GET['c'] ?? 'home';
$currentAction = $_GET['a'] ?? 'index';
$isHome = ($currentController === 'home' && $currentAction === 'index');

// Implementované s asistenciou AI
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= App\Configuration::APP_NAME ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="<?= $link->asset('favicons/RB_logoApple.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $link->asset('favicons/RB_logo32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $link->asset('favicons/RB_logo16.png') ?>">
    <link rel="shortcut icon" href="<?= $link->asset('favicons/RB_logo.png') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= $link->asset('css/styl.css') ?>">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $link->url('home.index') ?>">
            <img src="<?= $link->asset('images/RB_logo.png') ?>" height="32">
            REALMBASE
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= $link->url('home.index') ?>">Domov</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $link->url('categories.index') ?>">Kategórie</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $link->url('posts.index') ?>">Články</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $link->url('home.contact') ?>">Kontakt</a></li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if ($user->isLoggedIn()): ?>
                    <?php if ($user->getRole() === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger fw-bold" href="<?= $link->url('admin.index') ?>">ADMIN</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item"><span class="nav-link">👤 <?= $user->getName() ?></span></li>
                    <li class="nav-item"><a class="nav-link btn btn-warning px-3" href="<?= $link->url('auth.logout') ?>">Odhlásiť</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link btn btn-warning px-3" href="<?= App\Configuration::LOGIN_URL ?>">Prihlásiť / Registrovať</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php if ($isHome): ?>
    <div class="web-content-full mt-4">
        <div class="web-content--full">
            <?= $contentHTML ?>
        </div>
    </div>
<?php else: ?>
    <div class="container mt-4">
        <div class="web-content">
            <?= $contentHTML ?>
        </div>
    </div>
<?php endif; ?>

<script src="<?= $link->asset('js/script.js') ?>"></script>
</body>
</html>