<?php
/**
 *
 *
 * @var \App\Models\Post[] $posts
 */
?>
<h1>Príspevky</h1>

<a href="/?c=posts&a=add" class="btn btn-primary mb-3">Pridať príspevok</a>

<table class="table table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nadpis</th>
        <th>Obsah</th>
        <th>Kategória</th>
        <th>Používateľ</th>
        <th>Akcie</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($posts as $p) : ?>
        <tr>
            <td><?php echo $p->getId() ?></td>
            <td><?php echo htmlspecialchars($p->getTitle()) ?></td>
            <td><?php echo htmlspecialchars(substr($p->getContent(), 0, 40)) ?>...</td>
            <td><?php echo $p->getCategoryId() ?></td>
            <td><?php echo $p->getUserId() ?></td>
            <td>
                <a href="/?c=posts&a=edit&id=<?php echo $p->getId() ?>" class="btn btn-warning btn-sm">Upraviť</a>
                <a href="/?c=posts&a=delete&id=<?php echo $p->getId() ?>"
                   onclick="return confirm('Naozaj zmazať?')"
                   class="btn btn-danger btn-sm">Zmazať</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
