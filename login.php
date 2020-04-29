<?php

require_once 'includes/header.php';
use Carbon\Carbon;

$errors_e = $errors_pw = [];

?>
<section>
    <div class="container">
        <div>
            <h2>Connectez-vous</h2>
            <form method="post" action="assets/login.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" placeholder="example@example.com" class="form-control">
                    <div class="errors form-text text-muted" style="margin-bottom: 10px;"><?php echo implode('<br>', $errors_e); ?></div>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <div class="errors form-text text-muted" style="margin-bottom: 10px;"><?php echo implode('<br>', $errors_pw); ?></div>
                </div>
                <div>
                    <div style="display: flex">
                        <button type="submit" class="btn btn-success">Se Connecter</button>
                        <a href="register.php" class="btn btn-info ml-5">Créer un compte</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
