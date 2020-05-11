<?php

require_once '../includes/helpers.php';
$help_comment_id = isset($_GET['id']) ? $_GET['id'] : null;

$data = [];
$fields = [];
$errored = false;

session_start();

$likes = getHelpAnswerLikes($help_comment_id);
if (!empty($likes)) {
    foreach ($likes as $like) {
        if ($like['comment_id'] == $help_comment_id OR $like['comment_id'] == $_SESSION['auth_id']) {
            delHelpAnswerLike($help_comment_id, $_SESSION['auth_id']);
        }
    }
}

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;
    $pathError =  '/index.php?errored=true';
    header('Location: '. $pathError);
}
else {
    delHelpAnswerDislike($help_comment_id, $_SESSION['auth_id']);

    $pathSuccess =  "/mse/index.php";
    header('Location: '. $pathSuccess);

}