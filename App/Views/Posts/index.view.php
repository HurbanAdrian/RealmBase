<?php
/** @var array $posts */
/** @var \App\Models\Post $post */
/** @var \Framework\Core\App $this */
$currentCategory = $data['currentCategory'] ?? $_GET['category'] ?? 0;
$currentSort = $data['currentSort'] ?? $_GET['sort'] ?? 'created_at';
$currentOrder = strtoupper($data['currentOrder'] ?? $_GET['order'] ?? 'DESC');

$link = $this->app->getLinkGenerator();
$user = $this->app->getAppUser(); // Prihlásený užívateľ
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Zoznam článkov</h1>

        <?php if ($user->isLoggedIn()): ?>
            <a href="<?= $link->url('posts.add') ?>" class="btn btn-success">Pridať nový článok</a>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <input type="text" id="tableSearch" class="form-control w-100" placeholder="🔍 Hľadať článok...">
    </div>

    <div class="mb-3 d-flex gap-2 align-items-center">
        <span class="text-muted">Zoradiť:</span>

        <div class="btn-group">
            <a href="<?= $link->url('posts.index', ['category' => $currentCategory, 'sort' => 'created_at', 'order' => 'DESC']) ?>"
               class="btn btn-sm <?= ($currentSort == 'created_at' && $currentOrder == 'DESC') ? 'btn-warning' : 'btn-outline-warning' ?>">
                Najnovšie
            </a>
            <a href="<?= $link->url('posts.index', ['category' => $currentCategory, 'sort' => 'created_at', 'order' => 'ASC']) ?>"
               class="btn btn-sm <?= ($currentSort == 'created_at' && $currentOrder == 'ASC') ? 'btn-warning' : 'btn-outline-warning' ?>">
                Najstaršie
            </a>
        </div>

        <div class="btn-group">
            <a href="<?= $link->url('posts.index', ['category' => $currentCategory, 'sort' => 'title', 'order' => 'ASC']) ?>"
               class="btn btn-sm <?= ($currentSort == 'title' && $currentOrder == 'ASC') ? 'btn-warning' : 'btn-outline-warning' ?>">
                A-Z
            </a>
            <a href="<?= $link->url('posts.index', ['category' => $currentCategory, 'sort' => 'title', 'order' => 'DESC']) ?>"
               class="btn btn-sm <?= ($currentSort == 'title' && $currentOrder == 'DESC') ? 'btn-warning' : 'btn-outline-warning' ?>">
                Z-A
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Názov</th>
                <th>Kategória</th>
                <th>Autor</th>
                <th>Vytvorené</th>
                <th class="text-end">Akcie</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= $post->getId() ?></td>

                    <td>
                        <a href="<?= $link->url('posts.show', ['id' => $post->getId()]) ?>">
                            <?= htmlspecialchars($post->getTitle()) ?>
                        </a>
                    </td>

                    <td>
                        <?php
                        $cat = $post->getCategory();
                        echo $cat ? htmlspecialchars($cat->getName()) : '<em>Bez kategórie</em>';
                        ?>
                    </td>
                    <td>
                        <?php
                        $author = $post->getAuthor();
                        echo $author ? htmlspecialchars($author->getUsername()) : 'Neznámy';
                        ?>
                    </td>
                    <td><?= date("d.m.Y", strtotime($post->getCreatedAt())) ?></td>

                    <td class="text-end">
                        <?php
                        $canEdit = $user->isLoggedIn() && ($user->getId() === $post->getUserId() || $user->getRole() === 'admin');
                        ?>

                        <?php if ($canEdit): ?>
                            <a href="<?= $link->url('posts.edit', ['id' => $post->getId()]) ?>" class="btn btn-sm btn-warning">Upraviť</a>
                            <a href="<?= $link->url('posts.delete', ['id' => $post->getId()]) ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Zmazať?')">
                                Zmazať
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>