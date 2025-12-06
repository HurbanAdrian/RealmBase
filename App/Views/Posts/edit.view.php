<?php
/** @var \App\Models\Post $post */
/** @var \App\Models\Category[] $categories */
/** @var \App\Models\User[] $users */
?>

<h1>Upraviť príspevok</h1>

<form method="post">
    <div class="mb-3">
        <label>Nadpis:</label>
        <input name="title" class="form-control" value="<?= htmlspecialchars($post->getTitle()) ?>" required>
    </div>

    <div class="mb-3">
        <label>Obsah:</label>
        <textarea name="content" class="form-control" required><?= htmlspecialchars($post->getContent()) ?></textarea>
    </div>

    <div class="mb-3">
        <label>Kategória:</label>
        <select name="category_id" class="form-control">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat->getId() ?>"
                    <?= $cat->getId() == $post->getCategoryId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat->getName()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Používateľ:</label>
        <select name="user_id" class="form-control">
            <?php foreach ($users as $u): ?>
                <option value="<?= $u->getId() ?>"
                    <?= $u->getId() == $post->getUserId() ? 'selected' : '' ?>>
                    <?= $u->getUsername() ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-primary">Uložiť</button>
</form>
