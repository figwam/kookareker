<?php
require_once "DataBase.php";
require_once 'function/function.php';
session_start();
//Используется метод "GET", т.к. метод "POST" c портом установленным по умолчанию, не работает
$data = $_GET;
$errors = array();
//==================Динамическая проверка логина и пароля ============
if(isset($data['checkForm']))
{
    //ПРОВЕРКА ЛОГИНА
    if (isset($data["login"]))
    {
        $val = existence('new_table', 'login', $data["login"], $settingsLocalHost);
        echo json_encode($val);
    }

    //Проверка Email
    if(isset($data["email"]))
    {
        $val = existence('new_table', 'email', $data["email"], $settingsLocalHost);
        echo json_encode($val);
    }
}
//================== Регистрация =====================================
if(isset($data['signup']))
{
    //Проверка на занятость логина и почты
    $log = existence('new_table', 'login', $data['login'],$settingsLocalHost);
    $mail = existence('new_table', 'email', $data['email'], $settingsLocalHost);
    if($log == 'error' || $log)
        $errors[] = 'login';
    if ($mail == 'error' || $mail)
        $errors[] = 'email';

    //проверка на совпадение паролей
    if($data['passFirst'] != $data['passSecond'])
        $errors[] = 'password';

    if(empty($errors))//регистрация
    {
        $value = array($data['login'],password_hash($data['passFirst'], PASSWORD_DEFAULT),$data['email']);
        $result = addRow($settingsLocalHost, 'new_table', $value);
        $_SESSION['login'] = $data['login'];
        $_SESSION['pageOwner'] = $data['login'];
        echo json_encode($result);
    }
    else
        echo json_encode($errors);
}

