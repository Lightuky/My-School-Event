<?php

require_once 'includes/header.php';
use Carbon\Carbon;
$errored = isset($_GET['errored']) ? $_GET['errored'] : null;

if (empty($errored)):
    $_SESSION['fields'] = [];
endif;

?>
<section>
    <div class="container">
        <div class="mt-5" style="padding-top: 60px!important;">
            <h2>Connectez-vous</h2>
            <form method="post" action="assets/login.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" placeholder="example@example.com" class="form-control"
                           value="<?php echo ($errored ? ((isset($_SESSION['fields']["email"])) ? $_SESSION['fields']["email"]['old'] : NULL) : NULL) ?>" required>
                    <div class="errors form-text text-muted" style="margin-bottom: 10px;">
                        <?php echo (isset($_SESSION['fields']["email"])) ? $_SESSION['fields']["email"]['error'] : NULL ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <div class="errors form-text text-muted" style="margin-bottom: 10px;">
                        <?php echo (isset($_SESSION['fields']["password"])) ? $_SESSION['fields']["password"]['error'] : NULL ?>
                    </div>
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
