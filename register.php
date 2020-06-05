<?php
require_once 'includes/header.php';

$errored = isset($_GET['errored']) ? $_GET['errored'] : null;

if (empty($errored)):
    $_SESSION['fields'] = [];
endif;

$schools = getSchools();
$cities = getCities();

?>
<section>
    <div class="container">
        <div class="mt-5" style="padding-top: 60px!important;">
            <h2>Inscription en tant qu'étudiant</h2>
            <form method="post" action="assets/register.php?s=1">
                <div class="form-group">
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"
                           value="<?php echo ($errored ? ((isset($_SESSION['fields']["first_name"])) ? $_SESSION['fields']["first_name"]['old'] : NULL) : NULL) ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"
                           value="<?php echo ($errored ? ((isset($_SESSION['fields']["last_name"])) ? $_SESSION['fields']["last_name"]['old'] : NULL) : NULL) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="example@example.com"
                           value="<?php echo ($errored ? ((isset($_SESSION['fields']["email"])) ? $_SESSION['fields']["email"]['old'] : NULL) : NULL) ?>" required>
                    <div class="errors form-text text-danger small" style="margin-bottom: 10px;">
                        <?php echo (isset($_SESSION['fields']["email"])) ? $_SESSION['fields']["email"]['error'] : NULL ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="gender">Genre</label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="1" <?php echo ($errored ? ($_SESSION['fields']["gender"] == "1" ? 'selected' : NULL ) : 'selected') ?>>Homme</option>
                        <option value="2" <?php echo ($errored ? ($_SESSION['fields']["gender"] == "2" ? 'selected' : NULL ) : NULL) ?>>Femme</option>
                        <option value="3" <?php echo ($errored ? ($_SESSION['fields']["gender"] == "3" ? 'selected' : NULL ) : NULL) ?>>Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="school_id">École</label>
                    <select id="school_id" name="school_id" class="form-control" required>
                        <?php foreach ($schools as $school) { ?>
                            <option value="<?php echo $school['id'] ?>" <?php echo ($errored ? ($_SESSION['fields']["school_id"] == $school['id'] ? 'selected' : NULL ) : NULL) ?>>
                                <?php echo $school['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="school_year">Année</label>
                    <select id="school_year" name="school_year" class="form-control" required>
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i ?>" <?php echo ($errored ? ($_SESSION['fields']["school_year"] == $i ? 'selected' : NULL ) : NULL) ?>>
                                Année N°<?php echo $i ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">Ville</label>
                    <select id="city" name="city" class="form-control" required>
                        <?php foreach ($cities as $city) { ?>
                            <option value="<?php echo $city['id'] ?>" <?php echo ($errored ? ($_SESSION['fields']["city"] == $city['id'] ? 'selected' : NULL ) : NULL) ?>>
                                <?php echo $city['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password-confirm">Confirmer le mot de passe</label>
                    <input type="password" name="password-confirm" id="password-confirm" class="form-control" required>
                    <div class="errors form-text text-danger small" style="margin-bottom: 10px;">
                        <?php echo (isset($_SESSION['fields']["password"])) ? $_SESSION['fields']["password"]['error'] : NULL ?>
                    </div>
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
