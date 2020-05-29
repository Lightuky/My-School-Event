<?php
require_once 'includes/header.php';
use Carbon\Carbon;
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.UTF8');

$query_city = isset($_GET['c']) ? $_GET['c'] : null;
$query_school = isset($_GET['s']) ? $_GET['s'] : null;
$categories = getCategories();
$cities = getCities();
$schools = getSchools();

if (!$query_city AND !$query_school):
    $events = getEventsSorted();
    $posts = getPostsSorted();
    $helps = getHelpsSorted();
else:
    if (!$query_city AND $query_school):
        $query_column = "school_id";
        $table_join = "schools";
        $events = getEventsQueryOneParam($query_school,$query_column,$table_join);
        $posts = getPostsQueryOneParam($query_school,$query_column,$table_join);
        $helps = getHelpsQueryOneParam($query_school,$query_column,$table_join);
    elseif ($query_city AND !$query_school):
        $query_column = "city_id";
        $table_join = "cities";
        $events = getEventsQueryOneParam($query_city,$query_column,$table_join);
        $posts = getPostsQueryOneParam($query_city,$query_column,$table_join);
        $helps = getHelpsQueryOneParam($query_city,$query_column,$table_join);
    else:
        $events = getEventsQueryTwoParam($query_city,$query_school);
        $posts = getPostsQueryTwoParam($query_city,$query_school);
        $helps = getHelpsQueryTwoParam($query_city,$query_school);
    endif;
endif;

?>

