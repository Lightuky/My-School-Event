<?php
require_once 'includes/header.php';
use Carbon\Carbon;
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.UTF8');

$id = isset($_GET['id']) ? $_GET['id'] : null;
$event_infos = getEvent($id);
$event_category = getCategory($id);
$event_admin = getUser($event_infos['admin_id']);
$pending_users = getPendingUsers($id);
$event_members = getEventMembers($id);
$event_address = getEventAddress($event_infos['id']);

$current_date = date('Y-m-d');
$current_datetime = date('Y-m-d H:i:s');
$event_duration_hours = date('G', strtotime($event_infos['duration']));
$event_duration_minutes = date('i', strtotime($event_infos['duration']));
$event_duration_seconds = date('s', strtotime($event_infos['duration']));
$event_start_date = date('Y-m-d H:i:s', strtotime($event_infos['date'] . $event_infos['time']));
$event_end_date = date('Y-m-d H:i:s',strtotime("+" . $event_duration_hours . " hour +" . $event_duration_minutes . " minutes +" . $event_duration_seconds . " seconds",strtotime($event_start_date)));

if ($event_infos['name'] == NULL) {
    $pathError =  "/mse/404.php";
    header('Location: '. $pathError);
}

if (isset($_SESSION['auth_id'])) {
    $event_state= checkEventState($id, $_SESSION['auth_id']);
}

?>

