<?php

require_once '../includes/helpers.php';
$help_comment_id = isset($_GET['id']) ? $_GET['id'] : null;
$help_comment = getHelpComment($help_comment_id);

$data = [];
$fields = [];
$errored = false;

session_start();

$likes = getHelpAnswerLikes($help_comment_id);
if (!empty($likes)) {
    foreach ($likes as $like) {
        if ($like['comment_id'] == $help_comment_id AND $like['user_id'] == $_SESSION['auth_id']) {
            delHelpAnswerLike($help_comment_id, $_SESSION['auth_id']);
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
    addHelpAnswerDislike($help_comment_id, $_SESSION['auth_id']);

    $pathSuccess = $_SERVER['HTTP_REFERER'];
    header("Location: $pathSuccess");
}