<section>
    <div class="row m-0">
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
        <div class="col-10 mx-0 p-0 d-flex ml-auto" style="padding-top: 60px!important;">
            <div class="col-2 mt-4 p-0 text-center">
                <?php if (!isset($_SESSION['auth_id'])): ?>
                    <a href="login.php" class="btn btn-secondary">Ajouter un event</a>
                <?php else: ?>
                    <a href="addevent.php" class="btn btn-secondary">Ajouter un event</a>
                <?php endif; ?>
            </div>
            <div class="col-7 p-0">
                <div class="d-flex justify-content-around">
                    <div class="col-3 text-center">
                        <button class="h5 nav-link text-dark mt-3 border-0 mx-auto" id="SortMenuAll">Tout</button>
                        <div class="bg-dark mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                    </div>
                    <div class="col-3 text-center">
                        <button class="h5 nav-link text-dark mt-5 border-0 mx-auto" id="SortMenuPosts">Posts</button>
                        <div class="bg-dark mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                    </div>
                    <div class="col-3 text-center">
                        <button class="h5 nav-link text-dark mt-5 border-0 mx-auto" id="SortMenuEvents">Événements</button>
                        <div class="bg-dark mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                    </div>
                    <div class="col-3 text-center">
                        <button class="h5 nav-link text-dark mt-5 border-0 mx-auto" id="SortMenuHelps">Helps</button>
                        <div class="bg-dark mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                    </div>
                </div>
                <div id="allPosts">
                    <div class="card col-10 my-5 mx-auto" id="ContentPosts" style="border-radius: initial">
                        <div class="card-header text-center">Ajouter une publication</div>
                        <div class="card-body mt-2 p-1">
                            <form method="post" action="assets/addpost.php" id="addPostForm">
                                <div class="form-group">
                                    <label for="content">Contenu du post</label>
                                    <textarea class="form-control mt-1" name="content" rows="2" required></textarea>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <div class="btn btn-outline-success postAttachmentsImgur">Lier un album Imgur</div>
                                    <div class="text-secondary mt-1 font-weight-bolder">ou</div>
                                    <div class="btn btn-outline-danger postAttachmentsYoutube">Ajouter une vidéo Youtube</div>
                                </div>
                                <div class="form-group mt-4">
                                    <div class="input-group mb-3 d-none inputLinkImgur">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-secondary" id="basic-addon3">imgur.com/a/</span>
                                        </div>
                                        <input type="text" class="form-control text-secondary" name="linkImgur" aria-describedby="basic-addon3" placeholder="AbCtrGe" maxlength="15">
                                    </div>
                                    <div class="input-group mb-3 d-none inputLinkYoutube">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-secondary" id="basic-addon3">youtube.com/watch?v=</span>
                                        </div>
                                        <input type="text" class="form-control text-secondary" name="linkYoutube" aria-describedby="basic-addon3" placeholder="R5Vf5p5pyj4" maxlength="15">
                                    </div>
                                </div>
                                <div class="w-100 text-right">
                                    <?php if (isset($_SESSION['auth_id'])): ?>
                                        <button class="btn btn-outline-info my-2">Poster</button>
                                    <?php else: ?>
                                        <a href="login.php" class="btn btn-outline-info my-2">Poster</a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php foreach ($posts as $post):
                        $attachments = getPostAttachments($post['id']);
                        ?>
                        <div class="card col-10 mx-auto mt-4" id="ContentPosts">
                            <div class="card-body">
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($post['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                                <div class="d-flex">
                                    <h5 class="card-title mr-2"><?php echo $post['first_name'] . " " . $post['last_name'] ?></h5>
                                    <div class="d-flex justify-content-between" style="margin-top: -4px;">
                                        <?php $user_badges = getUserBadges($post['author_id']);
                                        foreach ($user_badges as $user_badge): ?>
                                            <div class="my-2 text-center" style="font-size: 0.3rem;">
                                                <span class="fa-stack fa-2x mx-auto" title="<?php echo $user_badge['name'] . " : " . $user_badge['description'] . "\n" . "Obtenu le : " . date('d/m/Y', strtotime($user_badge['date_added'])) ?>">
                                                    <i class="fas fa-certificate fa-stack-2x" style="color: <?php echo $user_badge['color'] ?>"></i>
                                                    <i class="fab <?php echo $user_badge['icon'] ?> fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($post['date_added']); ?></h6>
                                <p class="card-text text-muted"><?php echo $post['content'] ?></p>
                                <?php if ($attachments):
                                    if ($attachments['type'] == "imgur"): ?>
                                        <div class="ml-5">
                                            <blockquote class="imgur-embed-pub" lang="en" data-id="<?php echo 'a/' . $attachments['hash'] ?>" data-context="false">
                                                <a href="<?php echo '//imgur.com/a/' . $attachments['hash'] ?>"></a>
                                            </blockquote>
                                            <script async src="//s.imgur.com/min/embed.js" charset="utf-8"></script>
                                        </div>
                                    <?php elseif ($attachments['type'] == "youtube"): ?>
                                        <div style="margin-left: 11%">
                                            <iframe
                                                    width="426" height="248" src="<?php echo 'https://www.youtube.com/embed/' . $attachments['hash'] ?>" frameborder="0"
                                                    allow="accelerometer;autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="border-radius: 10px;">
                                            </iframe>
                                        </div>
                                    <?php endif;
                                endif; ?>
                                <div class="d-flex">
                                    <i class="fas fa-star mt-1" style="color: gold"></i>
                                    <div class="ml-2"><?php echo count(getPostLikes($post['id'])); ?></div>
                                </div>
                                <hr class="bg-secondary">
                                <div class="d-flex justify-content-around mt-3">
                                    <?php if (isset($_SESSION['auth_id'])):
                                        $post_likes = getPostLikes($post['id']);
                                        if (empty($post_likes)): ?>
                                            <div class="d-flex">
                                                <a href="assets/addpostlike.php?id=<?php echo $post['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-star mt-1 text-muted"></i> Favori</a>
                                            </div>
                                        <?php else:
                                            foreach ($post_likes as $post_like):
                                                if ($post_like['user_id'] == $_SESSION['auth_id']): ?>
                                                    <div class="d-flex">
                                                        <a href="assets/delpostlike.php?id=<?php echo $post['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-star mt-1 text-warning"></i> Favori</a>
                                                    </div>
                                                    <?php break;
                                                elseif (end($post_likes) == $post_like): ?>
                                                    <div class="d-flex">
                                                        <a href="assets/addpostlike.php?id=<?php echo $post['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-star mt-1 text-muted"></i> Favori</a>
                                                    </div>
                                                <?php endif;
                                            endforeach;
                                        endif;
                                    else: ?>
                                        <div class="d-flex">
                                            <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-star mt-1 text-muted"></i> Favori</a>
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
                                    <div class="nav-link text-muted px-0">(<?php echo count(getPostComments($post['id'])) ?>)</div>
                                </div>
                                <div class="d-none ContentsComments">
                                    <?php
                                    $post_comments = getPostComments($post['id']);
                                    foreach ($post_comments as $post_comment): ?>
                                        <div class="card-body" id="ContentPosts">
                                            <?php if (isset($_SESSION['auth_id'])):
                                                if ($post_comment['author_id'] == $_SESSION['auth_id']): ?>
                                                    <div class="d-flex flex-column align-items-end" id="deleteCommentBlock" style="border-radius: 10px;" title="Options du commentaire">
                                                        <button class="border-0 dropdownButtonPosts"><i class="fas fa-chevron-down"></i></button>
                                                        <div class="card d-none text-center position-relative border-0">
                                                            <a href="assets/delpostcomment.php?id=<?php echo $post_comment['id'] ?>&s=1" class="btn btn-outline-danger card-body px-2 py-0">Supprimer <i class="fas fa-trash-alt text-danger"></i></a>
                                                        </div>
                                                    </div>
                                                <?php endif;
                                            endif; ?>
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($post_comment['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                    <div class="d-flex">
                                                        <h6 class="card-title"><?php echo $post_comment['first_name'] . " " . $post_comment['last_name'] ?></h6>
                                                        <div class="d-flex justify-content-between ml-1" style="margin-top: -4px;">
                                                        <?php $user_badges = getUserBadges($post_comment['author_id']);
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
                                                    <h6 class="card-subtitle mb-2 text-muted text-left"><?php echo "Il y à " . getDateForHumans($post_comment['date_added']); ?></h6>
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
                                                <?php if (isset($_SESSION['auth_id'])):
                                                    $post_comment_likes = getPostCommentLikes($post_comment['id']);
                                                    if (empty($post_comment_likes)): ?>
                                                        <div class="d-flex">
                                                            <a href="assets/addpostcommentlike.php?id=<?php echo $post_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
                                                        </div>
                                                    <?php else:
                                                        foreach ($post_comment_likes as $post_comment_like):
                                                            if ($post_comment_like['user_id'] == $_SESSION['auth_id']): ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/delpostcommentlike.php?id=<?php echo $post_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-heart mt-1 text-danger"></i> Aimé</a>
                                                                </div>
                                                                <?php break;
                                                            elseif (end($post_comment_likes) == $post_comment_like): ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/addpostcommentlike.php?id=<?php echo $post_comment['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-heart mt-1 text-muted"></i> Aimer</a>
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
                    <?php endforeach; ?>
                </div>

                <div id="allEvents">
                    <?php foreach ($events as $event):
                        $current_datetime = date('Y-m-d H:i:s');
                        $event_duration_hours = date('G', strtotime($event['duration']));
                        $event_duration_minutes = date('i', strtotime($event['duration']));
                        $event_duration_seconds = date('s', strtotime($event['duration']));
                        $event_start_date = date('Y-m-d H:i:s', strtotime($event['date'] . $event['time']));
                        $event_end_date = date('Y-m-d H:i:s',strtotime("+" . $event_duration_hours . " hour +" . $event_duration_minutes . " minutes +" . $event_duration_seconds . " seconds",strtotime($event_start_date)));
                        ?>
                        <div class="card col-10 mx-auto mt-5" id="ContentPosts">
                            <div class="card-body">
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($event['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                                <div class="d-flex">
                                    <h5 class="card-title"><?php echo $event['first_name'] . " " . $event['last_name'] ?></h5>
                                    <div class="d-flex justify-content-between ml-1" style="margin-top: -4px;">
                                        <?php $user_badges = getUserBadges($event['admin_id']);
                                        foreach ($user_badges as $user_badge): ?>
                                            <div class="my-2 text-center" style="font-size: 0.3rem;">
                                                <span class="fa-stack fa-2x mx-auto" title="<?php echo $user_badge['name'] . " : " . $user_badge['description'] . "\n" . "Obtenu le : " . date('d/m/Y', strtotime($user_badge['date_added'])) ?>">
                                                    <i class="fas fa-certificate fa-stack-2x" style="color: <?php echo $user_badge['color'] ?>"></i>
                                                    <i class="fab <?php echo $user_badge['icon'] ?> fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($event['date_added']); ?></h6>
                                <p class="h5 font-weight-bold"><?php echo $event['name'] ?></p>
                                <p class="card-text text-muted"><?php echo $event['description'] ?></p>
                                <p class="card-text mb-1"><?php echo "Le " . strftime("%A %e %B", strtotime($event['date'])) . " à " . strftime("%Hh%M", strtotime($event['time'])) ?></p>
                                <div class="d-flex">
                                    <i class="fas fa-map-marker-alt mt-1" style="color: red"></i>
                                    <?php $event_address = getEventAddress($event['id']) ?>
                                    <p class="card-text text-muted ml-2"><?php echo $event_address['street_number'] . " " . $event_address['address_line1'] . ", " . $event_address['address_line2'] . " "  . $event_address['zip_code'] . " " . $event_address['city'] ?></p>
                                </div>
                                <div class="d-flex mt-3">
                                    <i class="fas fa-check-circle mt-1" style="color: forestgreen"></i>
                                    <div class="ml-2"><?php echo count(getEventMembers($event['id'])); ?></div>
                                </div>
                                <hr class="bg-secondary">
                                <div class="d-flex justify-content-around mt-3">
                                    <?php if ($event_end_date < $current_datetime): ?>
                                        <div class="d-flex">
                                            <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-clock mt-1 text-warning"></i> Terminé</a>
                                        </div>
                                    <?php else: ?>
                                        <?php if (isset($_SESSION['auth_id'])):
                                            if ($event['admin_id'] == $_SESSION['auth_id']): ?>
                                                <div class="d-flex">
                                                    <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-success"></i> Rejoint</a>
                                                </div>
                                            <?php else:
                                                $event_state = checkEventState($event['id'], $_SESSION['auth_id']);
                                                if (empty($event_state)): ?>
                                                    <div class="d-flex">
                                                        <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-muted"></i> Participer</a>
                                                    </div>
                                                <?php else:
                                                    if ($event_state['private_pending'] == "1"): ?>
                                                        <div class="d-flex">
                                                            <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-warning"></i> Demande Envoyée</a>
                                                        </div>
                                                    <?php elseif ($event_state['private_pending'] == "0"): ?>
                                                        <div class="d-flex">
                                                            <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-success"></i> Rejoint</a>
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
                                    <div class="nav-link text-muted px-0">(<?php echo count(getEventComments($event['id'])) ?>)</div>
                                </div>
                                <div class="d-none ContentsComments">
                                    <?php $event_comments = getEventComments($event['id']);
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
                    <?php endforeach; ?>
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
                                <?php if (isset($_SESSION['auth_id'])): ?>
                                    <button class="btn btn-outline-info my-2">Poster</button>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-outline-info my-2">Poster</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <?php foreach ($helps as $help): ?>
                        <div class="card col-10 mx-auto mt-4">
                            <div class="card-body">
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($help['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                                <div class="d-flex">
                                    <h5 class="card-title"><?php echo $help['first_name'] . " " . $help['last_name'] ?></h5>
                                    <div class="d-flex justify-content-between ml-1" style="margin-top: -4px;">
                                        <?php $user_badges = getUserBadges($help['author_id']);
                                        foreach ($user_badges as $user_badge): ?>
                                            <div class="my-2 text-center" style="font-size: 0.3rem;">
                                                <span class="fa-stack fa-2x mx-auto" title="<?php echo $user_badge['name'] . " : " . $user_badge['description'] . "\n" . "Obtenu le : " . date('d/m/Y', strtotime($user_badge['date_added'])) ?>">
                                                    <i class="fas fa-certificate fa-stack-2x" style="color: <?php echo $user_badge['color'] ?>"></i>
                                                    <i class="fab <?php echo $user_badge['icon'] ?> fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($help['date_added']); ?></h6>
                                <p class="card-text text-secondary font-weight-bold mb-0 mt-3"><?php echo $help['title'] ?></p>
                                <p class="card-text text-muted"><?php echo $help['content'] ?></p>
                                <div class="d-flex">
                                    <i class="fas fa-lightbulb mt-1 text-info"></i>
                                    <div class="ml-2"><?php echo count(getHelpLikes($help['id'])); ?></div>
                                </div>
                                <hr class="bg-secondary">
                                <div class="d-flex justify-content-around mt-3">
                                    <?php if (isset($_SESSION['auth_id'])):
                                        $help_likes = getHelpLikes($help['id']);
                                        if (empty($help_likes)): ?>
                                            <div class="d-flex">
                                                <a href="assets/addhelplike.php?s=1&id=<?php echo $help['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
                                            </div>
                                        <?php else:
                                            foreach ($help_likes as $help_like):
                                                if ($help_like['user_id'] == $_SESSION['auth_id']): ?>
                                                    <div class="d-flex">
                                                        <a href="assets/delhelplike.php?s=1&id=<?php echo $help['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-info"></i> Pertinent</a>
                                                    </div>
                                                    <?php break;
                                                elseif (end($help_likes) == $help_like): ?>
                                                    <div class="d-flex">
                                                        <a href="assets/addhelplike.php?s=1&id=<?php echo $help['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
                                                    </div>
                                                <?php endif;
                                            endforeach;
                                        endif;
                                    else: ?>
                                        <div class="d-flex">
                                            <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="d-flex">
                                        <a href="help.php?id=<?php echo $help['id'] ?>" class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Répondre</a>
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
                                    <?php
                                    $help_answer_infos = getHelpComments($help['id']);
                                    $i_answers = 0;
                                    $new_answers = [];

                                    foreach ($help_answer_infos as $help_answer_info):
                                        $new_answers[] = ["id" => $help_answer_info["id"], "help_id" => $help_answer_info["help_id"], "author_id" => $help_answer_info["author_id"]];
                                        $help_answer_ratio = (count(getHelpAnswerLikes($help_answer_info['id'])) - count(getHelpAnswerDislikes($help_answer_info['id'])));
                                        $new_answers[$i_answers]["ratio"] = "$help_answer_ratio";
                                        $i_answers++;
                                    endforeach;

                                    $ratio_column = array_column($new_answers, 'ratio');
                                    array_multisort($ratio_column, SORT_DESC, $new_answers);
                                    $help_best_answer = array_slice($new_answers, 0, 1);

                                    foreach ($help_answer_infos as $help_answer_info):
                                        if ($help_answer_info['id'] == $help_best_answer[0]['id']): ?>
                                            <div class="card-body" id="ContentPosts">
                                                <?php if (isset($_SESSION['auth_id'])):
                                                    if ($help_answer_info['author_id'] == $_SESSION['auth_id']): ?>
                                                        <div class="d-flex flex-column align-items-end" id="deleteCommentBlock" style="border-radius: 10px;" title="Options du commentaire">
                                                            <button class="border-0 dropdownButtonPosts"><i class="fas fa-chevron-down"></i></button>
                                                            <div class="card d-none text-center position-relative border-0">
                                                                <a href="assets/delhelpcomment.php?id=<?php echo $help_answer_info['id'] ?>&s=1" class="btn btn-outline-danger card-body px-2 py-0">Supprimer <i class="fas fa-trash-alt text-danger"></i></a>
                                                            </div>
                                                        </div>
                                                    <?php endif;
                                                endif; ?>
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <img src="https://www.gravatar.com/avatar/<?php echo md5($help_answer_info['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                        <div class="d-flex">
                                                            <h6 class="card-title"><?php echo $help_answer_info['first_name'] . " " . $help_answer_info['last_name'] ?></h6>
                                                            <div class="d-flex justify-content-between ml-1" style="margin-top: -4px;">
                                                                <?php $user_badges = getUserBadges($help_answer_info['author_id']);
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
                                                        <h6 class="card-subtitle mb-2 text-muted text-left"><?php echo "Il y à " . getDateForHumans($help_answer_info['date_added']); ?></h6>
                                                    </div>
                                                    <div class="mt-auto mb-4">
                                                        <div class="d-flex">
                                                            <i class="fas fa-lightbulb mt-1 text-info"></i>
                                                            <div class="ml-2" title="<?php echo count(getHelpAnswerLikes($help_answer_info['id'])) .
                                                                " personnes ont trouvée(s) cette réponse utile, " . count(getHelpAnswerDislikes($help_answer_info['id'])) . " autre(s) non." ?>">
                                                                <?php echo count(getHelpAnswerLikes($help_answer_info['id'])) . " / " . count(getHelpAnswerDislikes($help_answer_info['id'])) ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="card-text text-muted"><?php echo $help_answer_info['content'] ?></p>
                                                <hr class="bg-secondary">
                                                <div class="d-flex justify-content-around mt-3">
                                                    <?php if (isset($_SESSION['auth_id'])):
                                                        $help_comment_likes = getHelpAnswerLikes($help_answer_info['id']);
                                                        $help_comment_dislikes = getHelpAnswerDislikes($help_answer_info['id']);
                                                        if (empty($help_comment_likes)): ?>
                                                            <div class="d-flex">
                                                                <a href="assets/addhelpcommentlike.php?s=1&id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-success"></i> Utile</a>
                                                            </div>
                                                        <?php else:
                                                            foreach ($help_comment_likes as $help_comment_like):
                                                                if ($help_comment_like['user_id'] == $_SESSION['auth_id']): ?>
                                                                    <div class="d-flex">
                                                                        <a href="assets/delhelpcommentlike.php?s=1&id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-success"></i> Voté utile</a>
                                                                    </div>
                                                                    <?php break;
                                                                elseif (end($help_comment_likes) == $help_comment_like): ?>
                                                                    <div class="d-flex">
                                                                        <a href="assets/addhelpcommentlike.php?s=1&id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-success"></i> Utile</a>
                                                                    </div>
                                                                <?php endif;
                                                            endforeach;
                                                        endif;
                                                        if (empty($help_comment_dislikes)): ?>
                                                            <div class="d-flex">
                                                                <a href="assets/addhelpcommentdislike.php?s=1&id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-danger"></i> Pas utile</a>
                                                            </div>
                                                        <?php else:
                                                            foreach ($help_comment_dislikes as $help_comment_dislike):
                                                                if ($help_comment_dislike['user_id'] == $_SESSION['auth_id']): ?>
                                                                    <div class="d-flex">
                                                                        <a href="assets/delhelpcommentdislike.php?s=1&id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-danger"></i> Voté inutile</a>
                                                                    </div>
                                                                    <?php break;
                                                                elseif (end($help_comment_dislikes) == $help_comment_dislike): ?>
                                                                    <div class="d-flex">
                                                                        <a href="assets/addhelpcommentdislike.php?s=1&id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-danger"></i> Inutile</a>
                                                                    </div>
                                                                <?php endif;
                                                            endforeach;
                                                        endif;
                                                    else: ?>
                                                        <div class="d-flex">
                                                            <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-success"></i> Pertinent</a>
                                                        </div>
                                                        <div class="d-flex">
                                                            <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-danger"></i> Non Pertinent</a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="d-flex">
                                                        <a href="help.php?id=<?php echo $help['id'] ?>" class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Répondre</a>
                                                    </div>
                                                    <div class="d-flex">
                                                        <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-3 mt-4 p-0 text-center">
                <div class="btn btn-secondary" id="btnSort">Trier</div>
                <?php if ($query_school OR $query_city): ?>
                    <a href="index.php" class="btn btn-danger d-block col-5 mx-auto my-3">Annuler le tri</a>
                <?php endif; ?>
                <div class="border col-8 mx-auto mt-2 d-none" id="SortForm" style="border-radius: 10px;">
                    <form method="post" action="assets/indexsort.php">
                        <div class="form-group">
                            <label for="content" class="d-block mt-2 h6 font-weight-bold">Ville</label>
                            <div class="d-flex flex-wrap">
                                <?php foreach ($cities as $city): ?>
                                    <div class="form-check w-50">
                                        <input class="form-check-input" type="radio" name="city" id="city" value="<?php echo $city['id'] ?>">
                                        <label class="form-check-label" for="city"><?php echo $city['name'] ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <hr class="bg-secondary">
                        <div class="form-group">
                            <label for="content" class="d-block mt-2 h6 font-weight-bold">École</label>
                            <?php foreach ($schools as $school): ?>
                                <div class="form-check mx-0">
                                    <input class="form-check-input" type="radio" name="school" id="school" value="<?php echo $school['id'] ?>">
                                    <label class="form-check-label" for="school"><?php echo $school['name'] ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (isset($_SESSION['auth_id'])): ?>
                            <button class="btn btn-outline-info my-2">Rechercher</button>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline-info my-2">Rechercher</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
