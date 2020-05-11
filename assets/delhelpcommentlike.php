<?php

require_once '../includes/helpers.php';
$help_comment_id = isset($_GET['id']) ? $_GET['id'] : null;

$data = [];
$fields = [];
$errored = false;

session_start();

$dislikes = getHelpAnswerDislikes($help_comment_id);
if (!empty($dislikes)) {
    foreach ($dislikes as $dislike) {
        if ($dislike['comment_id'] == $help_comment_id OR $dislike['comment_id'] == $_SESSION['auth_id']) {
            delHelpAnswerDislike($help_comment_id, $_SESSION['auth_id']);
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
    delHelpAnswerLike($help_comment_id, $_SESSION['auth_id']);

    $pathSuccess =  "/mse/index.php";
    header('Location: '. $pathSuccess);

}