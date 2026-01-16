<?php
/** @var array $posts */
/** @var \App\Models\Post $post */
/** @var \Framework\Core\App $this */
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