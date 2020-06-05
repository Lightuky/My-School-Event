<?php

require_once '../includes/helpers.php';

$data = [];
$fields = [];
$errored = false;

session_start();
$is_brand = isBrand($_SESSION['auth_id']);

foreach ($_POST as $name => $value):
    $data[$name] = $value;
endforeach;

if ($data['linkYoutube']):
    $headers = get_headers('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=' . $data['linkYoutube']);

    if (!strpos($headers[0], '200')):
        $errored = true;
    endif;
endif;

if ($errored):
    session_start();
    $_SESSION['fields'] = $fields;

    $pathError = $_SERVER['HTTP_REFERER'];
    header("Location: $pathError");
else:
    $post_id = setNewPost($data, $_SESSION['auth_id']);

    if ($data['linkImgur']):
        setPostAttachments($post_id, "imgur", $data['linkImgur']);
    elseif($data['linkYoutube']):
        setPostAttachments($post_id, "youtube", $data['linkYoutube']);
    endif;

    if ($is_brand):
        setSponsoredPost($post_id);
    endif;

    $pathSuccess = $_SERVER['HTTP_REFERER'];
    header("Location: $pathSuccess");
endif;
