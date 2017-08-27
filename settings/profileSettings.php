<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../style/styleDoc.css">
    <link rel="stylesheet" href="../style/styleHeader.css">
    <link rel="stylesheet" href="../style/styleContent.css">
    <link rel="stylesheet" href="../style/styleBytton.css">
    <link rel="stylesheet" href="../style/styleFooter.css">
    <link rel="stylesheet" href="../style/styleSettings.css">
    <script src="../libs/jquery-3.2.1.min.js"></script>
</head>
<body>
<div id="wrap">
    <header></header>
<!--    --><?php
//    if (isset($_SESSION['login']))
//        echo "ЭТО ЕСТЬ ЛОГИН: ".$_SESSION['login'];
//    else
//        echo "ЛОГИНА НЕТ"
//    ?>
    <div id="content">
        <div id="settings">
            <div id="fieldName">
                <p id="nameName">Имя:</p>
                <p id="nameGender">Пол:</p>
                <p id="nameDate">Дата рождения:</p>
                <p id="nameLocation">Город:</p>
            </div>
            <div id="field">
                <p><input type="text" id="name"></p>
                <p><select id="gender">
                        <option value="man">Муж</option>
                        <option value="voman">Жен</option>

                    </select></p>
                <p><input type="date" id="date"></p>
                <p ><input type="text" id="location"></p>
            </div>
            <div class="button GoToSettings">
                <a href="#">Отправить</a>
            </div>
        </div>
    </div>
    <footer></footer>
</div>
<script src="settings.js"></script>
</body>
</html>
