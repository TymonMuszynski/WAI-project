<?php

require_once 'business.php';
function home(&$model)
{
    return 'home_view';
}

function logged(&$model)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // delete session connection 
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params['secure'], $params['httponly']);
        return "redirect:/home";
    }
    $user_id = $_SESSION['user_id'];
    $model['user_login'] = get_user_login($user_id);
    return 'logged_view';
}

function login(&$model)
{
    $GLOBALS['alertMes'] = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $pas = $_POST['pas'];

        if (check_login($login)) {
            alert('Użytkownik o nazwie ' . $login . ' nie istnieje');
        }


        if (!empty($GLOBALS['alertMes'])) {
            $model['alerts'] = get_alert();
            return 'login_view';
        }

        $user = get_user_data($login);

        if (!empty($user)) {
            $hashed_pas = $user[0]['password'];
            $id = $user[0]['_id'];
        } else {
            alert("bład logowania");
            $model['alerts'] = get_alert();
            return 'login_view';
        }

        if (!password_verify($pas, $hashed_pas)) {
            alert("Błedne Hasło");

        }

        if (!empty($GLOBALS['alertMes'])) {
            $model['alerts'] = get_alert();
            return 'login_view';
        }
        $_SESSION['user_id'] = $id;
        alert("Poprawne logowanie");
        $model['alerts'] = get_alert();


        return "redirect:/home";
    }
    return 'login_view';

}

function image_zoom(&$model)
{
    $image_id = $_GET['id'];
    $watermark_path = get_watermark_path($image_id);
    $model['name'] = $watermark_path;
    return "image_zoom_view";
}

function register(&$model)
{
    $GLOBALS['alertMes'] = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $login = $_POST['login'];
        $pas = $_POST['pas'];
        $re_pas = $_POST['re_pas'];

        if ($pas !== $re_pas) {
            alert('Popraw hasła');
        }

        if (!check_login($login)) {
            alert('Login istnieje w bazie uźytkowników');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            alert('Wpisz poprawny adres email');
        }
        if ($email == null || $login == null || $pas == null) {
            alert("Prosze uzupełnić wszytkie pola");
        }

        if (!empty($GLOBALS['alertMes'])) {
            $model['alerts'] = get_alert();
            return 'register_view';
        }

        $hashed_pas = password_hash($pas, PASSWORD_DEFAULT);

        //function create new user in data base
        add_user($login, $email, $hashed_pas);

        alert('Poprawne zarejestrowanie');
        $model['alerts'] = get_alert();
    }
    return 'register_view';
}

function gallery(&$model)
{
    $page = $_GET['page'];
    $pageSize = 4;
    $opts = [
        'skip' => ($page - 1) * $pageSize,
        'limit' => $pageSize
    ];
    // set model variable to all image data
    $model = download_image_data($opts);
    $model['page'] = $page;
    return 'gallery_view';
}

function add_image(&$model)
{
    // const that get default path 
    define('SITE_ROOT', realpath(dirname(__FILE__)));
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $GLOBALS['alertMes'] = [];
        if (empty($_POST['watermark'])) {
            alert("Wypełni pole water mark");
        } else {
            $watermark_text = $_POST['watermark'];
            $text = $_POST['text'];
            $author = $_POST['author'];
            if ($_FILES['image']['size'] >= 1000000) {
                alert("Zdjęcie ma powyej 1MB");
            }
            if ($_FILES['image']['size'] == 0) {
                alert("Brak pliku");
            } else {
                $target_file_name = basename($_FILES["image"]["name"]);
                if (check_file_name($target_file_name)) {
                    alert("Zmień nazwę pliku, ta nazwa jest istnieje w bazie danych");
                }
                $image_file_type = strtolower(pathinfo($target_file_name, PATHINFO_EXTENSION));
                if ($image_file_type != "png" && $image_file_type != "jpg") {
                    alert("Zły typ pliku");
                }
            }
            if (empty($GLOBALS['alertMes'])) {
                $file = $_FILES['image'];
                $target_path = SITE_ROOT . "/web/images/orginal/" . $target_file_name;
                $tmp_path = $file['tmp_name'];
                if (move_uploaded_file($tmp_path, $target_path)) {
                    alert("Poprawnie wysłane");
                    // function creates watermark and minature 
                    new_images($target_file_name, $target_path, $image_file_type, $watermark_text);
                    // sends image data to database
                    upload_image_data($text, $author, $file, $target_file_name);
                }
            }
        }
    }
    $model['alerts'] = get_alert();

    return 'add_image_view';
}

function alert($mes)
{
    array_push($GLOBALS['alertMes'], ['message' => $mes]);
}

function get_alert()
{
    if (isset($GLOBALS['alertMes'])) {
        $alerts = $GLOBALS['alertMes'];
        $GLOBALS['alertMes'] = [];
        return $alerts;
    }
    $GLOBALS['alertMes'] = [];
    return null;
}