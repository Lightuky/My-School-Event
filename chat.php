<?php

require_once 'includes/header.php';
use Carbon\Carbon;
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.UTF8');

if (!isset($_SESSION['auth_id'])) {
    $pathError =  "/mse/login.php";
    header('Location: '. $pathError);
}

$query = isset($_GET['q']) ? $_GET['q'] : null;
$friends_sorted = getFriendsSorted($_SESSION['auth_id']);

?>
    <section>
        <div class="row mx-0 mt-5">
            <div class="col-4 mt-1 overflow-auto" style="height: calc( 100vh - 60px);" id="chatFriendsList">
                <div class="card-header text-dark">
                    Mes contacts
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        $i_users = 0;
                        $friends_credentials = [];
                        $unique_users = [];

                        foreach ($friends_sorted as $friend_sorted):
                            $friends_credentials[] = ["user1_id" => $friend_sorted["user1_id"], "user2_id" => $friend_sorted["user2_id"]];
                            if ($friend_sorted['user1_id'] == $_SESSION['auth_id']):
                                if(!$query):
                                    $other_user = getUser($friend_sorted['user2_id']);
                                else:
                                    $other_user = getUserQuery($friend_sorted['user2_id'], $query);
                                endif;
                            elseif ($friend_sorted['user2_id'] == $_SESSION['auth_id']):
                                if(!$query):
                                    $other_user = getUser($friend_sorted['user1_id']);
                                else:
                                    $other_user = getUserQuery($friend_sorted['user1_id'], $query);
                                endif;
                            endif;
                            $friends_credentials[$i_users]["first_name"] = "" . $other_user['first_name'] . "";
                            $friends_credentials[$i_users]["last_name"] = "" . $other_user['last_name'] . "";
                            $friends_credentials[$i_users]["email"] = "" . $other_user['email'] . "";
                            $friends_credentials[$i_users]["id"] = "" . $other_user['id'] . "";

                            if (!in_array($other_user['id'], $unique_users)) {
                                array_push($unique_users, $other_user['id']);
                            }

                            $i_users++;
                        endforeach;
                        if (!empty($other_user)):
                            foreach ($unique_users as $unique_user):
                                $unique_user_credentials = getUser($unique_user);
                                $last_message = getLastMessageByPerson($unique_user,$_SESSION['auth_id']);
                                $last_sender_name = getUser($last_message['user1_id']);
                                ?>
                                <div class="list-group-item-flush list-group-item-action my-1 d-flex">
                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($unique_user_credentials['email']); ?>?s=700" class="mr-4 bd-highlight rounded-circle" style="width: 15%">
                                    <div>
                                        <div><?php echo $unique_user_credentials["first_name"] . " " . $unique_user_credentials["last_name"] ?></div>
                                        <div class="small text-muted"><?php echo ($last_sender_name["id"] == $_SESSION['auth_id'] ? "Vous" : ucfirst($last_sender_name["first_name"])) . ' : "' . $last_message['message'] . '"' ?></div>
                                    </div>
                                </div>
                            <?php endforeach;
                        else: ?>
                            <div class="list-group-item-flush list-group-item-action my-1">Aucune conversation trouvée</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-8 text-center border-left border-secondary mt-1">
                <div class="card-header text-dark">
                    Messages
                </div>
                <div class="card-body pt-0">
                    <h4>
                        <img src="https://www.gravatar.com/avatar/<?php echo md5($unique_user_credentials['email']); ?>?s=700" class="mr-2 bd-highlight rounded-circle" style="width: 25px">
                        <?php echo ucfirst($friends_credentials[0]['first_name']) . " " . ucfirst($friends_credentials[0]['last_name']) ?>
                    </h4>
                    <div class="row mb-3 border-secondary border" style="height: 60vh">
                        <?php if (!$query):
                            $last_person_messages = getLastPersonMessages($friends_credentials[0]['id'],$_SESSION['auth_id']);
                            foreach ($last_person_messages as $last_person_message): ?>
                                <div class="d-flex justify-content-start mb-4 w-100 mt-4 ml-4">
                                    <div class="mr-3"><img src="https://www.gravatar.com/avatar/<?php echo md5($friends_credentials[0]['email']); ?>?s=700" class="rounded-circle" style="width: 50px"></div>
                                    <div class="">
                                        <div class="p-2" style="background-color: rgba(251, 164, 220, 0.5); border-radius: 25px"><?php echo $last_person_message['message']; ?></div>
                                        <span class="small mr-2" style="color: #8b9bab"><?php echo "le " . date("d/m/Y", strtotime($last_person_message['date_added'])) . " à " . date("H:i", strtotime($last_person_message['date_added'])) ?></span>
                                    </div>
                                </div>
                            <?php endforeach;
                        else:
                            $query_user_messages = getQueryUserMessages($query);
                        endif; ?>
                    </div>
                    <div class="row d-flex justify-content-around border-secondary border" style="height: 15.5vh">
                        <div class="col-10 p-0">
                            <form action="" class="form" style="height: 15.5vh;">
                                <textarea name="message" id="message" rows="4" class="form-control" style="height: 15.2vh; resize: none; border-radius: 0" required></textarea>
                            </form>
                        </div>
                        <div class="col-2 my-auto">
                            <div class="my-auto mr-3"><button name="send" type="submit" class="btn btn-outline-info">Envoyer !</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php require_once 'includes/footer.php'; ?>