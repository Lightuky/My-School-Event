<?php

require_once '../includes/helpers.php';
$event_comment_id = isset($_GET['id']) ? $_GET['id'] : null;
$event_referer = isset($_GET['s']) ? $_GET['s'] : null;
$profile_id = isset($_GET['p']) ? $_GET['p'] : null;

$fields = [];
$errored = false;

session_start();

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;

    if ($event_referer === '1') {
        $pathError =  '/mse/index.php?errored=true';
        header('Location: '. $pathError);
    }
    elseif($event_referer === '2') {
        $pathError =  "/mse/profile.php?id=" . $profile_id . "&errored=true";
        header('Location: '. $pathError);
    }
}
else {
    delEventComment($event_comment_id, $_SESSION['auth_id']);
    delEventCommentAllLikes($event_comment_id);

    if ($event_referer === '1') {
        $pathSuccess =  '/mse/index.php';
        header('Location: '. $pathSuccess);
    }
    elseif($event_referer === '2') {
        $pathSuccess =  "/mse/profile.php?id=" . $profile_id;
        header('Location: '. $pathSuccess);
    }
}