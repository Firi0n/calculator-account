<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $description ?>">
    <meta name="author" content="Pasquale Rossini">
    <title>Calculator</title>
    <link rel="stylesheet" href="templates/template.css?<?= time() ?>">
</head>
<body>
    <header>
        <h1>Calculator: <?= $title ?></h1>
    </header>
    <main>
        <?= $content ?>
    </main>
    <footer>
        <label>Powered by Pasquale Rossini</label>
    </footer>
</body>
</html>
