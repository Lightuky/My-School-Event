<?php

require_once '../includes/helpers.php';
$help_id = isset($_GET['id']) ? $_GET['id'] : null;

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
    $pathError =  '/index.php?errored=true';
    header('Location: '. $pathError);
}
else {
    addHelpAnswer($_SESSION['auth_id'], $help_id, $data);

    $pathSuccess =  "/mse/help.php?id=" . $help_id;
    header('Location: '. $pathSuccess);

}