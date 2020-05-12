<?php

require_once '../includes/helpers.php';

$data = [];
$fields = [];
$errored = false;

foreach ($_POST as $name => $value) {
    $data[$name] = $value;
}

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;
    $pathError =  '/mse/index.php?errored=true';
    header('Location: '. $pathError);
}
else {
    session_start();

    if (!$data['city'] AND !$data['school']) {
        $pathEmpty =  "/mse/index.php";
        header('Location: '. $pathEmpty);
    }
    else {
        if ($data['city'] AND !$data['school']) {
            $pathOneQuery =  "/mse/index.php?c=" . $data['city'];
            header('Location: '. $pathOneQuery);
        }
        elseif (!$data['city'] AND $data['school']) {
            $pathOneParam =  "/mse/index.php?s=" . $data['school'];
            header('Location: '. $pathOneParam);
        }
        else {
            $pathTwoParam =  "/mse/index.php?c=" . $data['city'] . "&s=" . $data['school'];
            header('Location: '. $pathTwoParam);
        }
    }
}