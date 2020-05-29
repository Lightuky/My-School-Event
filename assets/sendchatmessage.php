<?php

require_once '../includes/helpers.php';

$data = [];
$fields = [];
$errored = false;
$receiver_id = isset($_GET['r']) ? $_GET['r'] : null;

session_start();

foreach ($_POST as $name => $value):
    $data[$name] = $value;
endforeach;

if ($errored):
    session_start();
    $_SESSION['fields'] = $fields;
    $pathError = $_SERVER['HTTP_REFERER'];
    header("Location: $pathError");
else:
    addChatMessage($_SESSION['auth_id'], $receiver_id, $data['message']);

    $pathSuccess = $_SERVER['HTTP_REFERER'];
    header("Location: $pathSuccess");
endif;