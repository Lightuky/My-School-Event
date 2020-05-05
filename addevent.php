<?php
require_once 'includes/header.php';

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

$categories = getCategories();

?>
<section>
    <div class="container">
        <div>
            <h2 class="text-center my-5">Créer un événement</h2>
            <form method="post" action="assets/addevent.php">
                <div class="form-group">
                    <label for="name">Nom de l'événement</label>
                    <input type="text" name="name" id="name" class="form-control">
                    <small class="invalid-feedback"><?php echo isset($errors['last_name']) && isset($errors['last_name']['error']) ?></small>
                </div>
                <div class="form-group">
                    <label for="category">Catégories d'événements</label>
                    <select id="category" name="category" class="form-control">
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="time">Heure de début</label>
                    <input type="time" name="time" id="time" class="form-control">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="duration">Durée de l'événement</label>
                    <input type="time" name="duration" id="duration" class="form-control">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea rows="4" name="description" id="description" class="form-control"></textarea>
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="member_limit">Nombre de membres (0 si illimité)</label>
                    <input type="number" id="member_limit" name="member_limit" min="0" class="form-control">
                    <small class="invalid-feedback"><?php echo isset($errors) ?></small>
                </div>
                <div class="form-group">
                    <label for="address">Adressse</label>
                    <input type="text" name="street_number" id="address" class="form-control" placeholder="4">
                    <input type="text" name="address_line1" id="address" class="form-control" placeholder="Rue des Oliviers">
                    <input type="text" name="address_line2" id="address" class="form-control" placeholder="Le Bois Bourgerel">
                    <input type="text" name="city" id="address" class="form-control" placeholder="Nantes">
                    <input type="text" name="zip_code" id="address" class="form-control" placeholder="44000">
                    <input type="text" name="country" id="address" class="form-control" placeholder="France">
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
                        <input class="form-check-input" type="radio" name="is_private" id="is_private" value="0" checked>
                        <label class="form-check-label" for="is_private">Non</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_private" id="is_private" value="1">
                        <label class="form-check-label" for="is_private">Oui</label>
                    </div>
                </div>
                <div>
                    <div style="display: flex">
                        <a href="index.php" class="btn btn-light" style="margin-right: 35px;">Retour</a>
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!--
<script type="text/javascript"  src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDSbcqgihtzSmD0RQQufPh5j0z5uiOmfO0"></script>
<script type="text/javascript" src="autocomplete.js"></script>
-->

<?php $_SESSION['errors'] = []; ?>

<?php require_once 'includes/footer.php'; ?>