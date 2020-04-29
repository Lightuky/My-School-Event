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
    $pathError =  '/login.php?errored=true';
    header('Location: '. $pathError);
}
else {
    $user = authUser($data);
    foreach ($user as $name => $value) {
        $_SESSION["auth_$name"] = $value;
    }
    $pathSuccess =  "/mse/profile.php?id=" . $_SESSION['auth_id'];
    header('Location: '. $pathSuccess);
}