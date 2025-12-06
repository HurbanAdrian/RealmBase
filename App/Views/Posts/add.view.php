<?php
/** @var array $errors */
/** @var string|null $title */
/** @var string|null $content */
/** @var array $categories */
/** @var array $users */
/** @var int|null $category_id */
/** @var int|null $user_id */
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<h1>Pridať príspevok</h1>

<form method="post">
    <div class="mb-3">
        <label>Nadpis:</label>
        <label>
            <input name="title" class="form-control" value="<?= htmlspecialchars($title ?? '') ?>" required>
        </label>
    </div>

    <div class="mb-3">
        <label>Obsah:</label>
        <label>
            <textarea name="content" class="form-control" required><?= htmlspecialchars($content ?? '') ?></textarea>
        </label>
    </div>

    <div class="mb-3">
        <label>Kategória:</label>
        <label>
            <select name="category_id" class="form-control">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->getId() ?>"
                            <?= ($category_id == $cat->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="mb-3">
        <label>Používateľ:</label>
        <label>
            <select name="user_id" class="form-control">
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u->getId() ?>"
                            <?= ($user_id == $u->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u->getUsername()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <button class="btn btn-success">Uložiť</button>
</form>
