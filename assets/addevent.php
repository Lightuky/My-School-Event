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
    $pathError =  '/addevent.php?errored=true';
    header('Location: '. $pathError);
}
else {
    $last_address = setNewAddress($data);
    $last_event = setNewEvent($data, $_SESSION['auth_id'], $last_address);

    $pathSuccess =  "/mse/event.php?id=" . $last_event;
    header('Location: '. $pathSuccess);

}