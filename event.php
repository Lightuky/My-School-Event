<?php
require_once 'includes/header.php';
use Carbon\Carbon;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$event_infos = getEvent($id);
$event_category = getCategory($id);
$event_admin = getUser($event_infos['admin_id']);
$pending_users = getPendingUsers($id);
$event_members = getEventMembers($id);

if ($event_infos['name'] == NULL) {
    $pathError =  "/mse/404.php";
    header('Location: '. $pathError);
}

if (isset($_SESSION['auth_id'])) {
    $event_state= checkEventState($id, $_SESSION['auth_id']);
}

?>

<section>
    <div class="container">
        <h2 class="text-center mt-5"><?php echo $event_infos['name'] ?></h2>
        <div class="row">
            <div class="col">
                <div class="container mt-3">
                    <?php if (isset($_SESSION['auth_id'])) {
                        if ($event_admin['id'] == $_SESSION['auth_id']) { ?>
                            <div class="d-flex">
                                <a href="editevent.php?id=<?php echo $id ?>" class="btn btn-secondary">Editer cet event</a>
                                <?php if ($event_members) { ?>
                                <a href="manageeventusers.php?id=<?php echo $id ?>" class="btn btn-dark ml-4">Gérer les membres</a>
                                <?php }
                                if ($pending_users) { ?>
                                <a href="accepteventusers.php?id=<?php echo $id ?>" class="btn btn-info ml-4"><span class="font-weight-bold"><?php echo count($pending_users) ?></span> Demandes</a>
                                <?php } ?>
                            </div>
                        <?php }
                    } ?>
                </div>
                <div class="mt-3">
                    <?php if (isset($_SESSION['auth_id'])): ?>
                        <?php if ($_SESSION['auth_id'] != $event_admin['id']): ?>
                            <?php if (!$event_state): ?>
                                <?php if ($event_infos['member_limit'] != '0') {
                                    if (($event_infos['member_limit'] - count($event_members)) != '0') { ?>
                                        <a href="assets/eventactions.php?s=0&id=<?php echo $id ?>" class="btn btn-success">Rejoindre l'événement</a>
                                    <?php }
                                    else { ?>
                                        <div class="btn btn-warning">Événement Plein</div>
                                    <?php }
                                } ?>
                            <?php else: ?>
                                <?php if ($event_state['private_pending'] === '0'): ?>
                                    <div class="d-flex mb-2">
                                        <div class="btn bg-success text-white">Event rejoint</div>
                                        <div class="ml-4"><a href="assets/eventactions.php?s=2&id=<?php echo $id ?>" class="btn btn-danger">Quitter l'event</a></div>
                                    </div>
                                <?php else: ?>
                                    <div><a href="assets/eventactions.php?s=2&id=<?php echo $id ?>" class="btn btn-danger">Annuler la demande</a></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($event_infos['member_limit'] != '0') {
                            if (($event_infos['member_limit'] - count($event_members)) != '0') { ?>
                                <a href="login.php" class="btn btn-success">Rejoindre l'événement</a>
                            <?php }
                            else { ?>
                                <div class="btn btn-warning">Événement Plein</div>
                            <?php }
                        } ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="container mt-5">
                <span class="text-muted d-block">Catégorie : <?php echo $event_category['name'] ?></span>
                <span class="text-muted d-block">Date de l'évenement : <?php echo getDateForHumans($event_infos['date']) . " (le " . $event_infos['date'] . ")" ?></span>
                <span class="text-muted d-block">Heure de début : <?php echo $event_infos['time'] ?></span>
                <span class="text-muted d-block">Durée : <?php echo $event_infos['duration'] ?></span>
                <span class="text-muted d-block">Description : <?php echo $event_infos['description'] ?></span>
                <span class="text-muted d-block">Adresse : <?php echo $event_infos['description'] ?></span>
                <span class="text-muted d-block">Responsable de l'événement : <?php echo $event_admin['first_name'] . " " . $event_admin['last_name'] ?></span>
                <?php if ($event_infos['member_limit'] != '0') { ?>
                <span class="text-muted d-block">Membres maximum : <?php echo $event_infos['member_limit'] . " (Encore " . ($event_infos['member_limit'] - count($event_members)) . " places)" ?></span>
                <?php } ?>
                <span class="text-muted d-block">Privé ? : <?php echo $event_infos['is_private'] == 1 ? "Oui" : "Non" ?></span>
            </div>
        </div>
        <?php if ($_SESSION['auth_id'] != $event_admin['id']) { ?>
        <a href="eventmembers.php?id=<?php echo $id ?>" class="btn btn-info mt-4">Voir les membres</a>
        <?php } ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
