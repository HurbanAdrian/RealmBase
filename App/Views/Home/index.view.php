<?php
/** @var \Framework\Core\App $this */
$link = $this->app->getLinkGenerator();

// Implementované s asistenciou AI
?>

<div class="home-wrapper">

    <div class="home-section">
        <h1>Vitajte v RealmBase</h1>
        <p>Komunitná platforma pre zdieľanie príspevkov, kategórií a kreativity.</p>
    </div>

    <h2 class="text-center mb-4" style="color: #f5c542;">Odporúčaný obsah</h2>
    <p class="text-center text-muted mb-5">Objavte najlepšie príspevky a trendy v komunite.</p>

    <div class="home-cards">

        <div class="home-card">
            <div>
                <h3>Kategórie</h3>
                <p>Prehľad a správa kategórií obsahu. Nájdite to, čo vás zaujíma.</p>
            </div>
            <a href="<?= $link->url('categories.index') ?>" class="btn btn-primary mt-3">Otvoriť kategórie</a>
        </div>

        <div class="home-card">
            <div>
                <h3>Príspevky</h3>
                <p>Prezerajte príspevky od komunity, diskutujte a zdieľajte nápady.</p>
            </div>
            <a href="<?= $link->url('posts.index') ?>" class="btn btn-primary mt-3">Zobraziť príspevky</a>
        </div>

        <div class="home-card">
            <div>
                <h3>Účet</h3>
                <p>Prihláste sa pre správu vlastných príspevkov alebo vytvorte nové.</p>
            </div>
            <?php if ($this->app->getAppUser()->isLoggedIn()): ?>
                <span class="btn btn-success mt-3 disabled">Ste prihlásený</span>
            <?php else: ?>
                <a href="<?= \App\Configuration::LOGIN_URL ?>" class="btn btn-primary mt-3">Prihlásiť sa</a>
            <?php endif; ?>
        </div>

    </div>

    <div class="text-center mt-5 footer-note">
        <small class="text-muted">© 2026 RealmBase — VAII Semester Project</small>
    </div>

</div>