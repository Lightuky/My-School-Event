<?php
require_once 'includes/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = getUser($id);
$schools = getSchools();
$cities = getCities();

if (isset($_SESSION['auth_id'])) {
    if ($_SESSION['auth_id'] != $id) {
        $pathError =  "/mse/profile.php?id=$id";
        header('Location: '. $pathError);
    }
}
else {
    $pathError =  "/mse/profile.php?id=$id";
    header('Location: '. $pathError);
}

?>
<section>
    <div class="container">
        <div>
            <h2>Éditer mes infos</h2>
            <form method="post" action="assets/updateuser.php?id=<?php echo $id ?>">
                <div class="form-group">
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $user['first_name'] ?>">
                </div>
                <div class="form-group">
                    <label for="last_name">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $user['last_name'] ?>">
                </div>
                <div class="form-group">
                    <label for="phone_number">N° de téléphone</label>
                    <input type="tel" id="phone_number" name="phone_number" class="form-control" maxlength="10" minlength="10"
                           value="<?php echo !$user['phone_number'] ? '' : $user['phone_number']; ?>"pattern="[0-9]{10}">
                </div>
                <div class="form-group">
                    <label for="birthday">Date de naissance</label>
                    <input type="date" name="birthday" id="birthday" class="form-control" value="<?php echo !$user['birthday'] ? '' : $user['birthday']; ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Genre</label>
                    <select id="gender" name="gender" class="form-control">
                        <?php for ($i = 1; $i <=3; $i++) { ?>
                            <option value="<?php echo $i ?>" <?php if ($user['gender'] == $i) echo 'selected' ?>>
                                <?php echo $i != 1 ? ($i != 2 ? "Autre" : "Femme") : "Homme" ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="school_id">École</label>
                    <select id="school_id" name="school_id" class="form-control">
                        <?php foreach ($schools as $school) { ?>
                            <option value="<?php echo $school['id'] ?>"
                                <?php if ($user['school_id'] == $school['id']) echo 'selected' ?>><?php echo $school['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="school_year">Année</label>
                    <select id="school_year" name="school_year" class="form-control">
                        <?php for ($i = 1; $i <=5; $i++) { ?>
                            <option value="<?php echo $i ?>" <?php if ($user['school_year'] == $i) echo 'selected' ?>><?php echo "Année N°". $i ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">Ville</label>
                    <select id="city" name="city" class="form-control">
                        <?php foreach ($cities as $city) { ?>
                            <option value="<?php echo $city['id'] ?>"
                                <?php if ($user['city_id'] == $city['id']) echo 'selected' ?>><?php echo $city['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <?php if (isset($_SESSION['auth_id'])) {
                    if ($id == $_SESSION['auth_id']) { ?>
                        <button type="submit" class="btn btn-success">Sauvegarder</button>
                    <?php }
                } ?>
            </form>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>


