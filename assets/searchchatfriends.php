<?php

session_start();

require_once '../includes/helpers.php';

$data = [];
$fields = [];
$errored = false;

foreach ($_POST as $name => $value):
    $data[$name] = $value;
endforeach;

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;
    $pathError = $_SERVER['HTTP_REFERER'];
    header("Location: $pathError");
}
else {
    $pathSuccess =  "/mse/chatfriends.php?q=" . $data['searchfriends'];
    header('Location: '. $pathSuccess);
}