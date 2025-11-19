<main id="userRegister">


    <form method="post" action="index.php?register">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <h1>Bienvenue</h1>

        <p class="choose-email">Rejoins la communauté :</p>

        <div class="inputs">

            <input type="text" id="prenom" name="prenom" placeholder="Prenom" required>
            <input type="text" id="nom" name="nom" placeholder="Nom" required>
            <label for="date_naissance">Date de naissance : </label>
            <input type="date" id="date_naissance" name="date_naissance" placeholder="Date de naissance" required>
            <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" required>
            <input type="text" id="adress" name="adress" placeholder="Adresse" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="pass" name="pass" placeholder="Mot de passe" required>
            <p>Selectionnez votre rôle :</p>
            <input type="radio" id="chauffeur" name="role" value="chauffeur">
            <label for="chauffeur">Chauffeur</label><br>
            <input type="radio" id="passager" name="role" value="passager">
            <label for="passager">passager</label><br>

        </div>
        <button type="submit" name="ok">Créer un compte</button>
    </form>

</main>