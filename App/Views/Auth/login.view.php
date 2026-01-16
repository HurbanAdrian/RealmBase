<?php

/**
 * @var string|null $message
 */
/**
 * @var \Framework\Support\LinkGenerator $link
 */
/**
 * @var \Framework\Support\View $view
 */

$view->setLayout('auth');
?>

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Prihlásenie</h5>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-danger text-center" role="alert">
                            ⚠️ <?= $message ?>
                        </div>
                    <?php endif; ?>
                    <form class="form-signin" method="post" action="<?php echo $link->url("login") ?>">
                        <div class="form-label-group mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input name="username" type="text" id="username" class="form-control" placeholder="Username"
                                   required autofocus>
                        </div>

                        <div class="form-label-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input name="password" type="password" id="password" class="form-control"
                                   placeholder="Password" required>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit" name="submit">Log in
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p>Ešte nemáš účet? <a href="<?= $link->url('auth.register') ?>">Zaregistruj sa</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>