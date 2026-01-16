<?php
/** @var \App\Models\Post $post */
/** @var array $comments */
/** @var \Framework\Core\App $this */
/** @var \Framework\Auth\AppUser $user */

$link = $this->app->getLinkGenerator();

if (!isset($user)) {
    $user = $this->app->getAppUser();
}
?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $link->url('posts.index') ?>">Články</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($post->getTitle()) ?></li>
        </ol>
    </nav>

    <div class="card mb-4">
        <div class="card-body">
            <h1 class="card-title"><?= htmlspecialchars($post->getTitle()) ?></h1>
            <h6 class="card-subtitle mb-2 text-muted">
                Autor: <?= htmlspecialchars($post->getAuthor()?->getUsername() ?? 'Neznámy') ?> |
                <?= date("d.m.Y H:i", strtotime($post->getCreatedAt())) ?>
            </h6>
            <hr>
            <p class="card-text">
                <?= nl2br(htmlspecialchars($post->getContent())) ?>
            </p>
        </div>
    </div>

    <div class="comments-section">
        <h3>Komentáre (<span id="comment-count"><?= count($comments) ?></span>)</h3>

        <div id="comments-list" class="mb-4">
            <?php foreach ($comments as $comment): ?>
                <div class="card mb-2" id="comment-<?= $comment->getId() ?>">
                    <div class="card-body py-2 d-flex justify-content-between align-items-start">

                        <div class="w-100">
                            <strong><?= htmlspecialchars($comment->getAuthor()?->getUsername() ?? 'Neznámy') ?></strong>
                            <small class="text-muted ms-2"><?= date("d.m.Y H:i", strtotime($comment->getCreatedAt())) ?></small>

                            <p class="mb-0 mt-1 comment-text" id="comment-text-<?= $comment->getId() ?>">
                                <?= htmlspecialchars($comment->getContent()) ?>
                            </p>
                        </div>

                        <?php if ($user->isLoggedIn() && ($user->getId() === $comment->getUserId() || $user->getRole() === 'admin')): ?>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-warning btn-edit-comment"
                                        data-id="<?= $comment->getId() ?>">
                                    ✏️
                                </button>

                                <button class="btn btn-sm btn-outline-danger btn-delete-comment"
                                        data-id="<?= $comment->getId() ?>">
                                    ×
                                </button>
                            </div> <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($user->isLoggedIn()): ?>
            <div class="card bg-light">
                <div class="card-body">
                    <h5>Pridať komentár</h5>
                    <form id="comment-form">
                        <input type="hidden" name="post_id" value="<?= $post->getId() ?>">

                        <div class="mb-3">
                            <textarea name="content" class="form-control" rows="3" placeholder="Napíš niečo..." required maxlength="1000"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Odoslať komentár</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Pre pridanie komentára sa musíte <a href="<?= \App\Configuration::LOGIN_URL ?>">prihlásiť</a>.
            </div>
        <?php endif; ?>
    </div>
</div>