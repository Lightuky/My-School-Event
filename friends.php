<?php
require_once 'includes/header.php';
$query = isset($_GET['q']) ? $_GET['q'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = getUser($id);
$friends = getFriends($id);
$schools = getSchools();

if ($user['email'] == NULL) {
    $pathError =  "/mse/404.php";
    header('Location: '. $pathError);
}

?>

<div class="row m-0">

    <section>
    <div class="menu-friends">
    <div class="col-2 m-0 p-0 bg-dark d-flex flex-column justify-content-between position-fixed" style="height: calc(100vh - 60px); bottom: 0;">
        <div>
            <a href="index.php" class="text-white nav-link border py-3 mt-2 border-left-0">Acceuil</a>
            <?php if (!isset($_SESSION['auth_id'])) { ?>
                <a href="login.php" class="text-white nav-link border py-3 mt-5 border-left-0">Se connecter</a>
                <a href="login.php" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                <a href="login.php" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                <a href="login.php" class="text-white nav-link border py-3 border-left-0">Mes amis</a>
            <?php }
            else { ?>
                <a href="profile.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 mt-5 border-left-0">Mon profil</a>
                <a href="calendar.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                <a href="bugreport.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                <a href="friends.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 border-left-0">Mes amis</a>
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
    </section>
    <section>
    <div class="m-button-menu-friends" style="margin-top: 78px;">
        <button class="" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <i class="fas fa-bars  text-black-50"></i>
        </button>

        <div class="collapse" id="collapseExample">

            <div class=" m-0 p-0 bg-dark d-flex flex-column justify-content-between position-fixed" style="width: 100vw; height: 93vh; bottom: 0; z-index: 10;">
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
    </section>
    <section class="feed-friends col-10 p-0 d-flex" style="margin-top: 80px">
        <div class="flex-row m-auto">
            <h1 class="mt-3">Mes amis</h1>
            <form class="form-inline" method="post" action="assets/searchfriends.php">
                <input class="form-control mr-2" type="search" name="search" id="search" aria-label="Search" style="width: 68%;">
                <button class="btn btn-outline-success search-button my-2" type="submit" style="width: 30%;">Rechercher</button>
            </form>
            <?php
            $i_users = 0;
            $friends_credentials = [];

            foreach ($friends as $friend) {
                $friends_credentials[] = ["user1_id" => $friend["user1_id"], "user2_id" => $friend["user2_id"], "pending" => $friend["pending"],
                    "date_added" => $friend["date_added"], "date_edited" => $friend["date_edited"]];
                if ($friend['user1_id'] == $id) {
                    $other_user = getUser($friend['user2_id']);
                }
                elseif ($friend['user2_id'] == $id) {
                    $other_user = getUser($friend['user1_id']);
                }

                $friends_credentials[$i_users]["first_name"] = "" . $other_user['first_name'] . "";
                $friends_credentials[$i_users]["last_name"] = "" . $other_user['last_name'] . "";
                $friends_credentials[$i_users]["email"] = "" . $other_user['email'] . "";
                $friends_credentials[$i_users]["id"] = "" . $other_user['id'] . "";
                ?>

                <?php foreach ($schools as $school) {
                    if ($school['id'] == $other_user['school_id']) {
                        $friends_credentials[$i_users]["school_name"] = "" . $school['name'] . "";
                    }
                }
                $friends_credentials[$i_users]["school_year"] = "" . $other_user['school_year'] . "";
                $i_users++;
            } ?>

            <?php foreach ($friends_credentials as $friends_credential){ ?>


            <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters none-flex-wrap">
                    <div class="col-md-4">
                        <img src="https://www.gravatar.com/avatar/<?php echo md5($friends_credential['email']); ?>?s=700" alt="" class="d-block rounded-circle " style="height: 68%; width: 48%;margin-top: 9%;" id="ContentProfilePics">
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                            <h5 class="card-title">
                                <div>
                                    <div>
                                <a href="profile.php?id=<?php echo $friends_credential['id'] ?>" class="text-black">
                                    <?php echo $friends_credential["first_name"] ?>
                                    <?php echo $friends_credential["last_name"] ?>
                                </a>
                                    </div>
                                <div>
                                <?php echo $friends_credential["school_year"] . "°" ?>
                                <?php echo $friends_credential["school_name"] ?>
                                </div>
                                </div>



                            </h5>
                            <p class="card-text"><small class="text-muted"><?php echo  getDateForHumans($friends_credential['date_added']); ?></small></p>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                        <?php if ($friends_credential['pending'] === '2'): ?>
                            <div class="btn bg-secondary text-white">Vous êtes Amis</div>
                        <?php elseif ($friends_credential['pending'] === '1'): ?>
                        <?php if ( $friends_credential['user2_id'] == $id): ?>
                            <a href="assets/friends.php?s=1&id=<?php echo $id ?>" class="btn btn-success">Accepter la demande</a>
                        <?php else: ?>
                            <div class="btn bg-info text-white">Demande Envoyée</div>
                        <?php endif; ?>
                        <?php endif; ?>

                    </div>
                </div>

            </div>

            <?php   } ?>
        </div>
    </section>

</div>

<?php require_once 'includes/footer.php'; ?>
