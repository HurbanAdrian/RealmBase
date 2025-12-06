<?php
/** @var array $errors */
/** @var string|null $name */
/** @var string|null $description */
?>
<h1>Pridať kategóriu</h1>

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
        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($name ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Popis:</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <button class="btn btn-success">Uložiť</button>
    <a href="/?c=categories" class="btn btn-secondary">Späť</a>
</form>
