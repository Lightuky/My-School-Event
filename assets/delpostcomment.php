<?php

require_once '../includes/helpers.php';
$post_comment_id = isset($_GET['id']) ? $_GET['id'] : null;

$fields = [];
$errored = false;

session_start();

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;
    $pathError =  '/mse/index.php?errored=true';
    header('Location: '. $pathError);
}
else {
    delPostComment($post_comment_id, $_SESSION['auth_id']);

    $pathSuccess =  "/mse/index.php";
    header('Location: '. $pathSuccess);

}