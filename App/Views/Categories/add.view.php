<h1>Pridať kategóriu</h1>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Názov:</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Popis:</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <button class="btn btn-success">Uložiť</button>
    <a href="/?c=categories" class="btn btn-secondary">Späť</a>
</form>
