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
    <div class="desktop">
        <div>
            <section class="menu-event">
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
                        <a href="assets/logout.php" class="text-danger nav-link border py-3 mt-5 border-left-0">Supprimer mon compte</a>
                        <a href="assets/logout.php" class="bg-white text-dark font-weight-bold nav-link border py-3 border-left-0">Déconnexion</a>
                    </div>
                <?php endif; ?>
            </div>
            </section>
            <div class="card container col-4" style="padding-top: 5px!important;max-width: 70%; margin-left: 30%;  margin-top: 10%; z-index: 99;">

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
                            <div class="card" style="position: absolute; width: 150px; z-index: 99; ">
                                <?php if (isset($_SESSION['auth_id'])) {
                                    if ($event_admin['id'] == $_SESSION['auth_id']) { ?>
                                        <a href="editevent.php?id=<?php echo $id ?>" style="color: black; border: solid 1px; height: 40px;">Éditer l'event</a>
                                        <a href="eventmembers.php?id=<?php echo $id ?>" style="color: black; border: solid 1px; height: 40px;">voir les membres</a>
                                        <a href="manageeventusers.php?id=<?php echo $id ?>" style="color: black; border: solid 1px; height: 40px;">gérer les membres</a>
                                        <a href="eventmembers.php?id=<?php echo $id ?>" style="color: black; border: solid 1px; height: 40px;">supprimer l'event</a>
                                    <?php } else{  ?>
                                        <a href="eventmembers.php?id=<?php echo $id ?>" style="color: black; border: solid 1px; height: 40px;">voir les membres</a>
                                 <?php   } if($event_state['private_pending'] === '0'){ ?>
                                        <a href="eventmembers.php?id=<?php echo $id ?>" style="color: black; border: solid 1px; height: 40px;">voir les membres</a>
                                        <a href="assets/eventactions.php?s=2&id=<?php echo $id ?>" style="color: black; border: solid 1px; height: 40px;">Annuler la demande</a>
                                        <a href="assets/eventactions.php?s=2&id=<?php echo $id ?>" style="color: black; border: solid 1px; height: 40px;">Quitter l'event</a>
                             <?php  }
                                } else { ?>
                                    <a href="login.php">se connecter</a>
                            <?php } ?>

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

                <hr class="bg-secondary">
                                <div class="d-flex justify-content-around mt-3">
                                    <?php if ($event_end_date < $current_datetime): ?>
                                        <div class="d-flex">
                                            <a href="event.php?id=<?php echo $event_infos['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-clock mt-1 text-warning"></i> Terminé</a>
                                        </div>
                                    <?php else: ?>
                                        <?php if (isset($_SESSION['auth_id'])):
                                            if ($event_infos['admin_id'] == $_SESSION['auth_id']): ?>
                                                <div class="d-flex">
                                                    <a href="event.php?id=<?php echo $event_infos['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-success"></i> Rejoint</a>
                                                </div>
                                            <?php else:
                                                $event_state = checkEventState($event_infos['id'], $_SESSION['auth_id']);
                                                if (empty($event_state)): ?>
                                                    <div class="d-flex">
                                                        <a href="event.php?id=<?php echo $event_infos['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-muted"></i> Participer</a>
                                                    </div>
                                                <?php else:
                                                    if ($event_state['private_pending'] == "1"): ?>
                                                        <div class="d-flex">
                                                            <a href="event.php?id=<?php echo $event_infos['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-warning"></i> Demande Envoyée</a>
                                                        </div>
                                                    <?php elseif ($event_state['private_pending'] == "0"): ?>
                                                        <div class="d-flex">
                                                            <a href="event.php?id=<?php echo $event_infos['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-success"></i> Rejoint</a>
                                                        </div>
                                                    <?php endif;
                                                endif;
                                            endif;
                                        else: ?>
                                            <div class="d-flex">
                                                <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-muted"></i> Participer</a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <div class="d-flex">
                                        <button class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Commenter</button>
                                    </div>
                                    <div class="d-flex">
                                        <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                    </div>
                                </div>

                            <div class="card-body text-center">
                                <div id="ContentPosts" class="col-10 mx-auto d-none newcommentform">
                                    <div class="card-header text-center h5">
                                        Ajouter un commentaire
                                    </div>
                                    <div class="card-body my-3 p-1">
                                        <form method="post" action="assets/addeventcomment.php?id=<?php echo $event_infos['id'] ?>">
                                            <div class="form-group">
                                                <label for="content">Contenu du commentaire</label>
                                                <textarea class="form-control mt-1" name="content" rows="2" required></textarea>
                                            </div>
                                            <?php if (isset($_SESSION['auth_id'])): ?>
                                                <button class="btn btn-outline-info my-2">Envoyer</button>
                                            <?php else: ?>
                                                <a href="login.php" class="btn btn-outline-info my-2">Envoyer</a>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-light bg-white text-secondary border-0 m-0 pr-1 ShowComments">Montrer les commentaires</button>
                                    <div class="nav-link text-muted px-0">(<?php echo count(getEventComments($event_infos['id'])) ?>)</div>
                                </div>
                                <div class="d-none ContentsComments">
                                    <?php $event_comments = getEventComments($event_infos['id']);
                                    foreach ($event_comments as $event_comment): ?>
                                        <div class="card-body" id="ContentPosts">
                                            <?php if (isset($_SESSION['auth_id'])):
                                                if ($event_comment['author_id'] == $_SESSION['auth_id']): ?>
                                                    <div class="d-flex flex-column align-items-end" id="deleteCommentBlock" style="border-radius: 10px;" title="Options du commentaire">
                                                        <button class="border-0 dropdownButtonPosts"><i class="fas fa-chevron-down"></i></button>
                                                        <div class="card d-none text-center position-relative border-0">
                                                            <a href="assets/deleventcomment.php?id=<?php echo $event_comment['id'] ?>&s=1" class="btn btn-outline-danger card-body px-2 py-0">Supprimer <i class="fas fa-trash-alt text-danger"></i></a>
                                                        </div>
                                                    </div>
                                                <?php endif;
                                            endif; ?>
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($event_comment['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                    <div class="d-flex">
                                                        <h6 class="card-title"><?php echo $event_comment['first_name'] . " " . $event_comment['last_name'] ?></h6>
                                                        <div class="d-flex justify-content-between ml-1" style="margin-top: -4px;">
                                                            <?php $user_badges = getUserBadges($event_comment['author_id']);
                                                            foreach ($user_badges as $user_badge): ?>
                                                                <div class="my-2 text-center" style="font-size: 0.25rem;">
                                                                    <span class="fa-stack fa-2x mx-auto" title="<?php echo $user_badge['name'] . " : " . $user_badge['description'] . "\n" . "Obtenu le : " . date('d/m/Y', strtotime($user_badge['date_added'])) ?>">
                                                                        <i class="fas fa-certificate fa-stack-2x" style="color: <?php echo $user_badge['color'] ?>"></i>
                                                                        <i class="fab <?php echo $user_badge['icon'] ?> fa-stack-1x fa-inverse"></i>
                                                                    </span>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($event_comment['date_added']); ?></h6>
                                                </div>
                                                <div class="mt-auto mb-4">
                                                    <div class="d-flex">
                                                        <i class="fas fa-heart mt-1 text-danger"></i>
                                                        <div class="ml-2"><?php echo count(getEventCommentLikes($event_comment['id'])); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted"><?php echo $event_comment['content'] ?></p>
                                            <hr class="bg-secondary">
                                            <div class="d-flex justify-content-around mt-3">
                                                <?php if (isset($_SESSION['auth_id'])):
                                                    $event_comment_likes = getEventCommentLikes($event_comment['id']);
                                                    if (empty($event_comment_likes)): ?>
                                                        <div class="d-flex">
                                                            <a href="assets/addeventcommentlike.php?id=<?php echo $event_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                        </div>
                                                    <?php else:
                                                        foreach ($event_comment_likes as $event_comment_like):
                                                            if ($event_comment_like['user_id'] == $_SESSION['auth_id']): ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/deleventcommentlike.php?id=<?php echo $event_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-heart mt-1 text-danger"></i> Aimé</a>
                                                                </div>
                                                                <?php break;
                                                            elseif (end($event_comment_likes) == $event_comment_like): ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/addeventcommentlike.php?id=<?php echo $event_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                                </div>
                                                            <?php endif;
                                                        endforeach;
                                                    endif;
                                                else: ?>
                                                    <div class="d-flex">
                                                        <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="d-flex">
                                                    <button class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Commenter</button>
                                                </div>
                                                <div class="d-flex">
                                                    <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

            </div>
        </div>
      
        <div class="container col-5" style="z-index: 99;">
            <div class="mapEvent" style="margin-top: 15%">
                <div class="gMaps">
                    <div class="gmap_canvas">
                        <iframe
                                id="gmap_canvas"
                                src="https://maps.google.com/maps?q=<?php echo $event_address["street_number"] . $event_address["address_line1"] . $event_address["address_line2"] . $event_address["city"] . $event_address["zip_code"] . $event_address["country"];
                                ?>&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                frameborder="0"
                                scrolling="no"
                                style="width: 40vw; height: 63vh;">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
</section>


<?php require_once 'includes/footer.php'; ?>
