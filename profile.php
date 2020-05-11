<?php
require_once 'includes/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = getUser($id);
$owned_events = getOwnedEvents($id);
$user_posts = getUserPosts($id);
$user_helps = getUserHelps($id);

if ($user['email'] == NULL) {
    $pathError =  "/mse/404.php";
    header('Location: '. $pathError);
}

$user_school = getUserSchool($id);

if (isset($_SESSION['auth_id'])) {
    $friend = checkFriend($_SESSION['auth_id'], $id);
}

?>


    <section class="section-up"></section>
    <script>document.getElementById('header').style.display = "none";</script>
    <div class="section-flex">
    <section class="menu-profile" style="width: 17%">
        <div class=" col-2 m-0 p-0 bg-dark d-flex flex-column justify-content-between position-fixed" style="width: 230px; height: 100vh; bottom: 0;">
            <div>
                <a href="index.php" class="text-white nav-link border py-3 mt-2 border-left-0">Acceuil</a>
                <?php if (!isset($_SESSION['auth_id'])) { ?>
                    <a href="login.php" class="text-white nav-link border py-3 mt-5 border-left-0">Se connecter</a>
                    <a href="login.php" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                    <a href="login.php" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                <?php }
                else { ?>
                    <a href="profile.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 mt-5 border-left-0">Mon profil</a>
                    <a href="calendar.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border-left-0">Calendrier</a>
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

    </section>
    <section class="section-down">
        <div class="m-button-menu">
            <button class="" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <i class="fas fa-bars  text-white"></i>
            </button>

            <div class="collapse" id="collapseExample">

                    <div class=" m-0 p-0 bg-dark d-flex flex-column justify-content-between position-fixed" style="width: 100vw; height: 100vh; bottom: 0;">
                        <div>
                            <button class="" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fas fa-bars text-white turn"></i>
                            </button>
                            <a href="index.php" class="text-white nav-link border py-3 mt-2 border-left-0">Acceuil</a>
                            <?php if (!isset($_SESSION['auth_id'])) { ?>
                                <a href="login.php" class="text-white nav-link border py-3 mt-5 border-left-0">Se connecter</a>
                                <a href="login.php" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                                <a href="login.php" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                            <?php }
                            else { ?>
                                <a href="profile.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 mt-5 border-left-0">Mon profil</a>
                                <a href="calendar.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border-left-0">Calendrier</a>
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


            </div>
        </div>
        <div class="container d-flex align-content-center information-profile">
            <div class="">
                <div class="text-center">
                    <img src="https://www.gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=600" alt="" class="d-profile-picture d-block shadow border border-white rounded-circle ">
                    <strong class="small color-text-white">Inscrit <?php echo getDateForHumans($user['date_added']); ?></strong>
                    <div class="container">
                        <?php if (isset($_SESSION['auth_id'])) {
                            if ($id == $_SESSION['auth_id']) { ?>
                                <a href="edituser.php?id=<?php echo $id ?>" class="">Editer le profile</a>
                            <?php }
                        } ?>
                    </div>
                    <div class="">
                    <?php if (isset($_SESSION['auth_id'])): ?>
                        <?php if ($_SESSION['auth_id'] != $id): ?>
                            <?php if (!$friend): ?>
                                <a href="assets/friends.php?s=0&id=<?php echo $id ?>" class="btn btn-success">Ajouter en ami</a>
                            <?php else: ?>
                                <?php if ($friend['pending'] === '2'): ?>
                                    <div class="d-flex mb-2">
                                        <div class="btn bg-success">Déja Amis</div>
                                        <div class="ml-4"><a href="assets/friends.php?s=2&id=<?php echo $id ?>" class="btn btn-danger">Supprimer l'ami</a></div>
                                    </div>
                                    <span>(Ajouté en ami <?php echo getDateForHumans($friend['date_added']); ?>)</span>
                                <?php else: ?>
                                    <?php if ($friend['user1_id'] === $_SESSION['auth_id']): ?>
                                        <div class="btn bg-info text-white">Demande Envoyée</div>
                                    <?php elseif ($friend['user2_id'] === $_SESSION['auth_id']): ?>
                                        <a href="assets/friends.php?s=1&id=<?php echo $id ?>" class="btn btn-success">Accepter la demande</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-success">Ajouter en ami</a>
                    <?php endif; ?>
                    </div>
                </div>
                <div class="">
                    <div class=" text-center">

                        <h1 class="font-size-name-profile color-text-white" ><?php echo $user['first_name'] . " " . $user['last_name'] ?></h1>

                    <!--</div>
                    <div class="mt-5 h3 text-muted">Événements gérés :</div>
                    <div>-->
                        <?php foreach ($owned_events as $owned_event) { ?>
                            <a class="nav-link card col-3 p-3 text-center mt-5 d-flex" href="event.php?id=<?php echo $owned_event['id'] ?>"><?php echo $owned_event['name'] ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-2 pb-5">
            <span class="fs-1vw fs-info-profile pr-2 border-right d-block color-text-white"><?php echo $user['school_year'] ?>°</span>
            <span class="fs-1vw fs-info-profile pr-2 pl-2  border-right d-block color-text-white"><?php echo $user_school['name'] ?></span>
            <span class="fs-1vw fs-info-profile pl-2 d-block color-text-white"><?php echo $user['email'] ?></span>
        </div>
    </section>
    <section class="section-feed align-content-center">
        <div class="button-choice">
            <div class="d-flex ">
                <div class="col-3  text-center">
                    <a href="#" class="h5 nav-link text-white mt-3" id="SortMenuAll">Tout</a>
                    <div class="bg-white mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                </div>
                <div class="col-3 text-center">
                    <a href="#" class="h5 nav-link text-white mt-5" id="SortMenuPosts">Posts</a>
                    <div class="bg-white mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                </div>
                <div class="col-3 text-center">
                    <a href="#" class="h5 nav-link text-white mt-5" id="SortMenuEvents">Événements</a>
                    <div class="bg-white mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                </div>
                <div class="col-3 text-center">
                    <a href="#" class="h5 nav-link text-white mt-5" id="SortMenuHelps">Helps</a>
                    <div class="bg-white mt-2 mx-auto" style="width: 60%; height: 6px; border-radius: 10px;"></div>
                </div>
            </div>
    </section>




<?php require_once 'includes/footer.php'; ?>
