<?php
/** @var \Framework\Core\App $this */
$link = $this->app->getLinkGenerator();
?>

<div class="home-wrapper">

    <!-- HERO -->
    <section class="home-section hero">
        <h1>Welcome to RealmBase</h1>
        <p>A community platform for sharing posts, categories and creativity.</p>
    </section>

    <!-- FEATURED -->
    <section class="home-section featured">
        <h2>Featured Content</h2>
        <p>Explore highlighted posts and trending content.</p>
    </section>

    <!-- GRID CARDS -->
    <section class="home-cards">
        <div class="home-card">
            <h3>Categories</h3>
            <p>View and manage content categories.</p>
            <a href="<?= $link->url('categories.index') ?>" class="btn btn-primary w-100">Open Categories</a>
        </div>

        <div class="home-card">
            <h3>Posts</h3>
            <p>Browse posts submitted by the community.</p>
            <a href="<?= $link->url('posts.index') ?>" class="btn btn-primary w-100">View Posts</a>
        </div>

        <div class="home-card">
            <h3>Account</h3>
            <p>Log in to manage posts or create new ones.</p>
            <a href="<?= App\Configuration::LOGIN_URL ?>" class="btn btn-primary w-100">Login</a>
        </div>
    </section>

    <!-- FOOTER -->
    <section class="footer-note text-center">
        <small>© <?= date("Y") ?> RealmBase — VAII Semester Project</small>
    </section>

</div>
