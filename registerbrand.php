<?php
require_once 'includes/header.php';

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

$schools = getSchools();
$cities = getCities();

?>
<section>
    <div class="container">
        <div class="mt-5" style="padding-top: 60px!important;">
            <h2>Inscription en tant qu'entreprise</h2>
            <form method="post" action="assets/register.php?s=2">
                <div class="form-group">
                    <label for="first_name">Prénom (de l'adminitrateur du compte)</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                    <small class="invalid-feedback"><?php echo isset($errors['last_name']) && isset($errors['last_name']['error']) ?></small>
                </div>
                <div class="form-group">
                    <label for="last_name">Nom (de l'adminitrateur du compte)</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="email">Email du compte</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="example@example.com" required>
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="contact_email">Email de contact de la marque </label>
                    <input type="email" name="contact_email" id="contact_email" class="form-control" placeholder="contact@brand.fr" required>
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="brand_name">Nom de la société</label>
                    <input type="text" name="brand_name" id="brand_name" class="form-control" required>
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="website_url">Adresse du site Web</label>
                    <input type="text" name="website_url" id="website_url" class="form-control" placeholder="www.example.com" required>
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="city">Ville d'activité</label>
                    <select id="city" name="city" class="form-control" required>
                        <?php foreach ($cities as $city) { ?>
                            <option value="<?php echo $city['id'] ?>"><?php echo $city['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="password-confirm">Confirmer le mot de passe</label>
                    <input type="password" name="password-confirm" id="password-confirm" class="form-control" required>
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
