<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="code/css/okBook.css" rel="stylesheet" />

</head>

<body>

    <main class="confirmation">
        <section class="confirmation-card">
            <h2 class="<?= $class_book ?>"><?= $titre_book ?></h2>
            <p><?= $text_book ?></p>
            <a href="index.php?profile" class="btn-retour <?= $class_book ?>">Retour à l'accueil</a>
        </section>
    </main>


</body>


</html>