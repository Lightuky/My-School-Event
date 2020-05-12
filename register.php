<?php
require_once 'includes/header.php';

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

$schools = getSchools();
$cities = getCities();

?>
<section>
    <div class="container">
        <div class="mt-5" style="padding-top: 60px!important;">
            <h2>Inscription</h2>
            <form method="post" action="assets/register.php">
                <div class="form-group">
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="form-control">
                    <small class="invalid-feedback"><?php echo isset($errors['last_name']) && isset($errors['last_name']['error']) ?></small>
                </div>
                <div class="form-group">
                    <label for="last_name">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="form-control">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="example@example.com">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="gender">Genre</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="1" selected>Homme</option>
                        <option value="2">Femme</option>
                        <option value="3">Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="school_id">École</label>
                    <select id="school_id" name="school_id" class="form-control">
                        <?php foreach ($schools as $school) { ?>
                            <option value="<?php echo $school['id'] ?>"><?php echo $school['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="school_year">Année</label>
                    <select id="school_year" name="school_year" class="form-control">
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i ?>">Année N°<?php echo $i ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">Ville</label>
                    <select id="city" name="city" class="form-control">
                        <?php foreach ($cities as $city) { ?>
                            <option value="<?php echo $city['id'] ?>"><?php echo $city['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="password-confirm">Confirmer le mot de passe</label>
                    <input type="password" name="password-confirm" id="password-confirm" class="form-control">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div>
                    <div style="display: flex">
                        <a href="login.php" class="btn btn-light" style="margin-right: 35px;">Retour</a>
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php $_SESSION['errors'] = []; ?>

<?php require_once 'includes/footer.php'; ?>
