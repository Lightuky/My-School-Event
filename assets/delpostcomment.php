<?php

require_once '../includes/helpers.php';
$post_comment_id = isset($_GET['id']) ? $_GET['id'] : null;
$post_referer = isset($_GET['s']) ? $_GET['s'] : null;
$profile_id = isset($_GET['p']) ? $_GET['p'] : null;

$fields = [];
$errored = false;

session_start();

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;

    if ($post_referer === '1') {
        $pathError =  '/mse/index.php?errored=true';
        header('Location: '. $pathError);
    }
    elseif($post_referer === '2') {
        $pathError =  "/mse/profile.php?id=" . $profile_id . "&errored=true";
        header('Location: '. $pathError);
    }
}
else {
    delPostComment($post_comment_id, $_SESSION['auth_id']);
    delPostCommentAllLikes($post_comment_id);

    if ($post_referer === '1') {
        $pathSuccess =  '/mse/index.php';
        header('Location: '. $pathSuccess);
    }
    elseif($post_referer === '2') {
        $pathSuccess =  "/mse/profile.php?id=" . $profile_id;
        header('Location: '. $pathSuccess);
    }
}