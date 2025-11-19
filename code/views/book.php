<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="code/css/book.css" rel="stylesheet" />

</head>

<body>


    <main class="recapitulatif">
        <section class="recap-card">

            <div class="list_book">
                <h2>Récapitulatif du trajet :</h2>
                <table class="table-recap">

                    <tbody>
                        <tr>
                            <td><strong>Conducteur :</strong></td>
                            <td><?= htmlspecialchars($trajet['pseudo']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Départ :</strong></td>
                            <td><?= htmlspecialchars($trajet['depart']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Arrivée :</strong></td>
                            <td><?= htmlspecialchars($trajet['arrivee']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Date de départ :</strong></td>
                            <td><?= htmlspecialchars($trajet['date_depart']) ?> à <?= htmlspecialchars($trajet['heure_depart']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Date d'arrivée :</strong></td>
                            <td><?= htmlspecialchars($trajet['date_arrivee']) ?> à <?= htmlspecialchars($trajet['heure_arrivee']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Prix par personne :</strong></td>
                            <td>
                                <span id="prixTrajet" data-prix="<?= htmlspecialchars($trajet['prix']) ?>">
                                    <?= number_format($trajet['prix'], 2) ?> €
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Places disponibles :</strong></td>
                            <td><?= htmlspecialchars($trajet['places']) ?></td>
                        </tr>
                    </tbody>
                </table>


            </div>

            <form method="post" action="index.php?okBook">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="id" value="<?= $trajet['id'] ?>">
                <label for="nbPersonnes">Nombre de personnes :</label>
                <input type="number" id="nbPersonnes" name="nbPersonnes" value="<?= $_POST['nb_voyageurs'] ?? '1' ?>" min="1" max="<?= $trajet['places'] ?>">

                <p><strong>Total à payer :</strong> <span id="total"><?= number_format($trajet['prix'] * ($_POST['nb_voyageurs'] ?? '1'), 2) ?></span> €</p>

                <button type="submit" class="btn-confirmer">Confirmer la réservation</button>
            </form>
        </section>
    </main>

    <script src="/studi-ecf-main/js/scriptbook.js"></script>

</body>


</html>