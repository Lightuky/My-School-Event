<?php

require_once '../includes/helpers.php';
$post_id = isset($_GET['id']) ? $_GET['id'] : null;

$data = [];
$fields = [];
$errored = false;

session_start();

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;
    $pathError =  '/index.php?errored=true';
    header('Location: '. $pathError);
}
else {
    addPostLike($post_id, $_SESSION['auth_id']);

    $pathSuccess =  "/mse/index.php";
    header('Location: '. $pathSuccess);

}