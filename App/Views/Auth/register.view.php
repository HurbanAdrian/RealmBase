<?php
/** @var array $errors */
/** @var array $formData */
/** @var \Framework\Core\App $this */
$link = $this->app->getLinkGenerator();

// Implementované s asistenciou AI
?>

<div class="col-md-4 mx-auto mt-5">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Registrácia</h3>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= $link->url('auth.register') ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Používateľské meno</label>
                    <input type="text" name="username" id="username" class="form-control"
                           value="<?= htmlspecialchars($formData['username'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="<?= htmlspecialchars($formData['email'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Heslo</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password_verify" class="form-label">Zopakujte heslo</label>
                    <input type="password" name="password_verify" id="password_verify" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Zaregistrovať sa</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <p>Už máš účet? <a href="<?= $link->url('auth.login') ?>">Prihlás sa tu</a></p>
            </div>
        </div>
    </div>
</div>