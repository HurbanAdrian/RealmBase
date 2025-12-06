<?php
/**
 * 
 *
 * @var \App\Models\Category[] $categories 
 */
?>

<h1>Kategórie</h1>

<a href="/?c=categories&a=add" class="btn btn-primary mb-3">Pridať kategóriu</a>

<table class="table table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Názov</th>
        <th>Popis</th>
        <th>Akcie</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?php echo $cat->getId() ?></td>
            <td><?php echo htmlspecialchars($cat->getName()) ?></td>
            <td><?php echo htmlspecialchars($cat->getDescription()) ?></td>

            <td>
                <a href="/?c=categories&a=edit&id=<?php echo $cat->getId() ?>" class="btn btn-sm btn-warning">Upraviť</a>
                <a href="/?c=categories&a=delete&id=<?php echo $cat->getId() ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Naozaj zmazať?')">Zmazať</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
