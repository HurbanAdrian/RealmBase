<?php
/**
 *
 *
 * @var array $errors
 */
/**
 *
 *
 * @var string|null $title
 */
/**
 *
 *
 * @var string|null $content
 */
/**
 *
 *
 * @var array $categories
 */
/**
 *
 *
 * @var array $users
 */
/**
 *
 *
 * @var int|null $category_id
 */
/**
 *
 *
 * @var int|null $user_id
 */
?>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e) : ?>
                <li><?php echo htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<h1>Pridať príspevok</h1>

<form method="post">
    <div class="mb-3">
        <label>Nadpis:</label>
        <input name="title" class="form-control" value="<?php echo htmlspecialchars($title ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label>Obsah:</label>
        <textarea name="content" class="form-control" required><?php echo htmlspecialchars($content ?? '') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Kategória:</label>
        <select name="category_id" class="form-control">
            <?php foreach ($categories as $cat) : ?>
                <option value="<?php echo $cat->getId() ?>"
                        <?php echo ($category_id == $cat->getId()) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($cat->getName()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Používateľ:</label>
        <select name="user_id" class="form-control">
            <?php foreach ($users as $u) : ?>
                <option value="<?php echo $u->getId() ?>"
                        <?php echo ($user_id == $u->getId()) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($u->getName()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-success">Uložiť</button>
</form>
