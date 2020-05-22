<?php

require_once '../includes/helpers.php';

session_start();

$users = getAllUsers();

$data = [];
$fields = [];
$errored = false;

foreach ($_POST as $name => $value):
    $errored = !$value ? true : $errored;
    $data[$name] = $value;
    $fields[$name]['old'] = $value;
    $fields[$name]['error'] = !$value ? 'Ce champ est obligatoire' : NULL;
endforeach;

foreach ($users as $user):
    if ($user['email'] == $data['email']):
        if ($user['password'] == sha1($data['password'])):
            $errored = false;
            break;
        else:
            $errored = true;
            $fields["password"]['error'] = "Erreur dans la combinaison Mot de passe / Email";
            break;
        endif;
    elseif ((end($users) == $user) && ($user['email'] != $data['email'])):
        $errored = true;
        $fields["email"]['error'] = "Adresse email inexistante";
    endif;
endforeach;

if ($errored):
    session_start();
    $_SESSION['fields'] = $fields;

    $pathError =  '/mse/login.php?errored=true';
    header('Location: '. $pathError);

else:
    $auth_user = authUser($data);
    foreach ($auth_user as $name => $value):
        $_SESSION["auth_$name"] = $value;
    endforeach;

    $categories = getCategories();
    $badges = getBadges();
    $owned_events = count(getOwnedEvents($_SESSION['auth_id']));
    $joined_events_cats = [];
    foreach ($categories as $category):
        $joined_events_cats[$category['id']] = count(getCategoryJoinedEvents($_SESSION['auth_id'], $category['id']));
    endforeach;
    $joined_events = count(getUserAcceptedEvents($_SESSION['auth_id']));
    $account_age = getUser($_SESSION['auth_id']);

    $i_badges = 0;
    foreach ($badges as $badge) {
        if ($badges[$i_badges]['id'] == "1") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ($owned_events >= 5 && $owned_events < 15) {
                    setNewBadge($_SESSION['auth_id'], 1);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "2") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ($owned_events >= 15 && $owned_events < 50) {
                    setNewBadge($_SESSION['auth_id'], 2);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "3") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ($owned_events >= 50) {
                    setNewBadge($_SESSION['auth_id'], 3);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "4") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ($joined_events_cats['1'] >= 15) {
                    setNewBadge($_SESSION['auth_id'], 4);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "5") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ($joined_events_cats['2'] >= 15) {
                    setNewBadge($_SESSION['auth_id'], 5);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "6") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ($joined_events_cats['4'] >= 15) {
                    setNewBadge($_SESSION['auth_id'], 6);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "7") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ($joined_events_cats['3'] >= 15) {
                    setNewBadge($_SESSION['auth_id'], 7);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "8") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ($joined_events >= 25 && $joined_events < 50) {
                    setNewBadge($_SESSION['auth_id'], 8);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "9") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ($joined_events >= 50) {
                    setNewBadge($_SESSION['auth_id'], 9);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "10") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ((time() > strtotime("+ 1 year",strtotime($account_age['date_added']))) && (time() < strtotime("+ 2 year",strtotime($account_age['date_added'])))) {
                    setNewBadge($_SESSION['auth_id'], 10);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "11") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if ((time() > strtotime("+ 2 year",strtotime($account_age['date_added']))) && (time() < strtotime("+ 3 year",strtotime($account_age['date_added'])))) {
                    setNewBadge($_SESSION['auth_id'], 11);
                }
            }
        }
        elseif ($badges[$i_badges]['id'] == "12") {
            $temp_badge = getUserBadge($_SESSION['auth_id'], $badge['id']);
            if (!$temp_badge){
                if (time() > strtotime("+ 3 year",strtotime($account_age['date_added']))) {
                    setNewBadge($_SESSION['auth_id'], 12);
                }
            }
        }
        $i_badges++;
    }

    $pathSuccess =  "/mse/profile.php?id=" . $_SESSION['auth_id'];
    header('Location: '. $pathSuccess);
endif;