<?php
require_once "server.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главаня</title>
    <link rel="stylesheet" href="style/styleDoc.css">
    <link rel="stylesheet" href="style/styleHeader.css">
    <link rel="stylesheet" href="style/styleProfile.css">
    <link rel="stylesheet" href="style/styleFooter.css">
    <link rel="stylesheet" href="style/stylIntdex.css">
    <link rel="stylesheet" href="style/styleBytton.css">
    <script src="libs/jquery-3.2.1.min.js"></script>
    <script src="signup/inputCheck.js"></script>
    <script src="function/function.js"></script>
    <script src="function/serviceScripts.js"></script>


</head>
<body>
<div id="wrap">
    <header>

    </header>
    <div id="profile"  class='strut'>
        <div id="form">
            <ul>
                <li><label for="logAutho">Авторизация</label></li>
                <li>
                    <label for="logAutho">Логин</label>
                    <input type="text" id="logAutho">
                    <p id="logSpr"></p>
                </li>
                <li>
                    <label for="passAutho">Пароль</label>
                    <input type="password" id="passAutho">
                    <p id="passSpr"></p>
                </li>
            </ul>
            <div class="button" id="do_login"><a href="#">Войти</a></div>
            <div class="button" id="signup"><a href="signup/signup.php">Регистрация</a></div>
        </div>
    </div>



    <footer>

    </footer>

</div>
<script src="scriptAuthorization.js"></script>

</body>
</html>