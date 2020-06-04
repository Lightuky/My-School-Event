<?php

require_once '../includes/helpers.php';
$event_comment_id = isset($_GET['id']) ? $_GET['id'] : null;

$data = [];
$fields = [];
$errored = false;

session_start();

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;

    $pathError = $_SERVER['HTTP_REFERER'];
    header("Location: $pathError");
}
else {
    addEventCommentLike($event_comment_id, $_SESSION['auth_id']);

    $pathSuccess = $_SERVER['HTTP_REFERER'];
    header("Location: $pathSuccess");
}