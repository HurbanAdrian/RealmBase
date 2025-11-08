<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Bez názvu') ?></title>
</head>
<body>
<h1><?= htmlspecialchars($title) ?></h1>
<p>Táto stránka je generovaná cez HomeController::about().</p>
</body>
</html>