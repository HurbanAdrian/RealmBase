<?php
/** @var array $errors */
/** @var array $categories */
/** @var array $users */
/** @var string|null $title */
/** @var string|null $content */
/** @var int|null $category_id */
/** @var int|null $user_id */
/** @var \Framework\Core\App $this */
$link = $this->app->getLinkGenerator();

// Implementované s asistenciou AI
?>

<div class="container mt-4">
    <h1>Pridať nový článok</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $link->url('posts.add') ?>" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="title" class="form-label">Nadpis článku</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($title ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Kategória</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">-- Vyberte kategóriu --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>" <?= (isset($category_id) && $category_id == $category->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if ($this->app->getAppUser()->getRole() === 'admin'): ?>
            <div class="mb-3">
                <label for="user_id" class="form-label">Autor (Admin override)</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">-- Vyberte autora (voliteľné) --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user->getId() ?>" <?= (isset($user_id) && $user_id == $user->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($user->getUsername()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="image" class="form-label">Titulný obrázok (voliteľné):</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Obsah</label>
            <textarea name="content" id="content" rows="10" class="form-control" required><?= htmlspecialchars($content ?? '') ?></textarea>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Uložiť článok</button>
        <a href="<?= $link->url('posts.index') ?>" class="btn btn-secondary">Zrušiť</a>
    </form>
</div>