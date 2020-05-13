<?php
require_once 'includes/header.php';
use Carbon\Carbon;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$help_infos = getHelp($id);
$help_author = getUser($help_infos['author_id']);
$help_answers = getHelpComments($id);
$help_likes = getHelpLikes($id);

if ($help_infos['title'] == NULL) {
    $pathError =  "/mse/404.php";
    header('Location: '. $pathError);
}

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
        <div class="col-10 mx-0 p-0 d-flex ml-auto" style="padding-top: 60px!important;">
            <div class="col-12 p-0">
                <div id="allHelps">
                    <div class="card col-10 mx-auto mt-4" id="ContentPosts">
                        <div class="card-body">
                            <img src="https://www.gravatar.com/avatar/<?php echo md5($help_infos['email']); ?>?s=600" alt="" class="d-block rounded-circle position-absolute" id="ContentProfilePics">
                            <h5 class="card-title"><?php echo $help_infos['first_name'] . " " . $help_infos['last_name'] ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($help_infos['date_added']); ?></h6>
                            <p class="card-text text-secondary font-weight-bold mb-0 mt-3"><?php echo $help_infos['title'] ?></p>
                            <p class="card-text text-muted"><?php echo $help_infos['content'] ?></p>
                            <div class="d-flex">
                                <i class="fas fa-lightbulb mt-1 text-info"></i>
                                <div class="ml-2"><?php echo count(getHelpLikes($help_infos['id'])); ?></div>
                            </div>
                            <hr class="bg-secondary">
                            <div class="d-flex justify-content-around mt-3">
                                <?php if (isset($_SESSION['auth_id'])) {
                                    $help_likes = getHelpLikes($help_infos['id']);
                                    if (empty($help_likes)) { ?>
                                        <div class="d-flex">
                                            <a href="assets/addhelplike.php?s=2&id=<?php echo $help_infos['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
                                        </div>
                                    <?php }
                                    else {
                                        foreach ($help_likes as $help_like) {
                                            if ($help_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                <div class="d-flex">
                                                    <a href="assets/delhelplike.php?s=2&id=<?php echo $help_infos['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-info"></i> Pertinent</a>
                                                </div>
                                                <?php break;
                                            }
                                            elseif (end($help_likes) == $help_like) { ?>
                                                <div class="d-flex">
                                                    <a href="assets/addhelplike.php?s=2&id=<?php echo $help_infos['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-muted"></i> Pertinent</a>
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
                                    <a href="help.php?id=<?php echo $help_infos['id'] ?>" class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Répondre</a>
                                </div>
                                <div class="d-flex">
                                    <a href="#" class="card-link ml-2 text-muted"><i class="fas fa-share mt-1 text-muted"></i> Partager</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div>
                                <?php
                                $help_answer_infos = getHelpComments($help_infos['id']);
                                $i_answers = 0;
                                $new_answers = [];

                                foreach ($help_answer_infos as $help_answer_info) {
                                    $new_answers[] = ["id" => $help_answer_info["id"], "author_id" => $help_answer_info["author_id"], "help_id" => $help_answer_info["help_id"],
                                        "content" => $help_answer_info["content"], "date_added" => $help_answer_info["date_added"], "date_edited" => $help_answer_info["date_edited"],
                                        "first_name" => $help_answer_info["first_name"], "last_name" => $help_answer_info["last_name"], "email" => $help_answer_info["email"]];
                                    $help_answer_ratio = (count(getHelpAnswerLikes($help_answer_info['id'])) - count(getHelpAnswerDislikes($help_answer_info['id'])));
                                    $new_answers[$i_answers]["ratio"] = "$help_answer_ratio";
                                    $i_answers++;
                                }
                                $ratio_column = array_column($new_answers, 'ratio');
                                array_multisort($ratio_column, SORT_DESC, $new_answers);

                                foreach ($new_answers as $new_answer) { ?>
                                        <div class="card-body col-10 mx-auto mb-5" id="ContentPosts">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($new_answer['email']); ?>?s=600" alt="" class="d-block rounded-circle position-relative" id="CommentProfilePics">
                                                    <h6 class="card-title"><?php echo $new_answer['first_name'] . " " . $new_answer['last_name'] ?></h6>
                                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo "Il y à " . getDateForHumans($new_answer['date_added']); ?></h6>
                                                </div>
                                                <div class="mt-auto mb-4">
                                                    <div class="d-flex">
                                                        <i class="fas fa-lightbulb mt-1 text-info"></i>
                                                        <div class="ml-2" title="<?php echo count(getHelpAnswerLikes($new_answer['id'])) .
                                                            " personnes ont trouvée(s) cette réponse utile, " . count(getHelpAnswerDislikes($new_answer['id'])) . " autre(s) non." ?>">
                                                            <?php echo count(getHelpAnswerLikes($new_answer['id'])) . " / " . count(getHelpAnswerDislikes($new_answer['id'])) ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted"><?php echo $new_answer['content'] ?></p>
                                            <hr class="bg-secondary">
                                            <div class="d-flex justify-content-around mt-3">
                                                <?php if (isset($_SESSION['auth_id'])) {
                                                    $help_comment_likes = getHelpAnswerLikes($new_answer['id']);
                                                    $help_comment_dislikes = getHelpAnswerDislikes($new_answer['id']);
                                                    if (empty($help_comment_likes)) { ?>
                                                        <div class="d-flex">
                                                            <a href="assets/addhelpcommentlike.php?s=2&id=<?php echo $new_answer['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-success"></i> Utile</a>
                                                        </div>
                                                    <?php }
                                                    else {
                                                        foreach ($help_comment_likes as $help_comment_like) {
                                                            if ($help_comment_like['user_id'] == $_SESSION['auth_id']) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/delhelpcommentlike.php?s=2&id=<?php echo $new_answer['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-success"></i> Voté utile</a>
                                                                </div>
                                                                <?php break;
                                                            }
                                                            elseif (end($help_comment_likes) == $help_comment_like) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/addhelpcommentlike.php?s=2&id=<?php echo $new_answer['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-success"></i> Utile</a>
                                                                </div>
                                                            <?php }
                                                        }
                                                    }
                                                    if (empty($help_comment_dislikes)) { ?>
                                                        <div class="d-flex">
                                                            <a href="assets/addhelpcommentdislike.php?s=2&id=<?php echo $new_answer['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-danger"></i> Pas utile</a>
                                                        </div>
                                                    <?php }
                                                    else {
                                                        foreach ($help_comment_dislikes as $help_comment_dislike) {
                                                            if ($help_comment_dislike['user_id'] == $_SESSION['auth_id']) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/delhelpcommentdislike.php?s=2&id=<?php echo $new_answer['id'] ?>" class="card-link ml-2 text-muted"><i class="fas fa-lightbulb mt-1 text-danger"></i> Voté inutile</a>
                                                                </div>
                                                                <?php break;
                                                            }
                                                            elseif (end($help_comment_dislikes) == $help_comment_dislike) { ?>
                                                                <div class="d-flex">
                                                                    <a href="assets/addhelpcommentdislike.php?s=2&id=<?php echo $new_answer['id'] ?>" class="card-link ml-2 text-muted"><i class="far fa-lightbulb mt-1 text-danger"></i> Inutile</a>
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
                                                    <a href="help.php?id=<?php echo $help_infos['id'] ?>" class="btn btn-light bg-white py-0 text-muted border-0 showCommentForm"><i class="far fa-comment-alt mt-1 text-muted"></i> Répondre</a>
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
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
