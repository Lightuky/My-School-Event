<?php

require_once '../includes/helpers.php';

$data = [];
$fields = [];
$errored = false;

session_start();

foreach ($_POST as $name => $value) {
    $data[$name] = $value;
}

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;

    $pathError = $_SERVER['HTTP_REFERER'];
    header("Location: $pathError");
}
else {
    setNewHelp($data, $_SESSION['auth_id']);

    $pathSuccess = $_SERVER['HTTP_REFERER'];
    header("Location: $pathSuccess");
}