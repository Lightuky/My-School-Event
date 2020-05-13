<?php
require_once 'includes/header.php';
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


    <section class="col-10 mx-0 p-0 d-flex ml-auto" style="margin-top: 60px">
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

            foreach ($schools as $school) {
                if ($school['id'] == $other_user['school_id']) {
                    $friends_credentials[$i_users]["school_name"] = "" . $school['name'] . "";
                }
            }

            $friends_credentials[$i_users]["school_year"] = "" . $other_user['school_year'] . "";
            $i_users++;
        } ?>
        <?php var_dump($friends_credentials); ?>
    </section>

</div>

<?php require_once 'includes/footer.php'; ?>
