<?php
require_once 'includes/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = getUser($id);
$owned_events = getOwnedEvents($id);

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

    <div class="section-flex">
    <section class="section-down">
        <div class="container d-flex align-content-center">
            <div class="">
                <div class="text-center">
                    <img src="https://www.gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=600" alt="" class="d-profile-picture m-auto d-block shadow border border-white rounded-circle w-25">
                    <strong class="small">Inscrit <?php echo getDateForHumans($user['date_added']); ?></strong>
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

                        <h1 class="font-size-name-profile" ><?php echo $user['first_name'] . " " . $user['last_name'] ?></h1>

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
            <span class="fs-info-profile pr-2 border-right d-block"><?php echo $user['school_year'] ?>°</span>
            <span class="fs-info-profile pr-2 pl-2  border-right d-block"><?php echo $user_school['name'] ?></span>
            <span class="fs-info-profile pl-2 d-block"><?php echo $user['email'] ?></span>
        </div>
    </section>
    <section class="section-feed"></section>
    <div>

<?php require_once 'includes/footer.php'; ?>