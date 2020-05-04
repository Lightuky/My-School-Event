<?php

require_once '../includes/helpers.php';

session_start();

$event_action = isset($_GET['s']) ? $_GET['s'] : null;
$event_id = isset($_GET['id']) ? $_GET['id'] : null;
$user_id = isset($_GET['u']) ? $_GET['u'] : null;
$already_joined = checkEventState($event_id, $_SESSION['auth_id']);
$event_infos = getEvent($event_id);

if ($event_action === '0') {
    if ($already_joined == false) {
        if ($event_infos['is_private'] === '1') {
            joinEvent(1, $_SESSION['auth_id'], $event_id);
        }
        else {
            joinEvent(0, $_SESSION['auth_id'], $event_id);
        }
        $pathSuccess =  "/mse/event.php?id=$event_id";
        header('Location: '. $pathSuccess);
    }
    else {
        $pathError =  "/mse/event.php?id=$event_id";
        header('Location: '. $pathError);
    }
}
elseif ($event_action === '1') {
    acceptEventJoin(0,$event_id, $user_id);
    $pathSuccess =  "/mse/accepteventusers.php?id=$event_id";
    header('Location: '. $pathSuccess);
}
elseif ($event_action === '2') {
    quitEvent($_SESSION['auth_id'], $event_id);
    $pathSuccess =  "/mse/event.php?id=$event_id";
    header('Location: '. $pathSuccess);
}
elseif ($event_action === '3') {
    quitEvent($user_id, $event_id);
    $pathSuccess =  "/mse/accepteventusers.php?id=$event_id";
    header('Location: '. $pathSuccess);
}
elseif ($event_action === '4') {
    quitEvent($user_id, $event_id);
    $pathSuccess =  "/mse/manageeventusers.php?id=$event_id";
    header('Location: '. $pathSuccess);
}
