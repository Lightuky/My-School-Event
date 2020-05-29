<?php

require_once '../includes/helpers.php';

$referer = isset($_GET['s']) ? $_GET['s'] : null;
$data = [];
$fields = [];
$errored = false;

foreach ($_POST as $name => $value) {
    $data[$name] = $value;
    $fields[$name]['old'] = $value;
    $fields[$name]['error'] = !$value ? 'Ce champ est obligatoire' : NULL;
}

if ($referer === '2') {
    $data["gender"] = $data["school_id"] = $data["school_year"] = NULL;
}

if ($errored) {
    session_start();
    $_SESSION['fields'] = $fields;

    if ($referer === '1') {
        $pathError =  '/register.php?errored=true';
        header('Location: '. $pathError);
    }
    elseif($referer === '2') {
        $pathError =  '/registerbrand.php?errored=true';
        header('Location: '. $pathError);
    }
}
else {
    $id = setNewUser($data);
    session_start();
    $_SESSION['auth_id'] = $id;

    if ($referer === '2') {
        setNewBrandInfos($id, $data);
    }

    /*$pathSuccess =  "/mse/profile.php?id=" . $_SESSION['auth_id'];
    header('Location: '. $pathSuccess);*/
}