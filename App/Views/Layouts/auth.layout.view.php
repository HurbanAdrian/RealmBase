<?php

/**
 * @var string $contentHTML
 */
/**
 * @var \Framework\Core\IAuthenticator $auth
 */
/**
 * @var \Framework\Support\LinkGenerator $link
 */
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <title><?php echo App\Configuration::APP_NAME ?></title>
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $link->asset('favicons/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $link->asset('favicons/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $link->asset('favicons/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?php echo $link->asset('favicons/site.webmanifest') ?>">
    <link rel="shortcut icon" href="<?php echo $link->asset('favicons/favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo $link->asset('css/styl.css') ?>">
    <script src="<?php echo $link->asset('js/script.js') ?>"></script>
</head>
<body>
<?php
// zistíme, či sme na homepage
$isHome = ($_GET['c'] ?? 'home') === 'home' && ($_GET['a'] ?? 'index') === 'index';
?>

<?php if ($isHome): ?>

    <!-- FULL WIDTH HOMEPAGE (bez bootstrap containeru) -->
    <div class="web-content-full">
        <?= $contentHTML ?>
    </div>

<?php else: ?>

    <!-- OSTATNÉ STRÁNKY OSTÁVAJÚ V BOOTSTRAP CONTAINERI -->
    <div class="container mt-4">
        <div class="web-content">
            <?= $contentHTML ?>
        </div>
    </div>

<?php endif; ?>

</body>
</html>
