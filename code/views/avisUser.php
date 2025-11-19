<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link href="code/css/avisUser.css" rel="stylesheet" />
</head>

<body>

    <main>


        <h1><?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?></h1>



        <div class="avis-container">
            <?php if (!empty($avisRecu)): ?>
                <?php foreach ($avisRecu as $avis): ?>
                    <div class="avis-card">

                        <div class="avis-photo">
                            <?php if (!empty($avis['passager_photo'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($avis['passager_photo']) ?>"
                                    alt="<?= $avis['passager_prenom'] ?>">
                            <?php else: ?>
                                <img src="img/iconsuser.png" alt="Photo passager">
                            <?php endif; ?>
                        </div>

                        <div class="avis-info">
                            <p><strong><?= htmlspecialchars($avis['passager_prenom'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($avis['passager_nom'], ENT_QUOTES, 'UTF-8') ?></strong> </p>
                            <div class="stars text-warning">
                                <?= $avis['etoiles_html'] ?>
                            </div>
                            <p><?= number_format($avis['a_note'], 0) ?>/5</p>
                            <p><?= $avis['commentaire'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun avis reçu pour le moment.</p>
            <?php endif; ?>
        </div>

    </main>
</body>

</html>