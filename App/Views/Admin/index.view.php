<?php
/** @var \App\Models\User[] $users */
/** @var \Framework\Core\App $this */
$link = $this->app->getLinkGenerator();
$currentUser = $this->app->getAppUser();
?>

<div class="container mt-5">
    <h1>Administrácia používateľov</h1>

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Meno</th>
                        <th>Email</th>
                        <th>Rola</th>
                        <th class="text-end">Akcia</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->getId() ?></td>
                            <td>
                                <strong><?= htmlspecialchars($user->getUsername()) ?></strong>
                                <?php if ($user->getId() === $currentUser->getId()): ?>
                                    <span class="badge bg-primary ms-2">To si ty</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($user->getEmail()) ?></td>
                            <td>
                                <?php if ($user->getRole() === 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">User</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($user->getId() !== $currentUser->getId()): ?>
                                    <a href="<?= $link->url('admin.deleteUser', ['id' => $user->getId()]) ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Naozaj chcete vyhodiť používateľa <?= htmlspecialchars($user->getUsername()) ?>?')">
                                        Zmazať účet
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled>Zmazať</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>