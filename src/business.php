<?php

use MongoDB\BSON\ObjectID;


function get_db()
{
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]
    );

    $db = $mongo->wai;

    for ($i = 1; $i <= 100; $i++)
        $db->images->deleteOne(['file_name' => '6ee4d1d8be608c5c2065a20860fec9e0.jpg']);

    // lines below clear database
    // $images = $db->images->find()->toArray();
    // print_r($images);
    // foreach ($images as $image) {
    //     $db->images->deleteOne(['file_name' => $image['file_name']]);
    // }

    // $users_data = $db->users->find()->toArray();
    // print_r($users_data);
    // foreach ($users_data as $user) {
    //     $db->users->deleteOne(['login' => $user['login']]);
    // }

    return $db;
}


function get_user_login($id)
{
    $db = get_db();
    $user_data = $db->users->find(['_id' => new ObjectId($id)])->toArray();
    return $user_data[0]['login'];
}

function get_user_data($login)
{
    $db = get_db();
    $user_data = $db->users->find(['login' => ($login)])->toArray();
    return ($user_data);
}

function add_user($login, $email, $pas)
{
    $db = get_db();

    $user_data = [
        'password' => $pas,
        'email' => $email,
        'login' => $login
    ];
    $db->users->insertOne($user_data);
}


//checks if login is in database
function check_login($login)
{
    $db = get_db();
    $users = $db->users->find()->toArray();
    if (!empty($users)) {
        foreach ($users as $user) {
            if ($user['login'] == $login) {
                return false;
            }
        }
        return true;
    } else {
        return true;
    }

}


function upload_image_data($text, $author, $file, $file_name)
{
    $min_path = SITE_ROOT . "/web/images/min/" . $file_name;
    $watermark_path = SITE_ROOT . "/web/images/watermark/" . $file_name;
    $orginal_path = SITE_ROOT . "/web/images/orginal/" . $file_name;
    $image_data = [
        'min_path' => $min_path,
        'watermark_path' => $watermark_path,
        'orginal_path' => $orginal_path,
        'text' => $text,
        'author' => $author,
        'file_name' => $file_name,
        'raw_data' => $file
    ];
    $db = get_db();
    $db->images->insertOne($image_data);
}

function get_watermark_path($id)
{
    $db = get_db();
    $image = $db->images->find(['_id' => new ObjectId($id)])->toArray();
    return $image[0]['file_name'];
}
function download_image_data($opts)
{
    $db = get_db();
    $image_data = $db->images->find([], $opts)->toArray();
    $filter_data['images'] = [];
    foreach ($image_data as $image) {
        array_push($filter_data['images'], $image);
    }
    return ($filter_data);
}

function check_file_name($file_name)
{
    $db = get_db();
    $image_data = $db->images->find()->toArray();
    foreach ($image_data as $image) {
        if ($file_name == $image['file_name']) {
            return true;
        }
    }
    return false;
}
function new_images($target_file_name, $orginal_image_path, $image_file_type, $watermark_text)
{
    $min_path = SITE_ROOT . "/web/images/min/" . $target_file_name;
    $watermark_path = SITE_ROOT . "/web/images/watermark/" . $target_file_name;
    create_watermark($target_file_name, $watermark_path, $orginal_image_path, $image_file_type, $watermark_text);
    create_miniature($target_file_name, $min_path, $orginal_image_path, $image_file_type);
}


function create_miniature($target_file_name, $min_path, $orginal_image_path, $image_file_type)
{
    $width = 200;
    $height = 125;
    $orginal_image_width = getimagesize($orginal_image_path)[0];
    $orginal_image_height = getimagesize($orginal_image_path)[1];

    if ($image_file_type == "png") {
        $new_image = imagecreatefrompng($orginal_image_path);
    } else {
        $new_image = imagecreatefromjpeg($orginal_image_path);
    }

    $min_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($min_image, $new_image, 0, 0, 0, 0, $width, $height, $orginal_image_width, $orginal_image_height);

    if ($image_file_type == "png") {
        imagejpeg($min_image, $min_path);
    } else {
        imagepng($min_image, $min_path);
    }
    imagedestroy($min_image);
    imagedestroy($new_image);
}


function create_watermark($target_file_name, $watermark_path, $orginal_image_path, $image_file_type, $watermark_text)
{
    if ($image_file_type == "png") {
        $new_image = imagecreatefrompng($orginal_image_path);
    } else {
        $new_image = imagecreatefromjpeg($orginal_image_path);
    }
    $x = 10;
    $y = 10;
    $color = imagecolorallocate($new_image, 140, 160, 90);
    $white = imagecolorallocate($new_image, 255, 255, 255);
    imagefilledrectangle($new_image, $x, $y, 150, 60, $white);
    imagestring($new_image, 3, $x + 10, $y + 20, $watermark_text, $color);
    if ($image_file_type == "png") {
        imagepng($new_image, $watermark_path);
    } else {
        imagejpeg($new_image, $watermark_path);
    }
    imagedestroy($new_image);

}