<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ecoride profil</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet"
        type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet" type="text/css" />

    <link href="code/css/profil.css" rel="stylesheet" />
</head>

<body id="page-top">




    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">
            <span class="d-block d-lg-none">Bienvenu</span>
            <span class="d-none d-lg-block"><img class="img-fluid img-profile rounded-circle mx-auto mb-2"
                    src="<?= $photo ?>" alt="IconUser" /></span>
        </a>



        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span
                class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav">

                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#apropos">A Propos</a></li>
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#ecoride">Ecorides à venir</a></li>
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#marque">Marque(s)</a></li>

            </ul>
        </div>
    </nav>
    <!-- Page Content-->
    <div class="container-fluid p-0">


        <!-- A propos-->
        <section class="resume-section" id="apropos">
            <div class="resume-section-content">
                <h1 class="mb-0">
                    <?= htmlspecialchars($nom) ?>
                    <span class="text-primary"><?= htmlspecialchars($prenom) ?></span>
                </h1>
                <div class="subheading mb-5">
                    <?= htmlspecialchars($adress) ?>
                    <a href="mailto:name@email.com"><?= htmlspecialchars($email) ?>
                    </a>
                </div>
                <form action="index.php?profile#apropos" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <label>Choisissez une image :</label>
                    <input type="file" name="image" accept="image/*">
                    <button type="submit" name="profilPic">Enregistrer</button>

                </form>
            </div>
        </section>
        <hr class="m-0" />
        <!-- EAvis-->
        <section class="resume-section" id="ecoride">
            <div class="resume-section-content">
                <h2>Avis en attente</h2>
                <div class="prof_d" id="c_top">


                    <?php if (!empty($statutAvis)) : ?>

                        <table>
                            <thead style="background-color: rgb(228 240 245);">
                                <tr>
                                    <th scope="col">Passager</th>
                                    <th scope="col">Commentaire</th>
                                    <th scope="col">Note</th>
                                    <th scope="col">Chauffeur</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?= $statutAvis ?>
                            </tbody>
                        </table>

                    <?php else : ?>
                        <p>Aucun avis à valider pour l'instant</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <hr class="m-0" />




        <section class="resume-section" id="marque">
            <div class="resume-section-content">
                <h2 class="mb-5">Marque</h2>

                <div class="prof_d">


                    <div class="addCar">

                        <h3>Ajoutez une marque de voiture pour les utilisateurs :</h3>

                        <form action="index.php?profile#marque" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                            <div>
                                <label for="marque">Marque :</label>

                                <input type="text" placeholder="Nom de la nouvelle marque" name="marque">

                            </div></br>

                            <div>
                                <label>Choisissez une image :</label>
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <br><br>
                            <div>
                                <input type="submit" name="addMarque" value="Valider">
                            </div>
                        </form>
                    </div>


                    <table>
                        <h3>Marque(s) enregistrée(s) :</h3>
                        <thead style="background-color: rgb(228 240 245);">
                            <tr>
                                <th scope="col">Marque</th>
                                <th scope="col">Logo</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?= $admin_marque ?>
                        </tbody>
                    </table>

                </div>
            </div>
    </div>
    </section>




    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>