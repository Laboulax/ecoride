<main>

    <section class="detail-travel">
        <form method="post" action="" class="form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <div class="input-ctrl">
                <label for="search-d">
                    <img src="img/icons-car.png" alt="">
                </label>
                <input type="text" id="search-d" name="search-d" placeholder="Ville de départ" value="<?= htmlspecialchars($_POST['search-d'] ?? '') ?>">
            </div>

            <div class="input-ctrl">
                <label for="search-a">
                    <img src="img/icons-car.png" alt="">
                </label>
                <input type="text" id="search-a" name="search-a" placeholder="Ville d'arrivée" value="<?= $ville ?>">
            </div>

            <div class="input-ctrl">
                <label for="voyageurs">Nombre de voyageurs : </label>
                <select type="text" name="voyageurs" id="voyageurs" required>
                    <?php
                    $voyageurs = $_POST['voyageurs'] ?? '1';
                    for ($i = 1; $i <= 8; $i++):
                    ?>
                        <option value="<?= $i ?>" <?= ($voyageurs == $i) ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <?php if (isset($_POST['go'])): ?>


                <div class="filtre">
                    <div>
                        <label for="duree">Durée max :</label>
                        <input type="number" step="0.1" min="0" id="duree" name="duree" placeholder="ex : 2.5 pour 2h30" value="<?= htmlspecialchars($_POST['duree'] ?? '') ?>">
                    </div>

                    <div>
                        <label for="prix">Prix max (€) :</label>
                        <input type="text" id="prix" name="prix" placeholder="ex: 50" value="<?= htmlspecialchars($_POST['prix'] ?? '') ?>">
                    </div>

                    <div>
                        <label for="energie">Énergie :</label>
                        <select id="energie" name="energie">
                            <option value="">Tous</option>
                            <option value="essence">Essence</option>
                            <option value="diesel">Diesel</option>
                            <option value="hybride">Hybride</option>
                            <option value="electrique">Electrique</option>
                        </select>
                    </div>

                    <div>
                        <label for="note_min">Note min :</label>
                        <select id="note_min" name="note_min">
                            <option value="">Tous</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bouton_menu"><button class="button_go" type="submit" name="go">Recherche d'Ecoriders</button></div>

        </form>
    </section>

    <?php if (!empty($search_trajet)) : ?>


        <div class="table-responsive">
            <table id="trajet-table">
                <thead style="background-color: rgb(228 240 245); cursor:pointer;">
                    <tr>
                        <th scope="col">Voyagez avec</th>
                        <th scope="col">Note</th>
                        <th scope="col">Date départ</th>
                        <th scope="col">Date arrivée</th>
                        <th scope="col">Départ à</th>
                        <th scope="col">Arrivée</th>
                        <th scope="col">Durée</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Énergie</th>
                        <th scope="col">Place(s) restante(s)</th>
                        <th scope="col">Réserver</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $search_trajet ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

    <div id="search_txt">
        <?= htmlspecialchars($nb_text_traj, ENT_QUOTES, 'UTF-8') ?>
    </div>



</main>