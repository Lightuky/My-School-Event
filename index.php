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
                                    <textarea class="form-control mt-1" name="content" rows="2"></textarea>
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
                                    <div class="ml-2"><?php echo count(getPostComments($post['id'])); ?></div>
                                </div>
                                <hr class="bg-secondary">
                                <div class="d-flex justify-content-around mt-3">
                                    <div class="d-flex">
                                        <i class="far fa-star mt-1 text-muted"></i>
                                        <a href="#" class="card-link ml-2 text-muted">Favori</a>
                                    </div>
                                    <div class="d-flex">
                                        <i class="far fa-comment-alt mt-1 text-muted"></i>
                                        <a href="#" class="card-link ml-2 text-muted">Commenter</a>
                                    </div>
                                    <div class="d-flex">
                                        <i class="fas fa-share mt-1 text-muted"></i>
                                        <a href="#" class="card-link ml-2 text-muted">Partager</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div id="allEvents">
                    <?php foreach ($events as $event){ ?>
                        <div class="card col-10 mx-auto mt-4" id="ContentPosts">
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
                                    <div class="ml-2"><?php echo count(getEventComments($event['id'])); ?></div>
                                </div>
                                <hr class="bg-secondary">
                                <div class="d-flex justify-content-around mt-3">
                                    <div class="d-flex">
                                        <i class="far fa-check-circle mt-1 text-muted"></i>
                                        <a href="event.php?id=<?php echo $event['id'] ?>" class="card-link ml-2 text-muted">Participer</a>
                                    </div>
                                    <div class="d-flex">
                                        <i class="far fa-comment-alt mt-1 text-muted"></i>
                                        <a href="#" class="card-link ml-2 text-muted">Commenter</a>
                                    </div>
                                    <div class="d-flex">
                                        <i class="fas fa-share mt-1 text-muted"></i>
                                        <a href="#" class="card-link ml-2 text-muted">Partager</a>
                                    </div>
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
                            <form method="post" action="assets/addpost.php">
                                <div class="form-group">
                                    <label for="title">Titre</label>
                                    <input type="text" name="title" placeholder="Comment faire une ancre en HTML ?" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="content">Description</label>
                                    <textarea class="form-control mt-1" name="content" rows="2"></textarea>
                                </div>
                                <?php if (isset($_SESSION['auth_id'])) { ?>
                                    <button class="btn btn-outline-info my-2">Poster</button>
                                <?php } else { ?>
                                    <a href="login.php" class="btn btn-outline-info my-2">Poster</a>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
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