//======================Авторизация===================================
if(isset($data['logAutho']))
{
    $base = new mysqli($host,$user,$password,$database);
    $su = $base->query("SELECT DISTINCT `password`
                                FROM new_table
                                WHERE `login` = '".$data['logAutho']."'");
    $base->close();
    $pass = mysqli_fetch_array($su)[0];
    if(password_verify($data['passAutho'],$pass))
    {
        $_SESSION['login'] = $data['logAutho'];
        $_SESSION['pageOwner'] = $data['logAutho'];
        echo json_encode("true");
    }
    else
        echo json_encode("false");
}

//======================Настройки=====================================
if(isset($data['name']) || isset($data['gender'])
    || isset($data['date']) || isset($data['location']))
{
    if(isset($data['name']) && $data['name'] != "")
    {
        $base = new mysqli($host,$user,$password,$database);
        $su = $base->query("UPDATE `users`.`new_table` SET `name` = '".$data['name']."'
                                    WHERE `login` = '".$_SESSION['login']."'");
        $base->close();
        $_SESSION['pageOwner'] = $_SESSION['login'];
    }
    if(isset($data['gender']) && $data['gender'] != "")
    {
        $base = new mysqli($host,$user,$password,$database);
        $su = $base->query("UPDATE `users`.`new_table` SET `gender` = '".$data['gender']."'
                                    WHERE `login` = '".$_SESSION['login']."'");
        $base->close();
        $_SESSION['pageOwner'] = $_SESSION['login'];
    }
    if(isset($data['date']) && $data['date'] != "")
    {
        $base = new mysqli($host,$user,$password,$database);
        $su = $base->query("UPDATE `users`.`new_table` SET `date` = '".$data['date']."'
                                    WHERE `login` = '".$_SESSION['login']."'");
        $base->close();
        $_SESSION['pageOwner'] = $_SESSION['login'];
    }
    if(isset($data['location']) && $data['location'] != "")
    {
        $base = new mysqli($host,$user,$password,$database);
        $su = $base->query("UPDATE `users`.`new_table` SET `location` = '".$data['location']."'
                                    WHERE `login` = '".$_SESSION['login']."'");
        $base->close();
        $_SESSION['pageOwner'] = $_SESSION['login'];
    }
    echo json_encode('1');
}

//======================MenuTransition================================
if(isset($data['menuActive']))
{
    if($data['menuActive'] == 'people')
    {
        if(!isset($data['limit']))
        {
            $_SESSION['limit'] = 2;
            $_SESSION['offset'] = 0;
        }
        else
            $_SESSION['limit'] = $data['limit'];

        if(!isset($_SESSION['offset']))
            $_SESSION['offset'] = 0;

        if(@$data['addActive'] != true)
            $_SESSION['offset'] = 0;



        $limit = $_SESSION['limit'];
        $base = new mysqli($host, $user, $password, $database);
        $ret = $base->query("SELECT `name`,`date`,`location`, `login`
                                FROM `new_table`
                                ORDER BY `name`,`date`,`location`, `login`
                                LIMIT ".$_SESSION['offset'].",".$limit."
            ");
        $out = array();
        $_SESSION['offset'] += $limit;
        $i=0;
        while($qwe = mysqli_fetch_array($ret))
        {
            $out[$i] = $qwe;
            $i++;
        }
        echo json_encode($out);
    }

    else if($data['menuActive'] == 'myPage')
    {
        if(isset($data['pageOwner']))
            $_SESSION['pageOwner'] = $data['pageOwner'];
        else
            $_SESSION['pageOwner'] = $_SESSION['login'];
        $base = new mysqli($host, $user, $password, $database);
        $ret = $base->query("SELECT DISTINCT `name`, `gender`, `date`, `location`
        FROM new_table
        WHERE `login` = '".$_SESSION['pageOwner']."'");
        $base->close();
//        $_SESSION['pageOwner'] = $_SESSION['login'];
        $out = mysqli_fetch_array($ret);
        $out['pageOwner'] = $_SESSION['pageOwner'];
        $out['login'] = $_SESSION['login'];
        echo json_encode($out);

    }
}

//======================logout==========================================
if(isset($data['logout']))
{
    session_destroy();
    echo json_encode(true);
}

//======================AuthorizationCheck=============================
if(isset($data['check']))
{
    if(isset($_SESSION['login']))
        echo json_encode(true);
    else
    {
        session_destroy();
        echo json_encode(false);
    }
}

//======================= Оформление подписки =========================
if(isset($data['subscribe']))
{
    if(!subscriptionCheck($_SESSION['login'], $_SESSION['pageOwner']))
    {
        if($data['subscribe']) {
            $base = new mysqli($host, $user, $password, $database);
            $bool1 = $base->query("UPDATE `new_table` 
                      SET `subscribers` = concat(`subscribers`, '" . $_SESSION['login'] . ",')
                      WHERE `login` = '" . $_SESSION['pageOwner'] . "';");

            $bool2 = $base->query("UPDATE `new_table` 
                      SET `subscriptions` = concat(`subscriptions`, '" . $_SESSION['pageOwner'] . ",')
                      WHERE `login` = '" . $_SESSION['login'] . "';");
            $base->close();
            if ($bool1 && $bool2)
                echo json_encode(true);
            else
                echo json_encode(false);
        }
        //удаление подписки
        elseif(!$data['subscribe'])
        {
            $base = new mysqli($host,$user,$password,$database);
            $obj = $base->query("SELECT `subscribers`
                            FROM `new_table`
                            WHERE `login` = '".$_SESSION['pageOwner']."'");
            $str = mysqli_fetch_array($obj)['subscribers'];
            $arr = explode(',',$str);
            $login=array($_SESSION['login']);
            $totalArr = array_diff($arr, $login);
            $string = implode(',', $totalArr);
            $bool1 = $base->query("UPDATE `new_table`
                                    SET `subscribers` = '".$string."'
                                    WHERE `login` = '".$_SESSION['pageOwner']."'");

            $obj = $base->query("SELECT `subscriptions`
                            FROM `new_table`
                            WHERE `login` = '".$_SESSION['login']."'");
            $str = mysqli_fetch_array($obj)['subscriptions'];
            $arr = explode(',',$str);
            $login=array($_SESSION['pageOwner']);
            $totalArr = array_diff($arr, $login);
            $string = implode(',', $totalArr);
            $bool2 = $base->query("UPDATE `new_table`
                                    SET `subscriptions` = '".$string."'
                                    WHERE `login` = '".$_SESSION['login']."'");
            $base->close();

            if($bool1 && $bool2)
                echo json_encode(true);
            else
                echo json_encode(false);

        }
    }
}

//======================= Дбавление нового кукарека в БД ==============
if(isset($data['newCrow']))
{
    $base = new mysqli($host, $user, $password, $database);
    $result = $base->query("INSERT INTO `postusers` (`login`, `date`, `post`)
                        VALUES ('".$_SESSION['login']."', ".time().", '".$data['newCrow']."') ");
    $base->close();
    echo json_encode($result);
}

//======================= Добавление нового кукарека на страницу ==========
if(isset($data['addPost']))
{
    if($data['addPost'] != 'default')
        $limit = $data['addPost'];
    else
        $limit = 30;

    //выбираем только пост владельца страницы
    if($data['ownerPost'] == 'owner')
        $strLogin = "`login` = '".$_SESSION['pageOwner']."'";

    //здесь выбираем и посты тех, на кого пидписаны  владельца
    elseif($data['ownerPost'] == 'all')
    {
        $base = new mysqli($host, $user, $password, $database);
        $result = $base->query("SELECT `subscriptions`
                                    FROM `new_table`
                                    WHERE login = '".$_SESSION['pageOwner']."'");
        $base->close();
        $strSubscriptions = mysqli_fetch_array($result)[0];
        $arrSubscript = explode(',', $strSubscriptions);
        $strLogin = '';
        for($i = 0; $i < count($arrSubscript) - 1; $i++)
        {
            $strLogin = $strLogin."`login` = '".$arrSubscript[$i]."' OR";
        }
        $strLogin = $strLogin."`login` = '".$_SESSION['pageOwner']."' ";
    }
    $base = new mysqli($host, $user, $password, $database);
    $result = $base->query("SELECT *
                        FROM `postusers`
                        WHERE ".$strLogin."
                        ORDER BY `id` DESC,`date`, login, post
                        LIMIT ".$limit);
    $base->close();
    $i = 0;
    while($pfe = mysqli_fetch_array($result))
    {
        $out[$i] = $pfe;
        $i++;
    }
    echo json_encode(array_reverse($out));
}

//======================= Проверка подписки ============================
if(isset($data['checkSub']))
{
    echo json_encode(subscriptionCheck($_SESSION['login'], $_SESSION['pageOwner'],$settingsLocalHost));
}