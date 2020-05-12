<?php

require_once '../includes/helpers.php';
$help_comment_id = isset($_GET['id']) ? $_GET['id'] : null;
$help_referer = isset($_GET['s']) ? $_GET['s'] : null;
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

    if ($help_referer === '1') {
        $pathError =  '/index.php?errored=true';
        header('Location: '. $pathError);
    }
    elseif($help_referer === '2') {
        $pathError =  "/mse/help.php?id=" . $help_comment['help_id'] . "&errored=true";
        header('Location: '. $pathError);
    }
}
else {
    delHelpAnswerDislike($help_comment_id, $_SESSION['auth_id']);

    if ($help_referer === '1') {
        $pathSuccess =  "/mse/index.php";
        header('Location: '. $pathSuccess);
    }
    elseif($help_referer === '2') {
        $pathSuccess =  "/mse/help.php?id=" . $help_comment['help_id'];
        header('Location: '. $pathSuccess);
    }
}