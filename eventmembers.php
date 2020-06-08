<?php
require_once 'includes/header.php';
use Carbon\Carbon;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$event_infos = getEvent($id);
$event_admin = getUser($event_infos['admin_id']);
$event_members = getEventMembers($id);
$event_members_credentials = getEventMembersCredentials($id);

if ($event_infos['name'] == NULL) {
    $pathError =  "/mse/404.php";
    header('Location: '. $pathError);
}

?>

<section>
    <div class="container" style="margin-top: 90px;">
        <div class="text-center mt-5">
            <h2><?php echo $event_infos['name'] ?></h2>
            <a href="event.php?id=<?php echo $id ?>" class="btn btn-info p-1 mt-2">Page de l'event</a>
        </div>
        <div class="row text-center">
            <div class="col">
                <h5 class="mt-5">Utilisateurs ayant rejoint</h5>
                <div class="text-muted mb-5"><?php echo count($event_members) ?> personne(s) ayant rejoint l'event</div>
                <ul class="card-group d-flex flex-wrap">
                    <?php foreach ($event_members_credentials as $event_member_credentials) {
                        $user_school = getSchool($event_member_credentials['school_id']);
                        $user_picture = getUser($event_member_credentials['user_id']); ?>
                        <div class="col-3">
                            <div class="card-body px-0">
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($user_picture['email']); ?>?s=600" alt="" class="rounded-circle" id="ContentProfilePics">
                                <h5 class="card-title">
                                    <a href="profile.php?id=<?php echo $event_member_credentials['user_id'] ?>" class="nav-link text-dark">
                                        <?php echo $event_member_credentials['first_name'] . " " . $event_member_credentials['last_name'] ?>
                                    </a>
                                </h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo  $user_school['name'] ?></h6>
                                <h6 class="card-subtitle mb-2 text-muted">Année N° <?php echo $event_member_credentials['school_year'] ?></h6>
                            </div>
                        </div>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
