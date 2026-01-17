<?php
/** @var array $errors */
/** @var \App\Models\Post $post */
/** @var array $categories */
/** @var array $users */
/** @var \Framework\Core\App $this */
$link = $this->app->getLinkGenerator();

// Hodnoty pre formulár (buď z POST po chybe, alebo z DB)
$title = $title ?? $post->getTitle();
$content = $content ?? $post->getContent();
$category_id = $category_id ?? $post->getCategoryId();
$user_id = $user_id ?? $post->getUserId();

// Implementované s asistenciou AI
?>

<div class="container mt-4">
    <h1>Upraviť článok: <?= htmlspecialchars($post->getTitle()) ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $link->url('posts.edit', ['id' => $post->getId()]) ?>" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="title" class="form-label">Nadpis článku</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Kategória</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>" <?= ($category_id == $category->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Autor</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user->getId() ?>" <?= ($user_id == $user->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user->getUsername()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Aktuálny obrázok:</label>
            <?php if ($post->getImage()): ?>
                <div class="mb-2">
                    <img src="public/uploads/<?= $post->getImage() ?>" class="img-thumbnail" style="height: 100px;">
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                        <label class="form-check-label text-danger" for="remove_image">
                            Odstrániť tento obrázok pri uložení
                        </label>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-muted small">Žiadny obrázok nie je nahraný.</p>
            <?php endif; ?>

            <label for="image" class="form-label d-block">Nahrať nový (nahradí starý):</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Obsah</label>
            <textarea name="content" id="content" rows="10" class="form-control" required><?= htmlspecialchars($content) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Uložiť zmeny</button>
        <a href="<?= $link->url('posts.index') ?>" class="btn btn-secondary">Zrušiť</a>
    </form>
</div>