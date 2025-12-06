<?php
/** @var \App\Models\Category $category */
?>

<h1>Upraviť kategóriu</h1>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Názov:</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($category->getName()) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Popis:</label>
        <textarea name="description" class="form-control"><?= htmlspecialchars($category->getDescription()) ?></textarea>
    </div>

    <button class="btn btn-success">Uložiť</button>
    <a href="/?c=categories" class="btn btn-secondary">Späť</a>
</form>
