<?php

require_once '../includes/helpers.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$event_infos = getEvent($id);
$event_address = getEventAddress($id);
$pending_users = getPendingUsers($id);

session_start();

$data = [];

foreach ($_POST as $name => $value) {
    $data[$name] = $value;
}

updateEvent($data, $id);
updateAddress($data, $event_address['id']);

if ($data['is_private'] == "0") {
    foreach ($pending_users as $pending_user) {
        $event_infos = getEvent($id);
        $event_members = getEventMembers($id);
        if ($event_infos['member_limit'] != '0') {
            if (($event_infos['member_limit'] - count($event_members)) != '0' AND ($event_infos['member_limit'] - count($event_members)) > '0') {
                acceptEventJoin(0,$id, $pending_user['id']);
            }
            else {
                discardEventPending($id);
            }
        }
        else {
            acceptEventJoin(0,$id, $pending_user['id']);
        }
    }
}

if ($data['member_limit'] != "0") {
    $event_infos = getEvent($id);
    $event_members_sorted = getEventMembersSorted($id);
    if ($event_infos['member_limit'] < count($event_members_sorted)) {
        $event_members_kicked = array_slice($event_members_sorted, $event_infos['member_limit']);
        foreach ($event_members_kicked as $event_member_kicked) {
            quitEvent($event_member_kicked['user_id'], $id);
        }
    }
}


$pathSuccess =  "/mse/event.php?id=$id";
header('Location: '. $pathSuccess);
