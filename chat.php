<?php

require_once 'includes/header.php';
use Carbon\Carbon;
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.UTF8');

if (!isset($_SESSION['auth_id'])) {
    $pathError =  "/mse/login.php";
    header('Location: '. $pathError);
}

$query = isset($_GET['u']) ? $_GET['u'] : null;
$friends_conv_sorted = getFriendsConvSorted($_SESSION['auth_id']);

if ($query) {
    $query_user_credentials = getuser($query);
}

?>
    <section class="chatPage">
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
                        $unique_users = [];

                        if (empty($friends_conv_sorted) && $query):
                            $friends_credentials[] = ["user1_id" => $_SESSION['auth_id'], "user2_id" => $query];
                            $other_user = getUser($query);
                            $friends_credentials[$i_users]["first_name"] = "" . $other_user['first_name'] . "";
                            $friends_credentials[$i_users]["last_name"] = "" . $other_user['last_name'] . "";
                            $friends_credentials[$i_users]["email"] = "" . $other_user['email'] . "";
                            $friends_credentials[$i_users]["id"] = "" . $other_user['id'] . "";

                            array_push($unique_users, $other_user['id']);
                        endif;

                        foreach ($friends_conv_sorted as $friend_conv_sorted):
                            $friends_credentials[] = ["user1_id" => $friend_conv_sorted["user1_id"], "user2_id" => $friend_conv_sorted["user2_id"]];
                            if ($friend_conv_sorted['user1_id'] == $_SESSION['auth_id']):
                                $other_user = getUser($friend_conv_sorted['user2_id']);
                            elseif ($friend_conv_sorted['user2_id'] == $_SESSION['auth_id']):
                                $other_user = getUser($friend_conv_sorted['user1_id']);
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
                                <a href="chat.php?u=<?php echo $unique_user_credentials["id"] ?>" class="list-group-item-flush list-group-item-action py-2 d-flex" id="ChatUniqueFriendList">
                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($unique_user_credentials['email']); ?>?s=700" class="mr-4 bd-highlight rounded-circle" style="width: 15%">
                                    <div class="align-self-center">
                                        <div><?php echo $unique_user_credentials["first_name"] . " " . $unique_user_credentials["last_name"] ?></div>
                                        <div class="small text-muted">
                                            <?php echo empty($last_message) ? "" : (($last_sender_name["id"] == $_SESSION['auth_id'] ? "Vous" : ucfirst($last_sender_name["first_name"])) . ' : "' . $last_message['message'] . '"') ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach;
                        else:
                            if ($query):
                                ?>
                                <a href="chat.php?u=<?php echo $query_user_credentials["id"] ?>" class="list-group-item-flush list-group-item-action py-2 d-flex" id="ChatUniqueFriendList">
                                    <img src="https://www.gravatar.com/avatar/<?php echo md5($query_user_credentials['email']); ?>?s=700" class="mr-4 bd-highlight rounded-circle" style="width: 15%">
                                    <div class="align-self-center">
                                        <div><?php echo $query_user_credentials["first_name"] . " " . $query_user_credentials["last_name"] ?></div>
                                    </div>
                                </a>
                            <?php else: ?>
                                <div class="list-group-item-flush list-group-item-action my-1">Aucune conversation trouvée</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-8 text-center border-left border-secondary mt-1">
                <div class="card-header text-dark">Messages</div>
                <div class="card-body pt-0">
                    <?php if ($friends_credentials): ?>
                        <h4>
                            <?php if (!$query): ?>
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($unique_user_credentials['email']); ?>?s=700" class="mr-2 bd-highlight rounded-circle" style="width: 25px">
                                <?php echo ucfirst($friends_credentials[0]['first_name']) . " " . ucfirst($friends_credentials[0]['last_name']);
                            else: ?>
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($query_user_credentials['email']); ?>?s=700" class="mr-2 bd-highlight rounded-circle" style="width: 25px">
                                <?php echo ucfirst($query_user_credentials['first_name']) . " " . ucfirst($query_user_credentials['last_name']);
                            endif; ?>
                        </h4>
                        <div class="mb-3 border-secondary border overflow-auto" style="height: 60vh" id="messageFeed">
                            <?php if (!$query):

                                $last_person_messages = getLastPersonMessages($friends_credentials[0]['id'],$_SESSION['auth_id']);
                                $receiver_id = empty($last_person_messages) ? $friends_credentials[0]['id'] : ($last_person_messages[0]["user1_id"] != $_SESSION['auth_id'] ? $last_person_messages[0]["user1_id"] : $last_person_messages[0]["user2_id"]);

                                foreach ($last_person_messages as $last_person_message):
                                    $last_person_pictures = getUser($last_person_message["user1_id"]);
                                    if ($last_person_message["user1_id"] != $_SESSION['auth_id']): ?>
                                        <div class="d-flex justify-content-start mb-4 w-100 mt-4">
                                            <div class="mr-3 ml-4"><img src="https://www.gravatar.com/avatar/<?php echo md5($last_person_pictures['email']); ?>?s=700" class="rounded-circle" style="width: 50px"></div>
                                            <div class="mt-1">
                                                <div class="p-2" style="background-color: rgba(251, 164, 220, 0.5); border-radius: 25px"><?php echo $last_person_message['message']; ?></div>
                                                <span class="small mr-2" style="color: #8b9bab"><?php echo "le " . date("d/m/Y", strtotime($last_person_message['date_added'])) . " à " . date("H:i", strtotime($last_person_message['date_added'])) ?></span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex justify-content-end mb-4 w-100 mt-4">
                                            <div class="mt-1">
                                                <div class="p-2" style="background-color: rgba(169,102,236,0.5); border-radius: 25px"><?php echo $last_person_message['message']; ?></div>
                                                <span class="small mr-2" style="color: #8b9bab"><?php echo "le " . date("d/m/Y", strtotime($last_person_message['date_added'])) . " à " . date("H:i", strtotime($last_person_message['date_added'])) ?></span>
                                            </div>
                                            <div class="ml-3 mr-4"><img src="https://www.gravatar.com/avatar/<?php echo md5($last_person_pictures['email']); ?>?s=700" class="rounded-circle mb-2" style="width: 50px"></div>
                                        </div>
                                    <?php endif;
                                endforeach;
                            else:
                                $query_user_messages = getQueryUserMessages($query_user_credentials['id'], $_SESSION['auth_id']);
                                $receiver_id = $query;

                                foreach ($query_user_messages as $query_user_message):
                                    $query_person_pictures = getUser($query_user_message["user1_id"]);
                                    if ($query_user_message["user1_id"] != $_SESSION['auth_id']): ?>
                                        <div class="d-flex justify-content-start mb-4 w-100 mt-4">
                                            <div class="mr-3 ml-4"><img src="https://www.gravatar.com/avatar/<?php echo md5($query_person_pictures['email']); ?>?s=700" class="rounded-circle" style="width: 50px"></div>
                                            <div class="mt-1">
                                                <div class="p-2" style="background-color: rgba(251, 164, 220, 0.5); border-radius: 25px"><?php echo $query_user_message['message']; ?></div>
                                                <span class="small mr-2" style="color: #8b9bab"><?php echo "le " . date("d/m/Y", strtotime($query_user_message['date_added'])) . " à " . date("H:i", strtotime($query_user_message['date_added'])) ?></span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex justify-content-end mb-4 w-100 mt-4">
                                            <div class="mt-1">
                                                <div class="p-2" style="background-color: rgba(169,102,236,0.5); border-radius: 25px"><?php echo $query_user_message['message']; ?></div>
                                                <span class="small mr-2" style="color: #8b9bab"><?php echo "le " . date("d/m/Y", strtotime($query_user_message['date_added'])) . " à " . date("H:i", strtotime($query_user_message['date_added'])) ?></span>
                                            </div>
                                            <div class="ml-3 mr-4"><img src="https://www.gravatar.com/avatar/<?php echo md5($query_person_pictures['email']); ?>?s=700" class="rounded-circle mb-2" style="width: 50px"></div>
                                        </div>
                                    <?php endif;
                                endforeach;
                            endif; ?>
                        </div>
                    <div class="justify-content-around border-secondary border" style="height: 15.5vh">
                        <form method="post" action="assets/sendchatmessage.php?r=<?php echo $receiver_id ?>" class="form-inline" style="height: 15.5vh;">
                            <div class="form-group col-10 px-0">
                                <textarea name="message" id="message" rows="4" class="form-control w-100" style="height: 15.3vh; resize: none; border-radius: 0; margin-top: -2px;" required></textarea>
                            </div>
                            <div class="col-2 px-0">
                                <button name="send" type="submit" class="btn btn-outline-info my-auto">Envoyer !</button>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php require_once 'includes/footer.php'; ?>