
$(document).ready(function () {
   ajaxInquiry("../server.php",{menuActive:'myPage'}, myPageActive);//myPageActive из файла 'scrips.js'
});
//======== Активна кнопка 'Моя страница' =======
$("#myPage").on('click', function(){
    ajaxInquiry("../server.php",{menuActive:'myPage'}, myPageActive);
});

//======== Активна кнопка 'Люди' ==========
var data = [];
$("#people").on('click', function(){
    while(window.data.pop()){} //очищаем массив
    ajaxInquiry("../server.php", {menuActive:"people"}, function (data) {
        //Собственно, сама страница "Люди"
        //Меню настройки сортировки
        $('#content').remove();
        $("#profile").empty();
        $("#profile").append("<div id='sort'>" +
            "<p>Выводить по: </p>" +
            "<select id='limit'>" +
            "<option value='2'>2</option>" +
            "<option value='4'>4</option>" +
            "<option value='8'>8</option>" +
            "<option value='16'>16</option>" +
            "</select>" +
            "<p> человек</p>" +
            "</div>");

        //Первая форма под выборку (по умолчанию)
        $("#profile").append("<div id='sampling'>" +
            "<div><p id='№'>№</p><p id='name'>Имя</p><p id='date'>Дата рождения</p><p id='location'>Город</p></div></div>");
        addStrUserInfo(data);

        //кнопка
        $("#profile").append("<div id='butList' class='button'><a id='add' href='#'>Добавить</a></div>");

        //Добавление новой выборки
        $("#add").on('click', function () {
            var data = {menuActive: 'people',
                        addActive: true,
                        limit: $("#limit").val()};
            ajaxInquiry('../server.php', data, addStrUserInfo);
        });

        //Пользовательские настройки
        $('#limit').on("change", function () {
            var obj =   {menuActive:'people',
                        limit:$("#limit").val()};
            ajaxInquiry('../server.php',obj, function (data) {
                $("#sampling").empty();
                $("#sampling").append("<div><p id='№'>№</p><p id='name'>Имя</p><p id='date'>Дата рождения</p><p id='location'>Город</p></div>");
                while(window.data.pop()){} //очищаем массив логинов
                addStrUserInfo(data);
            });
        });

        //Переходы к другому пользователю
        $("#sampling a").on('click', function () {
            var login = window.data[$(this).find("p:first-child").text() - 1];
            ajaxInquiry('../server.php',{menuActive:'myPage', pageOwner:login}, myPageActive);
        });
        $("#sampling").on("DOMSubtreeModified", function () {
            $("#sampling a").on('click', function () {
                var login = window.data[$(this).find("p:first-child").text() - 1];
                ajaxInquiry('../server.php',{menuActive:'myPage', pageOwner:login}, myPageActive);
            });
        });
    });
});

//========= Настройки профиля =============
