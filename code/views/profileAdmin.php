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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#stats">Statistiques</a></li>
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#newEmploye">Nouvel employé</a></li>
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
                    <?= $nom ?>
                    <span class="text-primary"><?= $prenom ?></span>
                </h1>
                <div class="subheading mb-5">
                    <?= $adress ?>
                    <a href="mailto:name@email.com"><?= $email ?></a>
                </div>
                <form action="index.php?profile#apropos" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <label>Choisissez une image :</label>
                    <input type="file" name="image" accept="image/*">
                    <button type="submit" name="profilPic">Enregistrer</button>

                </form>
            </div>
        </section>
        <hr class="m-0" />
        <!-- stats-->

        <section class="resume-section" id="stats">
            <div class="resume-section-content">
                <h2>Statistiques Ecoride</h2>



                </head>

                <body>

                    <div class="box">

                        <canvas id="statsChart"></canvas>
                    </div>

                    <script>
                        const dates = <?= $datesJS ?>;
                        const trajJour = <?= $trajJS ?>;
                        const creditJour = <?= $creditJS ?>;

                        const ctx = document.getElementById("statsChart").getContext("2d");

                        new Chart(ctx, {
                            type: "line",
                            data: {
                                labels: dates,
                                datasets: [{
                                        label: "Covoiturages / jour",
                                        data: trajJour,
                                        borderColor: "#007bff",
                                        backgroundColor: "rgba(0,123,255,0.2)",
                                        borderWidth: 2,
                                        tension: 0.3
                                    },
                                    {
                                        label: "Crédits / jour",
                                        data: creditJour,
                                        borderColor: "#28a745",
                                        backgroundColor: "rgba(40,167,69,0.2)",
                                        borderWidth: 2,
                                        tension: 0.3
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: "Valeurs"
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: "Dates"
                                        }
                                    }
                                }
                            }
                        });
                    </script>
            </div>
        </section>



        <!-- nvo employé-->


        <section class="resume-section" id="newEmploye">
            <div class="resume-section-content">
                <h2>Créer un nouveau profil employé</h2>



                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
                    Créer un compte employé
                </button>

                <!-- Modal Bootstrap complète -->
                <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="registerModalLabel">Infos du nouvel employé</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>

                            <form method="post" action="index.php?profile" id="createEmpForm">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <input type="text" class="form-control mb-2" id="prenom" name="prenom" placeholder="Prénom" required>
                                        <input type="text" class="form-control mb-2" id="nom" name="nom" placeholder="Nom" required>
                                        <label for="date_naissance" class="form-label">Date de naissance :</label>
                                        <input type="date" class="form-control mb-2" id="date_naissance" name="date_naissance" required>
                                        <input type="text" class="form-control mb-2" id="pseudo" name="pseudo" placeholder="Pseudo" required>
                                        <input type="email" class="form-control mb-2" id="email" name="email" placeholder="Email" required>
                                        <input type="password" class="form-control mb-3" id="pass" name="pass" placeholder="Mot de passe" required>
                                    </div>
                                </div>

                                <div class="modal-footer">

                                    <button type="submit" name="createEmp" class="btn btn-success">Créer le compte</button>
                                </div>
                            </form>

                        </div>
                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>