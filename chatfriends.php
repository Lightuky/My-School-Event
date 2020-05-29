<?php

require_once 'includes/header.php';
use Carbon\Carbon;
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.UTF8');

$query = isset($_GET['q']) ? $_GET['q'] : null;

if (!isset($_SESSION['auth_id']) OR !$query) {
    $pathError =  "/mse/login.php";
    header('Location: '. $pathError);
}

$friends = getFriends($_SESSION['auth_id']);

?>
    <section>
        <div class="row mx-0 mt-5">
            <div class="col-4 mt-1 overflow-auto" style="height: calc( 100vh - 60px);" id="chatFriendsList">
                <div class="d-flex card-header justify-content-between mt-1">
                    <div class="text-dark card-header border-0 w-50 align-self-center">Mes contacts</div>
                    <form class="form-inline mr-2" method="post" action="assets/searchchatfriends.php">
                        <label for="searchfriends" class="small text-muted w-100">Rechercher des amis</label>
                        <div class="d-flex">
                            <input class="form-control mr-2" type="search" name="searchfriends" id="searchfriends" minlength="2" aria-label="Search">
                            <button class="btn" type="submit"><i class="fas fa-search" aria-hidden="true"></i></button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        $i_users = 0;
                        $friends_credentials = [];

                        foreach ($friends as $friend):
                            $friends_credentials[] = ["user1_id" => $friend["user1_id"], "user2_id" => $friend["user2_id"], "pending" => $friend["pending"],
                                "date_added" => $friend["date_added"], "date_edited" => $friend["date_edited"]];
                            if ($friend['user1_id'] == $_SESSION['auth_id']):
                                $other_user = getUserQuery($friend['user2_id'], $query);
                            elseif ($friend['user2_id'] == $_SESSION['auth_id']):
                                $other_user = getUserQuery($friend['user1_id'], $query);
                            endif;
                            $friends_credentials[$i_users]["first_name"] = "" . $other_user['first_name'] . "";
                            $friends_credentials[$i_users]["last_name"] = "" . $other_user['last_name'] . "";
                            $friends_credentials[$i_users]["email"] = "" . $other_user['email'] . "";
                            $friends_credentials[$i_users]["id"] = "" . $other_user['id'] . "";

                            $i_users++;
                        endforeach;
                        if (!empty($other_user)):
                            foreach ($friends_credentials as $friends_credential):
                                $unique_user_credentials = getUser($friends_credential["id"]);
                                $last_message = getLastMessageByPerson($friends_credential["id"],$_SESSION['auth_id']);
                                $last_sender_name = getUser($last_message['user1_id']);
                                ?>
                                <a href="chat.php?u=<?php echo $friends_credential["id"] ?>" class="list-group-item-flush list-group-item-action py-2 d-flex" id="ChatUniqueFriendList">
                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($friends_credential['email']); ?>?s=700" class="mr-4 bd-highlight rounded-circle" style="width: 15%">
                                    <div class="align-self-center">
                                        <div><?php echo $friends_credential["first_name"] . " " . $friends_credential["last_name"] ?></div>
                                        <div class="small text-muted"><?php echo ($last_sender_name["id"] == $_SESSION['auth_id'] ? "Vous" : ucfirst($last_sender_name["first_name"])) . ' : "' . $last_message['message'] . '"' ?></div>
                                    </div>
                                </a>
                            <?php endforeach;
                        else: ?>
                            <div class="list-group-item-flush list-group-item-action my-1">Aucun ami trouvé</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-8 text-center border-left border-secondary mt-1">
                <div class="card-header text-dark">Messages</div>
                <div class="card-body pt-0">
                    <h4></h4>
                    <div class="mb-3 border-secondary border overflow-auto" style="height: 60vh" id="messageFeed"></div>
                    <div class="justify-content-around border-secondary border" style="height: 15.5vh"></div>
                </div>
            </div>
        </div>
    </section>
<?php require_once 'includes/footer.php'; ?>