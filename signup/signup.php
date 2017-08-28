<?php
    require_once "../server.php";

//?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Регистрация</title>
    <link rel="stylesheet" href="../style/styleDoc.css">
    <link rel="stylesheet" href="../style/styleHeader.css">
    <link rel="stylesheet" href="../style/styleFooter.css">
    <link rel="stylesheet" href="../style/styleBytton.css">
    <link rel="stylesheet" href="../style/styleProfile.css">
    <link rel="stylesheet" href="../style/styleSignup.css">
    <link rel="stylesheet" href="../style/Pseudo-elements.css">
    <script src="../libs/jquery-3.2.1.min.js"></script>
    <script src="../function/function.js"></script>
</head>
<body>
<div id = wrap>
    <header></header>
    <div id="profile"  class='strut'>

        <div id="form">
            <ul id="nameLabel">
                <li><label for="logAutho">Логин</label></li>
                <li><label for="passFirst">Пароль</label></li>
                <li><label for="passSecond">Пвторный пароль</label></li>
                <li><label for="mail">Почта</label></li>
            </ul>
            <ul id="formInput">
                <li>
                    <input type="text"  id="log" required>
                    <p id="logSpr" class="spr"></p>
                </li>
                <li>
                    <input type="password" id="passFirst" required>
                    <p id="passFirstSpr" class="spr"></p>
                </li>
                <li>
                    <input type="password" id="passSecond" required>
                    <p id="passSecondSpr" class="spr"></p>
                </li>
                <li>
                    <input type="email" id="mail" required>
                    <p id="emailSpr" class="spr"></p>
                </li>
            </ul>
            <div id="error"></div>
            <div class="button" id="signup"><a href="#">Зарегистрироваться</a></div>
        </div>
    </div>
    <footer></footer>
</div>
<script src="inputCheck.js"></script>
<script src="signup.js"></script>
</body>
</html>
