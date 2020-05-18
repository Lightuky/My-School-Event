<?php

session_start();

require_once '../includes/helpers.php';

$data = [];
$fields = [];
$errored = false;

foreach ($_POST as $name => $value) {
    $errored = !$value ? true : $errored;
    $data[$name] = $value;
    $fields[$name]['old'] = $value;
    $fields[$name]['error'] = !$value ? 'Ce champ est obligatoire' : NULL;
}
if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;
    $pathError = $_SERVER['HTTP_REFERER'];
    header("Location: $pathError");
}
else {
    $pathSuccess =  "/mse/friends.php?q=" . $data['search'];
    header('Location: '. $pathSuccess);
}