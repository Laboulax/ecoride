<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ecoride profil</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet"
        type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet" type="text/css" />

    <link href="code/css/style.css" rel="stylesheet" />
    <script src="script.js" defer></script>
</head>

<body>

    <header class="flux">
        <div class="Search">
            <a href="index.php?home">
                <img class="logomap" src="img/logo_map.png" alt="logo map">
            </a>
            <form action="index.php?search" method="post"> <input class="recherche" name="ville" type="search" placeholder=" Où souhaitez vous aller ?">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button class="bouton" type="submit">Rechercher</button>
            </form>
        </div>
        <div id="logoCo">
            <?= $profil ?>
        </div>
    </header>