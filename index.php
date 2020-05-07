<?php
require_once 'includes/header.php';
use Carbon\Carbon;
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR');

$categories = getCategories();
$events = getEventsSorted();
$posts = getPostsSorted();
$helps = getHelpsSorted();

?>

<section>
    <div class="row m-0">
        <div class="col-2 m-0 p-0 bg-dark d-flex flex-column justify-content-between position-fixed" style="height: calc(100vh - 60px); bottom: 0;">
            <div>
                <a href="index.php" class="text-white nav-link border py-3 mt-2 border-left-0">Acceuil</a>
                <?php if (!isset($_SESSION['auth_id'])) { ?>
                    <a href="login.php" class="text-white nav-link border py-3 mt-5 border-left-0">Se connecter</a>
                    <a href="login.php" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                    <a href="login.php" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                <?php }
                else { ?>
                    <a href="profile.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 mt-5 border-left-0">Mon profil</a>
                    <a href="calendar.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                    <a href="bugreport.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                <?php } ?>
            </div>
            <?php if (isset($_SESSION['auth_id'])) { ?>
                <div class="">
                    <a href="assets/logout.php" class="text-danger nav-link border py-3 mt-5 border-left-0">Supprimer mon compte</a>
                    <a href="assets/logout.php" class="bg-white text-dark font-weight-bold nav-link border py-3 border-left-0">Déconnexion</a>
                </div>
            <?php } ?>
        </div>
        <div class="col-10 mx-0 p-0 d-flex ml-auto" style="padding-top: 60px!important;">
            <div class="col-2 mt-4 p-0 text-center">
                <?php if (!isset($_SESSION['auth_id'])) {?>
                    <a href="login.php" class="btn btn-secondary">Ajouter un event</a>
                <?php }
                else { ?>
                    <a href="addevent.php" class="btn btn-secondary">Ajouter un event</a>
                <?php } ?>
            </div>
            <div class="col-7 p-0">
                <div class="d-flex justify-content-around">
                    <div class="col-3 text-center">
                        <a href="#" class="h5 nav-link text-dark mt-3" id="SortMenuAll">Tout</a>
                        <div class="bg-dark mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                    </div>
                    <div class="col-3 text-center">
                        <a href="#" class="h5 nav-link text-dark mt-5" id="SortMenuPosts">Posts</a>
                        <div class="bg-dark mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                    </div>
                    <div class="col-3 text-center">
                        <a href="#" class="h5 nav-link text-dark mt-5" id="SortMenuEvents">Événements</a>
                        <div class="bg-dark mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                    </div>
                    <div class="col-3 text-center">
                        <a href="#" class="h5 nav-link text-dark mt-5" id="SortMenuHelps">Helps</a>
                        <div class="bg-dark mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                    </div>
                </div>
                <div id="allPosts">
                    <div class="card col-10 my-5 mx-auto" id="ContentPosts" style="border-radius: initial">
                        <div class="card-header text-center">
                            Ajouter une publication
                        </div>
                        <div class="card-body mt-2 p-1">
                            <form method="post" action="assets/addpost.php">
                                <div class="form-group">
                                    <label for="content">Contenu du post</label>
                                    <textarea class="form-control mt-1" name="content" rows="2" required></textarea>
                                </div>
                                <?php if (isset($_SESSION['auth_id'])) { ?>
                                    <button class="btn btn-outline-info my-2">Poster</button>
                                <?php } else { ?>
                                    <a href="login.php" class="btn btn-outline-info my-2">Poster</a>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                    <?php foreach ($posts as $post){ ?>
                        <div class="card col-10 mx-auto mt-4" id="ContentPosts">
                            <div class="card-body">
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($post['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                                <h5 class="card-title"><?php echo $post['first_name'] . " " . $post['last_name'] ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($post['date_added']); ?></h6>
                                <p class="card-text text-muted"><?php echo $post['content'] ?></p>
                                <div class="d-flex">
                                    <i class="fas fa-star mt-1" style="color: gold"></i>
                                    <div class="ml-2"><?php echo count(getPostLikes($post['id'])); ?></div>
                                </div>
                                <hr class="bg-secondary">
                                <div class="d-flex justify-content-around mt-3">
                                    <?php if (isset($_SESSION['auth_id'])) {
                                        $post_likes = getPostLikes($post['id']);
                                        if (empty($post_likes)) { ?>
                                            <div class="d-flex">
                                                <a href="assets/addpostlike.php?id=<?php echo $post['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-star mt-1 text-muted"></i> Favori</a>
                                            </div>
                                        <?php }
                                        else {
                                            foreach ($post_likes as $post_like) {
                                                if ($post_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                    <div class="d-flex">
                                                        <a href="assets/delpostlike.php?id=<?php echo $post['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-star mt-1 text-warning"></i> Favori</a>
                                                    </div>
                                                    <?php break;
                                                }
                                                elseif (end($post_likes) == $post_like) { ?>
                                                    <div class="d-flex">
                                                        <a href="assets/addpostlike.php?id=<?php echo $post['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-star mt-1 text-muted"></i> Favori</a>
                                                    </div>
                                                <?php }
                                            }
                                        }
                                    }
                                    else { ?>
                                        <div class="d-flex">
                                            <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-star mt-1 text-muted"></i> Favori</a>
                                        </div>
                                    <?php } ?>
                                    <div class="d-flex">
                                        <button class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Commenter</button>
                                    </div>
                                    <div class="d-flex">
                                        <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <div id="ContentPosts" class="col-10 mx-auto d-none newcommentform">
                                    <div class="card-header text-center h5">
                                        Ajouter un commentaire
                                    </div>
                                    <div class="card-body my-3 p-1">
                                        <form method="post" action="assets/addpostcomment.php?id=<?php echo $post['id'] ?>">
                                            <div class="form-group">
                                                <label for="content">Contenu du commentaire</label>
                                                <textarea class="form-control mt-1" name="content" rows="2" required></textarea>
                                            </div>
                                            <?php if (isset($_SESSION['auth_id'])) { ?>
                                                <button class="btn btn-outline-info my-2">Envoyer</button>
                                            <?php } else { ?>
                                                <a href="login.php" class="btn btn-outline-info my-2">Envoyer</a>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-light bg-white text-secondary border-0 m-0 pr-1 ShowComments">Montrer les commentaires</button>
                                    <div class="nav-link text-muted px-0">(<?php echo count(getPostComments($post['id'])) ?>)</div>
                                </div>
                                <div class="d-none ContentsComments">
                                    <?php $post_comments = getPostComments($post['id']);
                                    foreach ($post_comments as $post_comment) { ?>
                                        <div class="card-body" id="ContentPosts">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($post_comment['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                    <h6 class="card-title"><?php echo $post_comment['first_name'] . " " . $post_comment['last_name'] ?></h6>
                                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($post_comment['date_added']); ?></h6>
                                                </div>
                                                <div class="mt-auto mb-4">
                                                    <div class="d-flex">
                                                        <i class="fas fa-heart mt-1 text-danger"></i>
                                                        <div class="ml-2"><?php echo count(getPostCommentLikes($post_comment['id'])); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted"><?php echo $post_comment['content'] ?></p>
                                            <hr class="bg-secondary">
                                            <div class="d-flex justify-content-around mt-3">
                                                <?php if (isset($_SESSION['auth_id'])) {
                                                    $post_comment_likes = getPostCommentLikes($post_comment['id']);
                                                    if (empty($post_comment_likes)) { ?>
                                                        <div class="d-flex">
                                                            <a href="assets/addpostcommentlike.php?id=<?php echo $post_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                        </div>
                                                        <?php break;
                                                    }
                                                    else {
                                                        foreach ($post_comment_likes as $post_comment_like) {
                                                            if ($post_comment_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/delpostcommentlike.php?id=<?php echo $post_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-heart mt-1 text-danger"></i> Aimé</a>
                                                                </div>
                                                                <?php break;
                                                            }
                                                            elseif (end($post_comment_likes) == $post_comment_like) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/addpostcommentlike.php?id=<?php echo $post_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                                </div>
                                                            <?php }
                                                        }
                                                    }
                                                }
                                                else { ?>
                                                    <div class="d-flex">
                                                        <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                    </div>
                                                <?php } ?>
                                                <div class="d-flex">
                                                    <button class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Commenter</button>
                                                </div>
                                                <div class="d-flex">
                                                    <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div id="allEvents">
                    <?php foreach ($events as $event){ ?>
                        <div class="card col-10 mx-auto mt-5" id="ContentPosts">
                            <div class="card-body">
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($post['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                                <h5 class="card-title"><?php echo $event['first_name'] . " " . $event['last_name'] ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($event['date_added']); ?></h6>
                                <p class="card-text text-muted"><?php echo $event['description'] ?></p>
                                <p class="h5 font-weight-bold"><?php echo $event['name'] ?></p>
                                <p class="card-text mb-1"><?php echo "Le " . strftime("%A%e %B", strtotime($event['date'])) . " à " . strftime("%Hh%M", strtotime($event['time'])) ?></p>
                                <div class="d-flex">
                                    <i class="fas fa-map-marker-alt mt-1" style="color: red"></i>
                                    <?php $event_address = getEventAddress($event['id']) ?>
                                    <p class="card-text text-muted ml-2"><?php echo $event_address['street_number'] . " " . $event_address['address_line1'] . ", "
                                            . $event_address['address_line2'] . " "  . $event_address['zip_code'] . " " . $event_address['city'] ?></p>
                                </div>
                                <div class="d-flex mt-3">
                                    <i class="fas fa-check-circle mt-1" style="color: forestgreen"></i>
                                    <div class="ml-2"><?php echo count(getEventMembers($event['id'])); ?></div>
                                </div>
                                <hr class="bg-secondary">
                                <div class="d-flex justify-content-around mt-3">
                                    <?php if (isset($_SESSION['auth_id'])) {
                                        if ($event['admin_id'] == $_SESSION['auth_id']) { ?>
                                            <div class="d-flex">
                                                <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-success"></i> Rejoint</a>
                                            </div>
                                        <?php }
                                        else {
                                            $event_state = checkEventState($event['id'], $_SESSION['auth_id']);
                                            if (empty($event_state)) { ?>
                                                <div class="d-flex">
                                                    <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-muted"></i> Participer</a>
                                                </div>
                                            <?php }
                                            else {
                                                if ($event_state['private_pending'] == "1") { ?>
                                                    <div class="d-flex">
                                                        <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-warning"></i> Demande Envoyée</a>
                                                    </div>
                                                <?php  }
                                                elseif ($event_state['private_pending'] == "0") { ?>
                                                    <div class="d-flex">
                                                        <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-success"></i> Rejoint</a>
                                                    </div>
                                                <?php }
                                            }
                                        }
                                    }
                                    else { ?>
                                        <div class="d-flex">
                                            <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-muted"></i> Participer</a>
                                        </div>
                                    <?php } ?>
                                    <div class="d-flex">
                                        <button class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Commenter</button>
                                    </div>
                                    <div class="d-flex">
                                        <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <div id="ContentPosts" class="col-10 mx-auto d-none newcommentform">
                                    <div class="card-header text-center h5">
                                        Ajouter un commentaire
                                    </div>
                                    <div class="card-body my-3 p-1">
                                        <form method="post" action="assets/addeventcomment.php?id=<?php echo $event['id'] ?>">
                                            <div class="form-group">
                                                <label for="content">Contenu du commentaire</label>
                                                <textarea class="form-control mt-1" name="content" rows="2" required></textarea>
                                            </div>
                                            <?php if (isset($_SESSION['auth_id'])) { ?>
                                                <button class="btn btn-outline-info my-2">Envoyer</button>
                                            <?php } else { ?>
                                                <a href="login.php" class="btn btn-outline-info my-2">Envoyer</a>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-light bg-white text-secondary border-0 m-0 pr-1 ShowComments">Montrer les commentaires</button>
                                    <div class="nav-link text-muted px-0">(<?php echo count(getEventComments($event['id'])) ?>)</div>
                                </div>
                                <div class="d-none ContentsComments">
                                    <?php $event_comments = getEventComments($event['id']);
                                    foreach ($event_comments as $event_comment) { ?>
                                        <div class="card-body" id="ContentPosts">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($event_comment['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                    <h6 class="card-title"><?php echo $event_comment['first_name'] . " " . $event_comment['last_name'] ?></h6>
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
                                                <?php if (isset($_SESSION['auth_id'])) {
                                                    $event_comment_likes = getEventCommentLikes($event_comment['id']);
                                                    if (empty($event_comment_likes)) { ?>
                                                        <div class="d-flex">
                                                            <a href="assets/addeventcommentlike.php?id=<?php echo $event_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                        </div>
                                                    <?php }
                                                    else {
                                                        foreach ($event_comment_likes as $event_comment_like) {
                                                            if ($event_comment_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/deleventcommentlike.php?id=<?php echo $event_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-heart mt-1 text-danger"></i> Aimé</a>
                                                                </div>
                                                                <?php break;
                                                            }
                                                            elseif (end($event_comment_likes) == $event_comment_like) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/addeventcommentlike.php?id=<?php echo $event_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                                </div>
                                                            <?php }
                                                        }
                                                    }
                                                }
                                                else { ?>
                                                    <div class="d-flex">
                                                        <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                    </div>
                                                <?php } ?>
                                                <div class="d-flex">
                                                    <button class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Commenter</button>
                                                </div>
                                                <div class="d-flex">
                                                    <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div id="allHelps">
                    <div class="card col-10 my-5 mx-auto" id="ContentPosts" style="display: none; border-radius: initial">
                        <div class="card-header text-center">
                            Poser une question
                        </div>
                        <div class="card-body mt-2 p-1">
                            <form method="post" action="assets/addhelp.php">
                                <div class="form-group">
                                    <label for="title">Titre</label>
                                    <input type="text" name="title" placeholder="Comment faire une ancre en HTML ?" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="content">Description</label>
                                    <textarea class="form-control mt-1" name="content" rows="2" required></textarea>
                                </div>
                                <?php if (isset($_SESSION['auth_id'])) { ?>
                                    <button class="btn btn-outline-info my-2">Poster</button>
                                <?php } else { ?>
                                    <a href="login.php" class="btn btn-outline-info my-2">Poster</a>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                    <?php foreach ($helps as $help){ ?>
                        <div class="card col-10 mx-auto mt-4" id="ContentPosts">
                            <div class="card-body">
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($help['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                                <h5 class="card-title"><?php echo $help['first_name'] . " " . $help['last_name'] ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($help['date_added']); ?></h6>
                                <p class="card-text text-secondary font-weight-bold mb-0 mt-3"><?php echo $help['title'] ?></p>
                                <p class="card-text text-muted"><?php echo $help['content'] ?></p>
                                <div class="d-flex">
                                    <i class="fas fa-lightbulb mt-1 text-info"></i>
                                    <div class="ml-2"><?php echo count(getHelpLikes($help['id'])); ?></div>
                                </div>
                                <hr class="bg-secondary">
                                <div class="d-flex justify-content-around mt-3">
                                    <?php if (isset($_SESSION['auth_id'])) {
                                        $help_likes = getHelpLikes($help['id']);
                                        if (empty($help_likes)) { ?>
                                            <div class="d-flex">
                                                <a href="assets/addhelplike.php?id=<?php echo $help['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
                                            </div>
                                        <?php }
                                        else {
                                            foreach ($help_likes as $help_like) {
                                                if ($help_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                    <div class="d-flex">
                                                        <a href="assets/delhelplike.php?id=<?php echo $help['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-info"></i> Pertinent</a>
                                                    </div>
                                                    <?php break;
                                                }
                                                elseif (end($help_likes) == $help_like) { ?>
                                                    <div class="d-flex">
                                                        <a href="assets/addhelplike.php?id=<?php echo $help['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
                                                    </div>
                                                <?php }
                                            }
                                        }
                                    }
                                    else { ?>
                                        <div class="d-flex">
                                            <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
                                        </div>
                                    <?php } ?>
                                    <div class="d-flex">
                                        <a href="help.php?id=<?php echo $help['id'] ?>" class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Commenter</a>
                                    </div>
                                    <div class="d-flex">
                                        <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-light bg-white text-secondary border-0 m-0 pr-1 ShowAnswer">Montrer la réponse la plus utile</button>
                                </div>
                                <div class="d-none BestAnswer">
                                    <?php $help_answers = getHelpComments($help['id']);
                                    $help_answer_infos = getHelpComments($help['id']);
                                    foreach ($help_answer_infos as $help_answer_info) { ?>
                                        <div class="card-body" id="ContentPosts">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($help_answer_info['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                    <h6 class="card-title"><?php echo $help_answer_info['first_name'] . " " . $help_answer_info['last_name'] ?></h6>
                                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($help_answer_info['date_added']); ?></h6>
                                                </div>
                                                <div class="mt-auto mb-4">
                                                    <div class="d-flex">
                                                        <i class="fas fa-heart mt-1 text-danger"></i>
                                                        <div class="ml-2"><?php echo count(getHelpAnswerLikes($help_answer_info['id'])); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted"><?php echo $help_answer_info['content'] ?></p>
                                            <hr class="bg-secondary">
                                            <div class="d-flex justify-content-around mt-3">
                                                <?php if (isset($_SESSION['auth_id'])) {
                                                    $help_comment_likes = getHelpComments($help_answer_info['id']);
                                                    if (empty($help_comment_likes)) { ?>
                                                        <div class="d-flex">
                                                            <a href="assets/addhelpcommentlike.php?id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                        </div>
                                                        <?php break;
                                                    }
                                                    else {
                                                        foreach ($help_comment_likes as $help_comment_like) {
                                                            if ($help_comment_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/delhelpcommentlike.php?id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-heart mt-1 text-danger"></i> Aimé</a>
                                                                </div>
                                                                <?php break;
                                                            }
                                                            elseif (end($help_comment_likes) == $help_comment_like) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/addhelpcommentlike.php?id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                                </div>
                                                            <?php }
                                                        }
                                                    }
                                                }
                                                else { ?>
                                                    <div class="d-flex">
                                                        <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                    </div>
                                                <?php } ?>
                                                <div class="d-flex">
                                                    <button class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Commenter</button>
                                                </div>
                                                <div class="d-flex">
                                                    <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-3 mt-4 p-0 text-center">
                <div class="btn btn-secondary" id="btnSort">Trier</div>
                <div class="border col-8 mx-auto mt-2" id="SortForm" style="display: none">
                    <div>test</div>
                    <div>test</div>
                    <div>test</div>
                    <div>test</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
