<?php
/** @var \App\Models\Category $category */
?>

<h1>Upraviť kategóriu</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Názov:</label>
        <label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name ?? $category->getName()) ?>"
        </label>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Popis:</label>
        <label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($category->getDescription()) ?></textarea>
        </label>
    </div>

    <button class="btn btn-success">Uložiť</button>
    <a href="/?c=categories" class="btn btn-secondary">Späť</a>
</form>
