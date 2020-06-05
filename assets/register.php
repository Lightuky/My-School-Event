<?php

require_once '../includes/helpers.php';

$referer = isset($_GET['s']) ? $_GET['s'] : null;
$data = [];
$fields = [];
$errored = false;
$users = getAllUsers();

foreach ($_POST as $name => $value):
    $data[$name] = $value;
    $fields[$name]['old'] = $value;
    $fields[$name]['error'] = !$value ? 'Ce champ est obligatoire' : NULL;
endforeach;


foreach ($users as $user):
    if ($user['email'] == $data['email']):
            $errored = true;
            $fields["email"]['error'] = "Adresse Email déja utilisée";
            break;
    elseif ($data['password'] != $data['password-confirm']):
        $errored = true;
        $fields["password"]['error'] = "Champs de Mots de passe différents";
    endif;
endforeach;


if ($referer === '2'):
    $data["gender"] = $data["school_id"] = $data["school_year"] = NULL;
endif;


if ($errored):
    session_start();
    $_SESSION['fields'] = $fields;

    if ($referer === '1'):
        $pathError =  '/mse/register.php?errored=true';
        header('Location: '. $pathError);
    elseif($referer === '2'):
        $pathError =  '/mse//registerbrand.php?errored=true';
        header('Location: '. $pathError);
    endif;
else:
    $id = setNewUser($data);
    session_start();
    $_SESSION['auth_id'] = $id;

    if ($referer === '2'):
        setNewBrandInfos($id, $data);
    endif;

    $pathSuccess =  "/mse/profile.php?id=" . $_SESSION['auth_id'];
    header('Location: '. $pathSuccess);
endif;