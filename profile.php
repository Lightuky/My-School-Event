<?php
require_once 'includes/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = getUser($id);

if ($user['email'] == NULL) {
    $pathError =  "/mse/404.php";
    header('Location: '. $pathError);
}

$user_school = getUserSchool($id);

if (isset($_SESSION['auth_id'])) {
    $friend = checkFriend($_SESSION['auth_id'], $id);
}

?>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <img src="https://www.gravatar.com/avatar/<?php echo md5($user['email']); ?>?s=600" alt="" class="d-block w-50">
                    <div class="container mt-3">
                        <?php if (isset($_SESSION['auth_id'])) {
                            if ($id == $_SESSION['auth_id']) { ?>
                                <a href="edituser.php?id=<?php echo $id ?>" class="btn btn-secondary">Editer mes infos</a>
                            <?php }
                        } ?>
                    </div>
                    <div class="mt-3">
                    <?php if (isset($_SESSION['auth_id'])): ?>
                        <?php if ($_SESSION['auth_id'] != $id): ?>
                            <?php if (!$friend): ?>
                                <a href="assets/friends.php?s=0&id=<?php echo $id ?>" class="btn btn-success">Ajouter en ami</a>
                            <?php else: ?>
                                <?php if ($friend['pending'] === '2'): ?>
                                    <div class="d-flex mb-2">
                                        <div class="btn bg-success text-white">Déja Amis</div>
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
                <div class="col-lg-8">
                    <div>
                        <h1><?php echo $user['first_name'] . " " . $user['last_name'] ?></h1>
                        <strong>Inscrit <?php echo getDateForHumans($user['date_added']); ?></strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <span class="text-muted d-block">Adresse Email : <?php echo $user['email'] ?></span>
            <span class="text-muted d-block">N° de téléphone : <?php echo !$user['phone_number'] ? 'Non renseigné' : $user['phone_number']; ?></span>
            <span class="text-muted d-block">Date de naissance : <?php echo !$user['birthday'] ? 'Non renseigné' : $user['birthday']; ?></span>
            <span class="text-muted d-block">Genre : <?php echo $user['gender'] != 1 ? ($user['gender'] != 2 ? "Autre" : "Femme") : "Homme" ?></span>
            <span class="text-muted d-block">École : <?php echo $user_school['name'] ?></span>
            <span class="text-muted d-block">Année : N°<?php echo $user['school_year'] ?></span>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>