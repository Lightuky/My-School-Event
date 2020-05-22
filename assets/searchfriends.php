<?php

session_start();

require_once '../includes/helpers.php';

$data = [];
$fields = [];
$errored = false;
$user_id = isset($_GET['id']) ? $_GET['id'] : null;

foreach ($_POST as $name => $value) {
    $errored = !$value ? true : $errored;
    $data[$name] = $value;
    $fields[$name]['old'] = $value;
    $fields[$name]['error'] = !$value ? 'Ce champ est obligatoire' : NULL;
}
if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;
    $pathError = "/mse/friends.php?id=" . $user_id;
    header("Location: $pathError");
}
else {
    $pathSuccess =  "/mse/friends.php?id=" . $user_id . "&q=" . $data['search'];
    header('Location: '. $pathSuccess);
}