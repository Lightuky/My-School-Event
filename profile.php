<?php
require_once 'includes/header.php';
use Carbon\Carbon;
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.UTF8');

$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = getUser($id);
$is_brand = isBrand($id);
$user_badges = getUserBadges($id);

$events = getOwnedEvents($id);
$posts = getUserPosts($id);
$helps = getUserHelps($id);

$all_contents = [];

foreach ($events as $event): $event['type'] = "event"; array_push($all_contents,$event); endforeach;
foreach ($posts as $post): $post['type'] = "post"; array_push($all_contents,$post); endforeach;
foreach ($helps as $help): $help['type'] = "help"; array_push($all_contents,$help); endforeach;

$all_contents_date = array_column($all_contents, 'date_added');
array_multisort($all_contents_date, SORT_DESC, $all_contents);

if ($user['email'] == NULL):
    $pathError =  "/mse/404.php";
    header('Location: '. $pathError);
endif;

$user_school = getUserSchool($id);

if (isset($_SESSION['auth_id'])):
    $friend = checkFriend($_SESSION['auth_id'], $id);
endif;

?>
<section class="section-up profile" id="section-up"></section>
<div class="section-flex">
    <section class="menu-profile" style="width: 17%">
        <div class=" col-2 m-0 p-0 bg-dark d-flex flex-column justify-content-between position-fixed" style="width: 230px; height: 100vh; bottom: 0;">
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
    </section>
    <section class="section-down">
        <div class="m-button-menu">
            <button class="" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <i class="fas fa-bars  text-white"></i>
            </button>
            <div class="collapse" id="collapseExample">
                <div class=" m-0 p-0 bg-dark d-flex flex-column justify-content-between position-fixed" style="width: 100vw; height: 100vh; bottom: 0; z-index: 10;">
                    <div>
                        <button class="" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fas fa-bars text-white turn"></i>
                        </button>
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
            </div>
        </div>
        <div class="container d-flex align-content-center information-profile">
            <div class="">
                <div class="text-center">
                    <img src="https://www.gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=600" alt="" class="d-profile-picture d-block shadow border border-white rounded-circle ">
                    <strong class="small color-text-white">Inscrit <?php echo getDateForHumans($user['date_added']); ?></strong>
                    <div class="container">
                        <?php if (isset($_SESSION['auth_id'])):
                            if ($id == $_SESSION['auth_id']): ?>
                                <a href="edituser.php?id=<?php echo $id ?>" class="nav-link">Editer le profile</a>
                            <?php endif;
                        endif; ?>
                    </div>
                    <div>
                        <?php if (!$is_brand):
                            if (isset($_SESSION['auth_id'])):
                                if ($_SESSION['auth_id'] != $id):
                                    if (!$friend): ?>
                                        <a href="assets/friends.php?s=0&id=<?php echo $id ?>" class="btn btn-success">Ajouter en ami</a>
                                    <?php else:
                                        if ($friend['pending'] === '2'): ?>
                                            <div class="d-flex mb-2">
                                                <div class="btn bg-success">Déja Amis</div>
                                                <div class="ml-4"><a href="assets/friends.php?s=2&id=<?php echo $id ?>" class="btn btn-danger">Supprimer l'ami</a></div>
                                            </div>
                                            <span>(Ajouté en ami il y à <?php echo getDateForHumans($friend['date_added']); ?>)</span>
                                        <?php else:
                                            if ($friend['user1_id'] === $_SESSION['auth_id']): ?>
                                                <div class="btn bg-info text-white">Demande Envoyée</div>
                                            <?php elseif ($friend['user2_id'] === $_SESSION['auth_id']): ?>
                                                <a href="assets/friends.php?s=1&id=<?php echo $id ?>" class="btn btn-success">Accepter la demande</a>
                                            <?php endif;
                                        endif;
                                    endif;
                                endif;
                            else: ?>
                                <a href="login.php" class="btn btn-success">Ajouter en ami</a>
                            <?php endif;
                        endif; ?>
                    </div>
                </div>
                <div class="">
                    <div class=" text-center">
                        <?php if (!$is_brand): ?>
                            <h1 class="font-size-name-profile color-text-white" ><?php echo $user['first_name'] . " " . $user['last_name'] ?></h1>
                        <?php else: ?>
                            <h1 class="font-size-name-profile color-text-white" ><?php echo $is_brand['brand_name'] ?></h1>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-2 pb-4">
            <?php if (!$is_brand): ?>
                <span class="fs-1vw fs-info-profile pr-2 border-right d-block color-text-white"><?php echo $user['school_year'] ?>°</span>
                <span class="fs-1vw fs-info-profile pr-2 pl-2  border-right d-block color-text-white"><?php echo $user_school['name'] ?></span>
                <span class="fs-1vw fs-info-profile pl-2 d-block color-text-white"><?php echo $user['email'] ?></span>
            <?php else: ?>
                <a href="mailto:<?php echo $is_brand['contact_email'] ?>" class="nav-link fs-1vw fs-info-profile pr-3 border-right d-block color-text-white"><?php echo $is_brand['contact_email'] ?></a>
                <a href="//<?php echo $is_brand['website_url'] ?>" class="nav-link fs-1vw fs-info-profile pr-2 pl-3  border-right d-block color-text-white"><?php echo $is_brand['website_url'] ?></a>
            <?php endif; ?>
        </div>
        <?php if ($is_brand): ?>
            <div class="text-center"><span class="badge badge-info">Compte Sponsorisé</span></div>
        <?php endif; ?>
        <div class="d-flex justify-content-between flex-wrap pr-4">
            <?php foreach ($user_badges as $user_badge): ?>
                <div class="w-25 my-2 text-center" style="font-size: 0.7rem;">
                    <span class="fa-stack fa-2x mx-auto" title="<?php echo $user_badge['name'] . " : " . $user_badge['description'] . "\n" . "Obtenu le : " . date('d/m/Y', strtotime($user_badge['date_added'])) ?>">
                        <i class="fas fa-certificate fa-stack-2x" style="color: <?php echo $user_badge['color'] ?>"></i>
                        <i class="fab <?php echo $user_badge['icon'] ?> fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="section-feed align-content-center">
        <div class="button-choice">
            <div class="d-flex ">
                <div class="col-3  text-center">
                    <button class="h5 nav-link text-white bg-transparent mt-3 border-0 mx-auto" id="SortMenuAll">Tout</button>
                    <div class="bg-white mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                </div>
                <div class="col-3 text-center">
                    <button class="h5 nav-link text-white bg-transparent mt-5 border-0 mx-auto" id="SortMenuPosts">Posts</button>
                    <div class="bg-white mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                </div>
                <div class="col-3 text-center">
                    <button class="h5 nav-link text-white bg-transparent mt-5 border-0 mx-auto" id="SortMenuEvents">Events</button>
                    <div class="bg-white mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                </div>
                <div class="col-3 text-center">
                    <button class="h5 nav-link text-white bg-transparent mt-5 border-0 mx-auto" id="SortMenuHelps">Helps</button>
                    <div class="bg-white mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                </div>
            </div>
        </div>
        <div id="allContents">
            <?php if($_SESSION['auth_id'] == $id): ?>
                <div class="card col-10 my-5 mx-auto allContentPostForm" id="ContentPosts" style="border-radius: initial">
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
                <div class="card col-10 my-5 mx-auto allContentEventForm" id="ContentPosts" style=" border-radius: initial; display: none">
                    <div class="card-header text-center">
                        Ajouter un événement
                    </div>
                    <div class="card-body mt-2 p-1 text-center">
                        <?php if (!isset($_SESSION['auth_id'])): ?>
                            <a href="login.php" class="btn btn-outline-info">Ajouter un event</a>
                        <?php else: ?>
                            <a href="addevent.php" class="btn btn-outline-info">Ajouter un event</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card col-10 my-5 mx-auto allContentHelpForm" id="ContentPosts" style="display: none; border-radius: initial">
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
            <?php endif; ?>
            <?php foreach ($all_contents as $all_content):
                if ($all_content['type'] == "post"):
                    $attachments = getPostAttachments($all_content['id']); ?>
                    <div class="card col-10 mx-auto mt-5 allContentPost" id="ContentPosts">
                        <div class="card-body">
                            <img src="https://www.gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                            <h5 class="card-title">
                                <a href="profile.php?id=<?php echo $id ?>" style="text-decoration: none; color: black">
                                    <?php echo !$is_brand ? $user['first_name'] . " " . $user['last_name'] : $is_brand['brand_name'] ?>
                                </a>
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($all_content['date_added']); ?></h6>
                            <p class="card-text text-muted"><?php echo $all_content['content'] ?></p>
                            <?php if ($attachments):
                                if ($attachments['type'] == "imgur"): ?>
                                    <div class="ml-5">
                                        <blockquote class="imgur-embed-pub" lang="en" data-id="<?php echo 'a/' . $attachments['hash'] ?>" data-context="false">
                                            <a href="<?php echo '//imgur.com/a/' . $attachments['hash'] ?>"></a>
                                        </blockquote>
                                        <script async src="//s.imgur.com/min/embed.js" charset="utf-8"></script>
                                    </div>
                                <?php elseif ($attachments['type'] == "youtube"): ?>
                                    <div style="margin-left: 15%">
                                        <iframe
                                                width="476" height="268" src="<?php echo 'https://www.youtube.com/embed/' . $attachments['hash'] ?>" frameborder="0"
                                                allow="accelerometer;autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="border-radius: 10px;">
                                        </iframe>
                                    </div>
                                <?php endif;
                            endif; ?>
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-self-center">
                                    <i class="fas fa-star mt-1" style="color: gold"></i>
                                    <div class="ml-2"><?php echo count(getPostLikes($all_content['id'])); ?></div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <?php if (empty(getPostComments($all_content['id']))): ?>
                                        <div class="btn btn-light bg-white text-secondary border-0 m-0 pr-1">Aucun commentaire</div>
                                    <?php else: ?>
                                        <button class="btn btn-light bg-white text-secondary border-0 m-0 pr-1 ShowComments"><?php echo count(getPostComments($all_content['id'])) ?> commentaire(s)</button>
                                        <div class="btn btn-light bg-white text-secondary border-0 m-0 pr-1 d-none HideComments">Masquer les commentaires</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr class="bg-secondary">
                            <div class="d-flex justify-content-around mt-3">
                                <?php if (isset($_SESSION['auth_id'])) {
                                    $post_likes = getPostLikes($all_content['id']);
                                    if (empty($post_likes)) { ?>
                                        <div class="d-flex">
                                            <a href="assets/addpostlike.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-star mt-1 text-muted"></i> Favori</a>
                                        </div>
                                    <?php }
                                    else {
                                        foreach ($post_likes as $post_like) {
                                            if ($post_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                <div class="d-flex">
                                                    <a href="assets/delpostlike.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-star mt-1 text-warning"></i> Favori</a>
                                                </div>
                                                <?php break;
                                            }
                                            elseif (end($post_likes) == $post_like) { ?>
                                                <div class="d-flex">
                                                    <a href="assets/addpostlike.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-star mt-1 text-muted"></i> Favori</a>
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
                                    <form method="post" action="assets/addpostcomment.php?id=<?php echo $all_content['id'] ?>">
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
                            <div class="d-none ContentsComments">
                                <?php $post_comments = getPostComments($all_content['id']);
                                foreach ($post_comments as $post_comment) { ?>
                                    <div class="card-body" id="ContentPosts">
                                        <?php if (isset($_SESSION['auth_id'])):
                                            if ($post_comment['author_id'] == $_SESSION['auth_id']): ?>
                                                <div class="d-flex flex-column align-items-end" id="deleteCommentBlock" style="border-radius: 10px;" title="Options du commentaire">
                                                    <button class="border-0 dropdownButtonPosts"><i class="fas fa-chevron-down"></i></button>
                                                    <div class="card d-none text-center position-relative border-0">
                                                        <a href="assets/delpostcomment.php?id=<?php echo $post_comment['id'] ?>&s=2&p=<?php echo $id ?>" class="btn btn-outline-danger card-body px-2 py-0">Supprimer <i class="fas fa-trash-alt text-danger"></i></a>
                                                    </div>
                                                </div>
                                            <?php endif;
                                        endif; ?>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <img src="https://www.gravatar.com/avatar/<?php echo md5($post_comment['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                <h6 class="card-title text-left ml-5">
                                                    <a href="profile.php?id=<?php echo $post_comment['author_id'] ?>" style="text-decoration: none; color: black">
                                                        <?php echo $post_comment['first_name'] . " " . $post_comment['last_name'] ?>
                                                    </a>
                                                </h6>
                                                <h6 class="card-subtitle mb-2 text-muted text-left ml-5"><?php echo "Il y à " . getDateForHumans($post_comment['date_added']); ?></h6>
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
                                                <?php }
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
                <?php elseif ($all_content['type'] == "event"):
                    $current_datetime = date('Y-m-d H:i:s');
                    $event_duration_hours = date('G', strtotime($all_content['duration']));
                    $event_duration_minutes = date('i', strtotime($all_content['duration']));
                    $event_duration_seconds = date('s', strtotime($all_content['duration']));
                    $event_start_date = date('Y-m-d H:i:s', strtotime($all_content['date'] . $all_content['time']));
                    $event_end_date = date('Y-m-d H:i:s',strtotime("+" . $event_duration_hours . " hour +" . $event_duration_minutes . " minutes +" . $event_duration_seconds . " seconds",strtotime($event_start_date)));
                    ?>
                    <div class="card col-10 mx-auto mt-5 allContentEvent" id="ContentPosts">
                        <div class="card-body">
                            <img src="https://www.gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                            <h5 class="card-title">
                                <a href="profile.php?id=<?php echo $all_content['admin_id'] ?>" style="text-decoration: none; color: black">
                                    <?php echo $user['first_name'] . " " . $user['last_name'] ?>
                                </a>
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($all_content['date_added']); ?></h6>
                            <p class="h5 font-weight-bold"><?php echo $all_content['name'] ?></p>
                            <p class="card-text text-muted"><?php echo $all_content['description'] ?></p>
                            <p class="card-text mb-1"><?php echo "Le " . strftime("%A %e %B", strtotime($all_content['date'])) . " à " . strftime("%Hh%M", strtotime($all_content['time'])) ?></p>
                            <div class="d-flex">
                                <i class="fas fa-map-marker-alt mt-1" style="color: red"></i>
                                <?php $event_address = getEventAddress($all_content['id']) ?>
                                <p class="card-text text-muted ml-2"><?php echo $event_address['street_number'] . " " . $event_address['address_line1'] . ", "
                                        . $event_address['address_line2'] . " "  . $event_address['zip_code'] . " " . $event_address['city'] ?></p>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="d-flex align-self-center">
                                    <i class="fas fa-check-circle mt-1" style="color: forestgreen"></i>
                                    <div class="ml-2"><?php echo count(getEventMembers($all_content['id'])); ?></div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <?php if (empty(getEventComments($all_content['id']))): ?>
                                        <div class="btn btn-light bg-white text-secondary border-0 m-0 pr-1">Aucun commentaire</div>
                                    <?php else: ?>
                                        <button class="btn btn-light bg-white text-secondary border-0 m-0 pr-1 ShowComments"><?php echo count(getEventComments($all_content['id'])) ?> commentaire(s)</button>
                                        <div class="btn btn-light bg-white text-secondary border-0 m-0 pr-1 d-none HideComments">Masquer les commentaires</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr class="bg-secondary">
                            <div class="d-flex justify-content-around mt-3">
                                <?php if ($event_end_date < $current_datetime): ?>
                                    <div class="d-flex">
                                        <a href="event.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-clock mt-1 text-warning"></i> Terminé</a>
                                    </div>
                                <?php else: ?>
                                    <?php if (isset($_SESSION['auth_id'])):
                                        if ($all_content['admin_id'] == $_SESSION['auth_id']): ?>
                                            <div class="d-flex">
                                                <a href="event.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-success"></i> Rejoint</a>
                                            </div>
                                        <?php else:
                                            $event_state = checkEventState($all_content['id'], $_SESSION['auth_id']);
                                            if (empty($event_state)): ?>
                                                <div class="d-flex">
                                                    <a href="event.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-muted"></i> Participer</a>
                                                </div>
                                            <?php else:
                                                if ($event_state['private_pending'] == "1"): ?>
                                                    <div class="d-flex">
                                                        <a href="event.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-warning"></i> Demande Envoyée</a>
                                                    </div>
                                                <?php elseif ($event_state['private_pending'] == "0"): ?>
                                                    <div class="d-flex">
                                                        <a href="event.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-check-circle mt-1 text-success"></i> Rejoint</a>
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
                                    <form method="post" action="assets/addeventcomment.php?id=<?php echo $all_content['id'] ?>">
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
                            <div class="d-none ContentsComments">
                                <?php $event_comments = getEventComments($all_content['id']);
                                foreach ($event_comments as $event_comment) { ?>
                                    <div class="card-body" id="ContentPosts">
                                        <?php if (isset($_SESSION['auth_id'])):
                                            if ($event_comment['author_id'] == $_SESSION['auth_id']): ?>
                                                <div class="d-flex flex-column align-items-end" id="deleteCommentBlock" style="border-radius: 10px;" title="Options du commentaire">
                                                    <button class="border-0 dropdownButtonPosts"><i class="fas fa-chevron-down"></i></button>
                                                    <div class="card d-none text-center position-relative border-0">
                                                        <a href="assets/deleventcomment.php?id=<?php echo $event_comment['id'] ?>&s=2&p=<?php echo $id ?>" class="btn btn-outline-danger card-body px-2 py-0">Supprimer <i class="fas fa-trash-alt text-danger"></i></a>
                                                    </div>
                                                </div>
                                            <?php endif;
                                        endif; ?>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <img src="https://www.gravatar.com/avatar/<?php echo md5($event_comment['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                <h6 class="card-title text-left ml-5">
                                                    <a href="profile.php?id=<?php echo $event_comment['author_id'] ?>" style="text-decoration: none; color: black">
                                                        <?php echo $event_comment['first_name'] . " " . $event_comment['last_name'] ?>
                                                    </a>
                                                </h6>
                                                <h6 class="card-subtitle mb-2 text-muted text-left ml-5"><?php echo "Il y à " . getDateForHumans($event_comment['date_added']); ?></h6>
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
                <?php elseif ($all_content['type'] == "help"): ?>
                    <div class="card col-10 mx-auto mt-5 allContentHelp" id="ContentPosts">
                        <div class="card-body">
                            <img src="https://www.gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                            <h5 class="card-title">
                                <a href="profile.php?id=<?php echo $all_content['author_id'] ?>" style="text-decoration: none; color: black">
                                    <?php echo $user['first_name'] . " " . $user['last_name'] ?>
                                </a>
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($all_content['date_added']); ?></h6>
                            <p class="card-text text-secondary font-weight-bold mb-0 mt-3"><?php echo $all_content['title'] ?></p>
                            <p class="card-text text-muted"><?php echo $all_content['content'] ?></p>
                            <div class="d-flex">
                                <i class="fas fa-lightbulb mt-1 text-info"></i>
                                <div class="ml-2"><?php echo count(getHelpLikes($all_content['id'])); ?></div>
                            </div>
                            <hr class="bg-secondary">
                            <div class="d-flex justify-content-around mt-3">
                                <?php if (isset($_SESSION['auth_id'])) {
                                    $help_likes = getHelpLikes($all_content['id']);
                                    if (empty($help_likes)) { ?>
                                        <div class="d-flex">
                                            <a href="assets/addhelplike.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
                                        </div>
                                    <?php }
                                    else {
                                        foreach ($help_likes as $help_like) {
                                            if ($help_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                <div class="d-flex">
                                                    <a href="assets/delhelplike.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-info"></i> Pertinent</a>
                                                </div>
                                                <?php break;
                                            }
                                            elseif (end($help_likes) == $help_like) { ?>
                                                <div class="d-flex">
                                                    <a href="assets/addhelplike.php?id=<?php echo $all_content['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
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
                                    <a href="help.php?id=<?php echo $all_content['id'] ?>" class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Répondre</a>
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
                                $help_answer_infos = getHelpComments($all_content['id']);
                                $i_answers = 0;
                                $new_answers = [];

                                foreach ($help_answer_infos as $help_answer_info) {
                                    $new_answers[] = ["id" => $help_answer_info["id"], "help_id" => $help_answer_info["help_id"], "author_id" => $help_answer_info["author_id"]];
                                    $help_answer_ratio = (count(getHelpAnswerLikes($help_answer_info['id'])) - count(getHelpAnswerDislikes($help_answer_info['id'])));
                                    $new_answers[$i_answers]["ratio"] = "$help_answer_ratio";
                                    $i_answers++;
                                }
                                $ratio_column = array_column($new_answers, 'ratio');
                                array_multisort($ratio_column, SORT_DESC, $new_answers);
                                $help_best_answer = array_slice($new_answers, 0, 1);

                                foreach ($help_answer_infos as $help_answer_info) {
                                    if ($help_answer_info['id'] == $help_best_answer[0]['id']) { ?>
                                        <div class="card-body" id="ContentPosts">
                                            <?php if (isset($_SESSION['auth_id'])):
                                                if ($help_answer_info['author_id'] == $_SESSION['auth_id']): ?>
                                                    <div class="d-flex flex-column align-items-end" id="deleteCommentBlock" style="border-radius: 10px;" title="Options du commentaire">
                                                        <button class="border-0 dropdownButtonPosts"><i class="fas fa-chevron-down"></i></button>
                                                        <div class="card d-none text-center position-relative border-0">
                                                            <a href="assets/delhelpcomment.php?id=<?php echo $help_answer_info['id'] ?>&s=3&p=<?php echo $id ?>" class="btn btn-outline-danger card-body px-2 py-0">Supprimer <i class="fas fa-trash-alt text-danger"></i></a>
                                                        </div>
                                                    </div>
                                                <?php endif;
                                            endif; ?>
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($help_answer_info['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                    <h6 class="card-title text-left ml-5">
                                                        <a href="profile.php?id=<?php echo $help_answer_info['author_id'] ?>" style="text-decoration: none; color: black">
                                                            <?php echo $help_answer_info['first_name'] . " " . $help_answer_info['last_name'] ?>
                                                        </a>
                                                    </h6>
                                                    <h6 class="card-subtitle mb-2 text-muted text-left ml-5"><?php echo "Il y à " . getDateForHumans($help_answer_info['date_added']); ?></h6>
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
                                                <?php if (isset($_SESSION['auth_id'])) {
                                                    $help_comment_likes = getHelpAnswerLikes($help_answer_info['id']);
                                                    $help_comment_dislikes = getHelpAnswerDislikes($help_answer_info['id']);
                                                    if (empty($help_comment_likes)) { ?>
                                                        <div class="d-flex">
                                                            <a href="assets/addhelpcommentlike.php?id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-success"></i> Utile</a>
                                                        </div>
                                                    <?php }
                                                    else {
                                                        foreach ($help_comment_likes as $help_comment_like) {
                                                            if ($help_comment_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/delhelpcommentlike.php?id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-success"></i> Voté utile</a>
                                                                </div>
                                                                <?php break;
                                                            }
                                                            elseif (end($help_comment_likes) == $help_comment_like) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/addhelpcommentlike.php?id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-success"></i> Utile</a>
                                                                </div>
                                                            <?php }
                                                        }
                                                    }
                                                    if (empty($help_comment_dislikes)) { ?>
                                                        <div class="d-flex">
                                                            <a href="assets/addhelpcommentdislike.php?id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-danger"></i> Pas utile</a>
                                                        </div>
                                                    <?php }
                                                    else {
                                                        foreach ($help_comment_dislikes as $help_comment_dislike) {
                                                            if ($help_comment_dislike['user_id'] == $_SESSION['auth_id']) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/delhelpcommentdislike.php?id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-danger"></i> Voté inutile</a>
                                                                </div>
                                                                <?php break;
                                                            }
                                                            elseif (end($help_comment_dislikes) == $help_comment_dislike) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/addhelpcommentdislike.php?id=<?php echo $help_answer_info['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-danger"></i> Inutile</a>
                                                                </div>
                                                            <?php }
                                                        }
                                                    }
                                                }
                                                else { ?>
                                                    <div class="d-flex">
                                                        <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-success"></i> Pertinent</a>
                                                    </div>
                                                    <div class="d-flex">
                                                        <a href="login.php" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-danger"></i> Non Pertinent</a>
                                                    </div>
                                                <?php } ?>
                                                <div class="d-flex">
                                                    <a href="help.php?id=<?php echo $all_content['id'] ?>" class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Répondre</a>
                                                </div>
                                                <div class="d-flex">
                                                    <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                <?php endif;
            endforeach; ?>
        </div>
    </section>
    
    <?php require_once 'includes/footer.php'; ?>
