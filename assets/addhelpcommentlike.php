<?php

require_once '../includes/helpers.php';
$help_comment_id = isset($_GET['id']) ? $_GET['id'] : null;
$help_comment = getHelpComment($help_comment_id);

$data = [];
$fields = [];
$errored = false;

session_start();

$dislikes = getHelpAnswerDislikes($help_comment_id);
if (!empty($dislikes)) {
    foreach ($dislikes as $dislike) {
        if ($dislike['comment_id'] == $help_comment_id AND $dislike['user_id'] == $_SESSION['auth_id']) {
            delHelpAnswerDislike($help_comment_id, $_SESSION['auth_id']);
        }
    }
}

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;

    $pathError = $_SERVER['HTTP_REFERER'];
    header("Location: $pathError");
}
else {
    addHelpAnswerLike($help_comment_id, $_SESSION['auth_id']);

    $pathSuccess = $_SERVER['HTTP_REFERER'];
    header("Location: $pathSuccess");
}