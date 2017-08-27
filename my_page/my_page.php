<?php
require_once "../DataBase.php";
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Моя страница</title>
    <script src="../libs/jquery-3.2.1.min.js"></script>
    <script src="../function/function.js"></script>
    <script>
        authorizationCheck('../server.php', "../index.php");
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i&subset=cyrillic,cyrillic-ext');
    </style>
    <link rel="stylesheet" href="../style/styleDoc.css">
    <link rel="stylesheet" href="../style/styleHeader.css">
    <link rel="stylesheet" href="../style/styleMenu.css">
    <link rel="stylesheet" href="../style/styleProfile.css">
    <link rel="stylesheet" href="../style/styleBytton.css">
    <link rel="stylesheet" href="../style/styleContent.css">
    <link rel="stylesheet" href="../style/styleFooter.css">
    <link rel="stylesheet" href="../style/stylePeople.css">

</head>
<body>
<div id="wrap">
    <header>
        <div id="logout">
            <div class="button"><a href="#">Выйти</a></div>
        </div>
    </header>

    <div id="menu">
        <ul>
            <li>Меню</li>
            <li class="button"><a id="myPage" href="#">Моя страница</a></li>
            <li class="button"><a id="people" href="#">Люди</a></li>
        </ul>
    </div>

    <div id="profile"  class='strut'>
    </div>

    <footer>

    </footer>
</div>
<script src="menuTransitions.js"></script>
<script src="../function/logout.js"></script>
</body>
</html>
