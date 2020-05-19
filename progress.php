<?php
require_once 'includes/header.php';
use Carbon\Carbon;

if (isset($_SESSION['auth_id'])):
    $user_badges = getUserBadges($_SESSION['auth_id']);
    $badges = getBadges();
    $user = getUser($_SESSION['auth_id']);
else:
    $pathError =  "/mse/index.php";
    header('Location: '. $pathError);
endif;

?>

<section>
    <div style="margin-top: 90px">
        <h2 class="text-center mt-5">Progression des badges</h2>
        <div class="row d-flex mx-0 mt-5">
            <?php foreach ($badges as $badge):
                $misssing_badge = 0; ?>
                <div class="card text-center w-25 p-0 my-3 border-0">
                    <div class="h6 mb-3"><?php echo $badge['name'] ?></div>
                    <?php if (empty(!$user_badges)): ?>
                        <?php foreach ($user_badges as $user_badge):
                            if ($user_badge["badge_id"] == $badge['id']): ?>
                                <span class="fa-stack fa-2x mx-auto">
                                    <i class="fas fa-certificate fa-stack-2x" style="color: <?php echo $badge['color'] ?>"></i>
                                    <i class="fab <?php echo $badge['icon'] ?> fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="text-muted font-weight-light"><?php echo $badge['description'] ?></div>
                                <div class="text-muted font-weight-light">Obtenu le : <?php echo  date('d/m/Y', strtotime($user_badge['date_added'])) ?></div>
                                <?php $misssing_badge = 1;
                            elseif($misssing_badge != 1): $misssing_badge = 2;
                            endif;
                        endforeach;
                        if ($misssing_badge == 2): ?>
                            <span class="fa-stack fa-2x mx-auto">
                                    <i class="fas fa-certificate fa-stack-2x" style="color: lightgray"></i>
                                    <i class="fab <?php echo $badge['icon'] ?> fa-stack-1x fa-inverse"></i>
                            </span>
                            <div class="text-muted font-weight-light"><?php echo $badge['description'] ?></div>
                            <div class="text-muted">
                                <?php if ($badge['id'] == "1"): echo count(getOwnedEvents($_SESSION['auth_id'])) . " sur 5";
                                elseif ($badge['id'] == "2"): echo count(getOwnedEvents($_SESSION['auth_id'])) . " sur 15";
                                elseif ($badge['id'] == "3"): echo count(getOwnedEvents($_SESSION['auth_id'])) . " sur 50";
                                elseif ($badge['id'] == "4"): echo count(getCategoryOwnedEvents($_SESSION['auth_id'], 1)) . " sur 15";
                                elseif ($badge['id'] == "5"): echo count(getCategoryOwnedEvents($_SESSION['auth_id'], 2)) . " sur 15";
                                elseif ($badge['id'] == "6"): echo count(getCategoryOwnedEvents($_SESSION['auth_id'], 4)) . " sur 15";
                                elseif ($badge['id'] == "7"): echo count(getCategoryOwnedEvents($_SESSION['auth_id'], 3)) . " sur 15";
                                elseif ($badge['id'] == "8"): echo count(getUserAcceptedEvents($_SESSION['auth_id'])) . " sur 25";
                                elseif ($badge['id'] == "9"): echo count(getUserAcceptedEvents($_SESSION['auth_id'])) . " sur 50";
                                elseif ($badge['id'] == "10"): echo "Le : " . date('d/m/Y',strtotime("+ 1 year",strtotime($user['date_added'])));
                                elseif ($badge['id'] == "11"): echo "Le : " . date('d/m/Y',strtotime("+ 2 year",strtotime($user['date_added'])));
                                elseif ($badge['id'] == "12"): echo "Le : " . date('d/m/Y',strtotime("+ 3 year",strtotime($user['date_added'])));
                                endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="fa-stack fa-2x mx-auto">
                            <i class="fas fa-certificate fa-stack-2x" style="color: lightgray"></i>
                            <i class="fab <?php echo $badge['icon'] ?> fa-stack-1x fa-inverse"></i>
                        </span>
                        <div class="text-muted font-weight-light"><?php echo $badge['description'] ?></div>
                        <div class="text-muted">
                            <?php if ($badge['id'] == "1"): echo count(getOwnedEvents($_SESSION['auth_id'])) . " sur 5";
                            elseif ($badge['id'] == "2"): echo count(getOwnedEvents($_SESSION['auth_id'])) . " sur 15";
                            elseif ($badge['id'] == "3"): echo count(getOwnedEvents($_SESSION['auth_id'])) . " sur 50";
                            elseif ($badge['id'] == "4"): echo count(getCategoryOwnedEvents($_SESSION['auth_id'], 1)) . " sur 15";
                            elseif ($badge['id'] == "5"): echo count(getCategoryOwnedEvents($_SESSION['auth_id'], 2)) . " sur 15";
                            elseif ($badge['id'] == "6"): echo count(getCategoryOwnedEvents($_SESSION['auth_id'], 4)) . " sur 15";
                            elseif ($badge['id'] == "7"): echo count(getCategoryOwnedEvents($_SESSION['auth_id'], 3)) . " sur 15";
                            elseif ($badge['id'] == "8"): echo count(getUserAcceptedEvents($_SESSION['auth_id'])) . " sur 25";
                            elseif ($badge['id'] == "9"): echo count(getUserAcceptedEvents($_SESSION['auth_id'])) . " sur 50";
                            elseif ($badge['id'] == "10"): echo "Le : " . date('d/m/Y',strtotime(strtotime("+ 1 year",strtotime($user['date_added']))));
                            elseif ($badge['id'] == "11"): echo "Le : " . date('d/m/Y',strtotime(strtotime("+ 2 year",strtotime($user['date_added']))));
                            elseif ($badge['id'] == "12"): echo "Le : " . date('d/m/Y',strtotime(strtotime("+ 3 year",strtotime($user['date_added']))));
                            endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