<section>
    <div class="d-flex">
        <div>
            <div class="col-2 m-0 p-0 bg-dark d-flex flex-column justify-content-between position-fixed" style="height: calc(100vh - 60px); bottom: 0;">
                <div>
                    <a href="index.php" class="text-white nav-link border py-3 mt-2 border-left-0">Acceuil</a>
                    <?php if (!isset($_SESSION['auth_id'])): ?>
                        <a href="login.php" class="text-white nav-link border py-3 mt-5 border-left-0">Se connecter</a>
                        <a href="login.php" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                        <a href="login.php" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                        <a href="login.php" class="text-white nav-link border py-3 border-left-0">Mes amis</a>
                        <a href="login.php" class="text-white nav-link border py-3 border-left-0">Progression</a>
                    <?php else: ?>
                        <a href="profile.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 mt-5 border-left-0">Mon profil</a>
                        <a href="calendar.php" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                        <a href="bugreport.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                        <a href="friends.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 border-left-0">Mes amis</a>
                        <a href="progress.php" class="text-white nav-link border py-3 border-left-0">Progression</a>
                    <?php endif; ?>
                </div>
                <?php if (isset($_SESSION['auth_id'])): ?>
                    <div class="">
                        <a href="assets/logout.php" class="text-white nav-link border py-3 mt-5 border-left-0" style="background-color: rgba(206, 130, 299, 0.3)">Supprimer mon compte</a>
                        <a href="assets/logout.php" class="bg-white text-dark font-weight-bold nav-link border py-3 border-left-0">Déconnexion</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card container col-4" style="padding-top: 5px!important;max-width: 70%; margin-left: 30%;  margin-top: 262px;">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <div class="text-muted mr-2">Évenement crée par</div>
                        <div class="h5"><?php echo $event_admin['first_name'] . " " . $event_admin['last_name']; ?></div>
                    </div>
                    <div>
                        <button class="" type="button" data-toggle="collapse" data-target="#collapseEvent" aria-expanded="false" aria-controls="collapseEvent">
                            <i class="fas fa-cog mt-2" style="color: black; font-size: 30px;" title="Éditer l'event"></i>
                        </button>
                        <div class="collapse" id="collapseEvent">
                            <div class="card" style="position: absolute">
                                <?php if (isset($_SESSION['auth_id'])) {
                                    if ($event_admin['id'] == $_SESSION['auth_id']) { ?>
                                        <a href="editevent.php?id=<?php echo $id ?>">Éditer event</a>
                                    <?php } else{ ?>
                                        <a href="eventmembers.php?id=<?php echo $id ?>" style="color: black">voir les membre</a>
                                    <?php   }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="mt-1"><?php echo $event_infos['name'] ?></h2>
                <?php if ($event_end_date < $current_datetime): ?>
                    <span class=" d-block">Terminé le <?php echo strftime("%A %e %B", strtotime($event_end_date)) . " à " . strftime("%Hh%M", strtotime($event_end_date)) ?></span>
                <?php elseif ($event_start_date < $current_datetime && $event_end_date > $current_datetime ): ?>
                    <span class="d-block">Événement en cours : Encore <?php echo gmdate("G\h i\m", strtotime($event_end_date) - strtotime($current_datetime)) ?></span>
                <?php elseif ($event_start_date > $current_datetime):
                    if ($current_date == $event_infos['date']): ?>
                        <span class=" d-block"><?php echo getDateForHumans($event_infos['time']) . "le" . strftime("%A %e %B", strtotime($event_infos['date'])) . "a" . strftime("%Hh%M", strtotime($event_infos['time'])) ?></span>
                    <?php else: ?>
                        <span class="d d-block">Le <?php echo strftime("%A %e %B", strtotime($event_infos['date'])) . " à " . strftime("%Hh%M", strtotime($event_infos['time'])) ?></span>
                    <?php endif;
                endif; ?>
                <div class="d-flex">
                    <i class="fas fa-map-marker-alt mt-1" style="color: red; margin-right: 5px"></i>
                    <span class="text-muted d-block"><?php echo $event_address['street_number'] . " " . $event_address['address_line1'] . ", "        . $event_address['address_line2'] . " "  . $event_address['zip_code'] . " " . $event_address['city'] ?></span>
                </div>
                <div class="d-flex">
                    <i class="far fa-clock mt-1" style=" margin-right: 5px"></i>
                    <span class="text-muted d-block">Durée : <?php echo strftime("%Hh%M", strtotime($event_infos['duration']));?></span>
                </div>
                <span class="d-block mt-4">Description : <?php echo $event_infos['description'] ?></span>
                <div class="d-flex mt-4">
                    <a href="eventmembers.php?id=<?php echo $id ?>" style="color: black"><i class="fas fa-users  mt-1" title="membres de l'événenment" style=" margin-right: 5px"></i></a>
                    <?php if ($event_infos['member_limit'] != '0') { ?>
                        <span class="text-muted d-block"><?php echo  count($event_members) . " sur " . $event_infos['member_limit'] ." personnes" ?><?php if ($event_infos['is_private'] == 1){ echo " (Événement privée) "; } ?></span>
                    <?php } else { ?>
                        <span class="text-muted d-block"><?php echo  count($event_members) . " personnes"?><?php if ($event_infos['is_private'] == 1){ echo " (Événement privée) "; } ?></span>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="">
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
                            <?php if ($event_end_date < $current_datetime): ?>
                            <?php else: ?>
                                <?php if (isset($_SESSION['auth_id'])): ?>
                                    <?php if ($_SESSION['auth_id'] != $event_admin['id']): ?>
                                        <?php if (!$event_state): ?>
                                            <?php if ($event_infos['member_limit'] != '0') {
                                                if (($event_infos['member_limit'] - count($event_members)) != '0') { ?>
                                                    <a href="assets/eventactions.php?s=0&id=<?php echo $id ?>" class="btn btn-success">Participer</a>
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
                                            <a href="login.php" class="btn btn-success">Participer</a>
                                        <?php }
                                        else { ?>
                                            <div class="btn btn-warning">Événement Plein</div>
                                        <?php }
                                    } ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php if (isset($_SESSION['auth_id'])) { ?>
                    <?php if ($_SESSION['auth_id'] != $event_admin['id']) { ?>
                        <a href="eventmembers.php?id=<?php echo $id ?>" class="btn btn-info mt-4">Voir les membres</a>
                    <?php }
                }
                else { ?>
                    <a href="login.php" class="btn btn-info mt-4">Voir les membres</a>
                <?php }
                ?>
            </div>
        </div>
        <div class="container col-5">
            <div class="mapEvent" style="margin-top: 50%">
                <div class="gMaps">
                    <div class="gmap_canvas">
                        <iframe
                                id="gmap_canvas"
                                src="https://maps.google.com/maps?q=<?php echo $event_address["street_number"] . $event_address["address_line1"] . $event_address["address_line2"] . $event_address["city"] . $event_address["zip_code"] . $event_address["country"];
                                ?>&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                frameborder="0"
                                scrolling="no"
                                style="width: 40vw; height: 40vh;">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
