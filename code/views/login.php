<main id="userLogin">

    <?php
    if (isset($_SESSION['flash_message'])) {
        echo "<script>alert('" . addslashes($_SESSION['flash_message']) . "');</script>";
        unset($_SESSION['flash_message']);
    }
    ?>

    <form method="post" action="index.php?login">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <h1>Se connecter</h1>
        <div class="social-media">
            <p><i class="fab fa-google"></i></p>
            <p><i class="fab fa-youtube"></i></p>
            <p><i class="fab fa-facebook-f"></i></p>
            <p><i class="fab fa-twitter"></i></p>
        </div>
        <p class="choose-email">Ou utiliser un compte existant :</p>

        <div class="inputs">
            <input type="email" id="email" name="email" placeholder="Email" required />
            <input type="password" id="pass" name="pass" placeholder="Mot de passe" required>


            <p class="inscription">Pas encore de compte ? <a href="index.php?register">Devenir EcoRider</a></p>
            <button type="submit" name="ok">Se connecter</button>

        </div>

        <div style='color:red'><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?> </div>




    </form>
</main>