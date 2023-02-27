<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
        $path = "http://localhost/projects/calculator-account/"; 
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $description ?>">
    <meta name="author" content="Pasquale Rossini">
    <title>Calculator</title>
    <link rel="stylesheet" href="<?= $path; ?>templates/template.css?<?= time() ?>">
    <link rel="stylesheet" href="<?= $path; ?>templates/header/header.css?<?= time() ?>">
    <link rel="stylesheet" href="<?= $path; ?>templates/footer/footer.css?<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?<?= time() ?>">
</head>

<body>
    <header>
        <?php require(__DIR__."/header/header.php"); ?>
    </header>
    <main>
        <?= $content ?>
    </main>
    <footer>
        <?php require(__DIR__."/footer/footer.php"); ?>
    </footer>
</body>

</html>
