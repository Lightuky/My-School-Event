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


<section style="margin-top: 60px">
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

<?php require_once 'includes/footer.php'; ?>
