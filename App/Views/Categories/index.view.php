<?php
/**
 *
 *
 * @var \App\Models\Category[] $categories
 */
?>

<h1>Kategórie</h1>

<?php
$user = $this->app->getAppUser();
?>

<?php if ($user->isLoggedIn()) : ?>
    <a class="btn btn-success mb-3" href="<?= $this->app->getLinkGenerator()->url('categories.add') ?>">Add Category</a>
<?php endif; ?>

<table class="table table-dark table-striped table-custom">
    <thead>
    <tr>
        <th>ID</th>
        <th>Názov</th>
        <th>Popis</th>
        <th>Akcie</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($categories as $cat) : ?>
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
