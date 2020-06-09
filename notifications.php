<?php

require_once 'includes/header.php';
use Carbon\Carbon;
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.UTF8');

if (!isset($_SESSION['auth_id'])) {
    $pathError =  "/mse/login.php";
    header('Location: '. $pathError);
}

$friend_requests = getReceivedFriendRequests($_SESSION['auth_id']);
$owned_events = getOwnedEvents($_SESSION['auth_id']);
$found_requests = 0;

?>

<section style="margin-top: 90px">
    <div class="row text-center mt-4 w-100 mx-auto">
        <div class="col-6">
            <h1 class="h4 card-header">Demandes d'amis recues</h1>
            <div class="text-muted mb-5 mt-3"><?php echo (empty($friend_requests) ? "Aucune demande d'ami en attente" : count($friend_requests) . " demande(s) en attente") ?></div>
            <ul class="list-group align-items-center">
                <?php foreach ($friend_requests as $friend_request): ?>
                    <div class="card mb-3" style="width: 550px;">
                        <div class="row no-gutters d-flex">
                            <div class="align-self-center"><img src="https://www.gravatar.com/avatar/<?php echo md5($friend_request['email']); ?>?s=700" alt="" class="d-block rounded-circle " style="" id="ContentProfilePics"></div>
                            <div class="card-body mx-3 py-1">
                                <h5 class="card-title">
                                    <a href="profile.php?id=<?php echo $friend_request['user1_id'] ?>" class="text-black nav-link" title="Voir le profil"><?php echo $friend_request["first_name"] . " " . $friend_request["last_name"] ?></a>
                                </h5>
                                <p class="card-text"><small class="text-muted"><?php echo "Recue il y à " . getDateForHumans($friend_request['date_added']); ?></small></p>
                            </div>
                            <div class="align-self-center pr-2">
                                <a href="assets/friends.php?s=1&id=<?php echo $friend_request['user1_id'] ?>" class="btn btn-success">Accepter</a>
                                <a href="assets/friends.php?s=2&id=<?php echo $friend_request['user1_id'] ?>" class="btn btn-danger ml-2">Refuser</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-6">
            <h1 class="h4 card-header">Membres en attente pour vos events</h1>
            <div class="text-muted mb-5 mt-3"><?php echo (empty($owned_events) ? "Aucun event géré" : count($owned_events) . " event géré(s)") ?></div>
            <ul class="list-group align-items-center">
                <?php foreach ($owned_events as $owned_event):
                    $pending_users = getPendingUsers($owned_event['id']);
                    if (!empty($pending_users)):
                        $found_requests = 1; ?>
                        <div class="card mb-3" style="max-width: 500px;">
                            <div class="row no-gutters d-flex">
                                <div class="align-self-center"></div>
                                <div class="card-body mx-3 py-1">
                                    <h5 class="card-title">
                                        <a href="event.php?id=<?php echo $owned_event['id'] ?>" class="text-black nav-link" title="Voir l'event"><?php echo $owned_event['name'] ?></a>
                                    </h5>
                                    <p class="card-text"><small class="text-muted"><?php echo count($pending_users) . " demande(s) en attente"; ?></small></p>
                                </div>
                                <div class="align-self-center pr-2">
                                    <a href="accepteventusers.php?id=<?php echo $owned_event['id'] ?>" class="btn btn-info">Voir les demandes</a>
                                </div>
                            </div>
                        </div>
                <?php endif;
                endforeach;
                if ($found_requests == 0): ?>
                    <div class="text-muted mb-5 mt-3">Aucune demande pour vos event géré(s)</div>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</section>


<?php require_once 'includes/footer.php'; ?>
