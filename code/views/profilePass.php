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
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

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
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#historique">Historique des trajets</a></li>


            </ul>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <!-- A propos-->
        <section class="resume-section" id="apropos">
            <div class="resume-section-content">
                <h1 class="mb-0">
                    <?= $nom ?>
                    <span class="text-primary"><?= $prenom ?></span>
                </h1>
                <div class="subheading mb-5">
                    <?= $adress ?>
                    <a href="mailto:name@email.com"><?= $email ?></a>
                </div>
                <form action="index.php?profile#apropos" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <label>Choisissez une image:</label>
                    <input type="file" name="image" accept="image/*">
                    <button type="submit" name="profilPic" id="profilPic">Enregistrer</button>

                </form>
            </div>
        </section>
        <hr class="m-0" />
        <!-- Ecoride à venir-->
        <section class="resume-section" id="ecoride">
            <div class="resume-section-content">
                <h2 class="mb-5">Ecorides à venir</h2>

                <div class="prof_d">

                    <div>

                        <?php if (!empty($trajet_futurPass)) : ?>
                            <table>

                                <thead style="background-color: rgb(228 240 245);">
                                    <tr>

                                        <th scope="col">Ville départ</th>
                                        <th scope="col">Ville arrivée</th>
                                        <th scope="col">Date départ</th>
                                        <th scope="col">Heure départ</th>
                                        <th scope="col">Heure d'arrivée</th>
                                        <th scope="col">durée</th>
                                        <th scope="col">Nombre de voyageur(s)</th>
                                        <th scope="col">Prix /place</th>
                                        <th scope="col">Carburant</th>
                                        <th scope="col"></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?= $trajet_futurPass ?>
                                </tbody>
                            </table>

                        <?php else : ?>
                            <p>
                                Vous n'avez ucun trajet de prévu pour le moment
                            </p>
                        <?php endif; ?>

                    </div>




                    <p>Cherchez un nouveau trajet :</p><button class="btn-ajouter" onclick="window.location.href='index.php?search'">
                        Chercher
                    </button>
                </div>
            </div>

    </div>
    </section>
    <hr class="m-0" />
    <!-- Historique des trajets-->
    <section class="resume-section" id="historique">
        <div class="resume-section-content">
            <h2 class="mb-5">Historique des trajets</h2>
            <div class="prof_d">

                <?php if (!empty($trajet_fait)) : ?>
                    <table>
                        <thead style="background-color: rgb(228 240 245);">
                            <tr>
                                <th scope="col">Role</th>
                                <th scope="col">Ville départ</th>
                                <th scope="col">Ville arrivée</th>
                                <th scope="col">Date départ</th>
                                <th scope="col">Heure départ</th>
                                <th scope="col">Heure d'arrivée</th>
                                <th scope="col">durée</th>
                                <th scope="col">Prix</th>
                                <th scope="col"></th>

                            </tr>
                        </thead>
                        <tbody>

                            <?= $trajet_fait ?>


                            <div id="avisModal" class="modal" style="display:none;">
                                <div class="modal-content">
                                    <span class="close" id="avisModalClose">&times;</span>
                                    <h3 id="avisModalTitle">Laisser un avis</h3>

                                    <form id="avisForm" action="index.php?avis" method="post" onsubmit="return confirm('Voulez-vous valider ce commentaire ?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                                        <input type="hidden" name="covoiturage_id" id="covoiturage_id_input" value="">
                                        <textarea name="commentaire" id="commentaire_input" placeholder="Donnez votre avis..." required></textarea>
                                        <br>

                                        <label>Note :</label>
                                        <div class="stars">
                                            <i class="lar la-star" data-value="1"></i><i class="lar la-star" data-value="2"></i><i class="lar la-star" data-value="3"></i><i class="lar la-star" data-value="4"></i><i class="lar la-star" data-value="5"></i>
                                        </div>
                                        <input type="hidden" name="note" id="note" value="0">
                                        <br>
                                        <button type="submit" class="avis_submit">Envoyer</button>
                                    </form>
                                </div>
                            </div>





                        </tbody>
                    </table>
                <?php else : ?>
                    <p>Vous n'avez encore jamais efectué de trajet</p>
                <?php endif; ?>

            </div>
        </div>
    </section>
    <hr class="m-0" />

    <!-- Ajout véhicule-->


    <section id="change_role">

        <p>Vous souhaitez enregistrer un véhicule et devenir conducteur Ecoride ?</p>
        <button class="openVehiculeModalBtn" onclick="openVehiculeModal()">Enregistrer un véhicule</button>

        <div id="vehiculeModal" class="modal">
            <div class="modal-content">
                <span id="vehiculeModalClose" class="close">&times;</span>
                <h2 id="vehiculeModalTitle">Enregistrer un véhicule</h2>

                <section>
                    <div>
                        <form id="vehiculeForm" method="post" action="index.php?profile#voiture">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                            <label for="modele">Modèle :</label>
                            <input type="text" id="modele" name="modele" required>

                            <label for="immatriculation">Immatriculation :</label><br>
                            <input type="text" id="immatriculation" name="immat" required>

                            <label for="places">Date de première immatriculation :</label><br>
                            <input type="date" id="date" name="date_immat" required>

                            <label for="nrj">Énergie :</label>
                            <select name="nrj" id="nrj" required>
                                <option value="" disabled selected>Sélectionnez le type de carburant</option>
                                <option value="Electrique">Electrique</option>
                                <option value="Hybride">Hybride</option>
                                <option value="Essence">Essence</option>
                                <option value="Diesel">Diesel</option>
                            </select>



                            <label for="marque">Marque :</label>
                            <select type="text" name="marque" id="marque" required>
                                <option value="0" selected>Veuillez choisir une marque</option>
                                <?= $list_marque ?>
                            </select>


                            <button type="submit" name="addCar" class="btn-submit">Enregistrer</button>
                        </form>
                    </div>
                </section>
            </div>
        </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>