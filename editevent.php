<?php
require_once 'includes/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$event_infos = getEvent($id);
$event_admin = getUser($event_infos['admin_id']);
$event_address = getEventAddress($id);
$categories = getCategories();

if (isset($_SESSION['auth_id'])) {
    if ($event_admin['id'] !== $_SESSION['auth_id']) {
        $pathError =  "/mse/event.php?id=$id";
        header('Location: '. $pathError);
    }
}

?>
<section>
    <div class="container">
        <div>
            <h2 class="text-center my-5">Éditer l'event : <?php echo $event_infos['name'] ?></h2>
            <form method="post" action="assets/updateevent.php?id=<?php echo $id ?>">
                <div class="form-group">
                    <label for="name">Nom de l'événement</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $event_infos['name'] ?>">
                    <small class="invalid-feedback"><?php echo isset($errors['last_name']) && isset($errors['last_name']['error']) ?></small>
                </div>
                <div class="form-group">
                    <label for="category">Catégories d'événements</label>
                    <select id="category" name="category" class="form-control">
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?php echo $category['id'] ?>"
                                <?php if ($event_infos['category'] == $category['id']) echo 'selected' ?>><?php echo $category['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control" value="<?php echo $event_infos['date'] ?>">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="time">Heure de début</label>
                    <input type="time" name="time" id="time" class="form-control" value="<?php echo $event_infos['time'] ?>">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="duration">Durée de l'événement</label>
                    <input type="time" name="duration" id="duration" class="form-control" value="<?php echo $event_infos['duration'] ?>">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea rows="4" name="description" id="description" class="form-control"><?php echo $event_infos['description'] ?></textarea>
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="member_limit">Nombre de membres (0 si illimité)</label>
                    <input type="number" id="member_limit" name="member_limit" min="0" class="form-control" value="<?php echo $event_infos['member_limit'] ?>">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="address">Adressse</label>
                    <input type="text" name="street_number" id="address" class="form-control" placeholder="4" value="<?php echo $event_address['street_number'] ?>">
                    <input type="text" name="address_line1" id="address" class="form-control" placeholder="Rue des Oliviers" value="<?php echo $event_address['address_line1'] ?>">
                    <input type="text" name="address_line2" id="address" class="form-control" placeholder="Le Bois Bourgerel" value="<?php echo $event_address['address_line2'] ?>">
                    <input type="text" name="city" id="address" class="form-control" placeholder="Nantes" value="<?php echo $event_address['city'] ?>">
                    <input type="text" name="zip_code" id="address" class="form-control" placeholder="44000" value="<?php echo $event_address['zip_code'] ?>">
                    <input type="text" name="country" id="address" class="form-control" placeholder="France" value="<?php echo $event_address['country'] ?>">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <!--
                <div class="form-group">
                    <label for="address">Adressse</label>
                    <input id="user_input_autocomplete_address" placeholder="4 rue des oliviers..." name="address" class="form-control">
                    <label>street_number</label>
                    <input id="street_number" name="street_number" disabled class="form-control">
                    <label>route</label>
                    <input id="route" name="route" disabled class="form-control">
                    <label>locality</label>
                    <input id="locality" name="locality" disabled class="form-control">
                    <label>country</label>
                    <input id="country" name="country" disabled class="form-control">
                </div>
                -->
                <div class="form-group">
                    <label for="is_private" class="d-block">Événement privé (amis uniquement)</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_private" id="is_private" value="0" <?php if ($event_infos['is_private'] == "0") echo 'checked' ?>>
                        <label class="form-check-label" for="is_private">Non</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_private" id="is_private" value="1" <?php if ($event_infos['is_private'] == "1") echo 'checked' ?>>
                        <label class="form-check-label" for="is_private">Oui</label>
                    </div>
                </div>
                <div>
                    <div style="display: flex">
                        <a href="event.php?id=<?php echo $id ?>" class="btn btn-light" style="margin-right: 35px;">Retour</a>
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>


