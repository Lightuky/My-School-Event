<?php
require_once 'includes/header.php';
use Carbon\Carbon;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$event_infos = getEvent($id);
$event_admin = getUser($event_infos['admin_id']);
$pending_users = getPendingUsers($id);

if ($event_infos['name'] == NULL) {
    $pathError =  "/mse/404.php";
    header('Location: '. $pathError);
}

if (isset($_SESSION['auth_id'])) {
    if ($event_admin['id'] !== $_SESSION['auth_id']) {
        $pathError =  "/mse/event.php?id=$id";
        header('Location: '. $pathError);
    }
}

?>

<section style="margin-top: 90px;">
    <div class="container">
        <div class="text-center mt-5">
            <h2><?php echo $event_infos['name'] ?></h2>
            <a href="event.php?id=<?php echo $id ?>" class="btn btn-info p-1 mt-2">Page de l'event</a>
        </div>
        <div class="row text-center">
            <div class="col">
                <h5 class="mt-5">Utilisateurs intéressés</h5>
                <div class="text-muted mb-5"><?php echo count($pending_users) ?> personne(s) désirant rejoindre l'event</div>
                <ul class="card-group d-flex flex-wrap">
                    <?php foreach ($pending_users as $pending_user) {
                        $pending_user_school = getSchool($pending_user['school_id']) ?>
                        <div class="col-3">
                            <div class="card-body px-0">
                                <h5 class="card-title"><?php echo $pending_user['first_name'] . " " . $pending_user['first_name'] ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo  $pending_user_school['name'] ?></h6>
                                <h6 class="card-subtitle mb-2 text-muted">Année N° <?php echo $pending_user['school_year'] ?></h6>
                                <a href="assets/eventactions.php?s=1&id=<?php echo $id ?>&u=<?php echo $pending_user['user_id'] ?>" class="btn btn-success p-1 mt-2">Accepter</a>
                                <a href="assets/eventactions.php?s=3&id=<?php echo $id ?>&u=<?php echo $pending_user['user_id'] ?>" class="btn btn-danger p-1 mt-2">Refuser</a>
                            </div>
                        </div>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
