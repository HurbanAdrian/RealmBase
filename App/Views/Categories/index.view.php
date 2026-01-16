<?php
/** @var array $categories */
/** @var \App\Models\Category $category */
/** @var \Framework\Core\App $this */

$link = $this->app->getLinkGenerator();
$user = $this->app->getAppUser();
$isAdmin = ($user && $user->isLoggedIn() && $user->getRole() === 'admin');
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Kategórie</h1>

        <?php if ($isAdmin): ?>
            <a href="<?= $link->url('categories.add') ?>" class="btn btn-success">Pridať novú kategóriu</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Názov</th>
                <th>Popis</th>
                <?php if ($isAdmin): ?>
                    <th class="text-end">Akcie</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category->getId() ?></td>
                    <td>
                        <a href="<?= $link->url('posts.index') ?>&category=<?= $category->getId() ?>"
                           class="text-decoration-none fw-bold">
                            <?= htmlspecialchars($category->getName()) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($category->getDescription()) ?></td>

                    <?php if ($isAdmin): ?>
                        <td class="text-end">
                            <a href="<?= $link->url('categories.edit', ['id' => $category->getId()]) ?>" class="btn btn-sm btn-warning">Upraviť</a>
                            <a href="<?= $link->url('categories.delete', ['id' => $category->getId()]) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Naozaj zmazať?')">Zmazať</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>