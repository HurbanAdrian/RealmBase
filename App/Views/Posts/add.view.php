<?php
/** @var \App\Models\Category[] $categories */
/** @var \App\Models\User[] $users */
?>
<h1>Pridať príspevok</h1>

<form method="post">
    <div class="mb-3">
        <label>Nadpis:</label>
        <input name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Obsah:</label>
        <textarea name="content" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label>Kategória:</label>
        <select name="category_id" class="form-control">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat->getId() ?>">
                    <?= htmlspecialchars($cat->getName()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Používateľ:</label>
        <select name="user_id" class="form-control">
            <?php foreach ($users as $u): ?>
                <option value="<?= $u->getId() ?>"><?= $u->getUsername() ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-success">Uložiť</button>
</form>
