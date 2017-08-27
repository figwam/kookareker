//Создаёт профиль пользователя
function myPageActive(data)
{
    $("#profile").empty();
    $("#content").remove();
    $("#profile").append
    (
        "<div id='left'>" +
            "<div id='image'></div>" +
        "</div>" +
        "<div id='right'>" +
            "<div id='login'>"+ data['pageOwner'] +"</div>" +
            "<div id='fieldName'>" +
                "<p id='nameName'>Имя:</p>" +
                "<p id='nameGender'>Пол:</p>" +
                "<p id='nameDate'>Дата рождения:</p>" +
                "<p id='nameLocation'>Город:</p>" +
            "</div>" +
            "<div id='userInfo'>" +
                "<p id='name'>"+data['name']+"</p>" +
                "<p id='gender'>"+(data['gender'] == 'man' ? 'мужской':'женский')+"</p>" +
                "<p id='date'>"+data['date']+"</p>" +
                "<p id='location'>"+data['location']+"</p>" +
            "</div>" +
            "<div id='pageInfo'> здесь должна быть ИНФОРМАЦИЯ О СТРАНИЦЕ</div>" +
        "</div>"
    );
    if(data['pageOwner'] == data['login'])
    {
        $("#userInfo").append(" <div class='GoToSettings button'>" +
            "<a href='../settings/profileSettings.php'>Перейти в настройки</a>" +
            "</div>");
    }
    else
    {

        //проверка подписки
        // ajaxInquiry('../server.php', {checkSub: true}, function(data){
        //     if(data)
        //     {
        //         $("#left").append(
        //             "<div class='GoToSettings button'>" +
        //             "<a href='#' id='Subscribe'>Отписаться</a>" +
        //             "</div>");
        //         //Навешиваем событие на кнопку 'Subscribe'
        //         $("#Subscribe").on('click', function(){
        //             ajaxInquiry('../server.php', {subscribe:false}, function(data){
        //                 alert(data + ' подписка успешно отменена');
        //             });
        //         });
        //     }
        //     else
        //     {
        //         $("#left").append(
        //             "<div class='GoToSettings button'>" +
        //             "<a href='#' id='Subscribe'>Подписаться</a>" +
        //             "</div>");
        //         //Навешиваем событие на кнопку 'Subscribe'
        //         $("#Subscribe").on('click', function(){
        //             ajaxInquiry('../server.php', {subscribe:true}, function(data){
        //                 if(data)
        //                     alert(data + ' подписка успешно оформлена');
        //
        //                 else
        //                     alert(data + ' увы, подписаться не получилось');
        //             });
        //         });
        //     }
        // });
    }
    alert('qwe');
    createContent();
}
//============== создание блока 'content' и наполнение оного содержимым ==============
function createContent(){
    //Поле для ввода нового кукарека
    $("#profile").after("<div id='content'>" +
        "<div><div class='button'><a id='newCrow' href='#newCrow'>Добавить новый кукарек</a></div></div>" +
        "<div id='post'></div>" +
        "</div>");
    $('#newCrow').on('click', function(){
        $('#content>div:first-child').empty();
        $('#content>div:first-child').append("<textarea name='textCrow' id='textCrow'></textarea>" +
            "<div class='button'><a id='newCrow' href='#newCrow'>Кукарекнуть</a></div>");

        //проверка кукарека и добавление в бд и на страницу
        $('#newCrow').on('click', function () {
            $('#content .errors').remove();
            var length = $('#textCrow').val().length,
                lengthMax = 140;

            //проверка длины кукарека
            if(length > lengthMax)
                $('#content textarea').after("<div class='errors'>Кукарек превысил максимальную разрешённую длину на "+(length-lengthMax)+" символов</div>");

            else if($.trim($('#textCrow').val()))
                ajaxInquiry('../server.php', {newCrow:$('#textCrow').val()}, function(data){
                    if(data)
                    {
                        addPost('../server.php', 1, 'owner');
                    }
                });
        });
    });

    //загрузка уже существующих какареков владельца страницы и от его друзей
    addPost('../server.php');
}
//Добавление новых кукареков на страницу
function addPost(url, rows, ownerPost)
{
    var row = rows || 'default', //количество кукареков
        owner = ownerPost || 'all'; //владелец кукара
    ajaxInquiry(url,{addPost:row, ownerPost:owner},function (data) {
        // $('#post').empty();
        for(var i = 0; i < data.length; i++)
        {
            // alert(data[i]);
            $('#post').after("<div>" +
                "<div class='key'>" +
                "<div class = 'login'>Логин: " + data[i]['login'] + "</div>" +
                "<div class = 'date'>Дата: " +date(data[i]['date'] * 1000) + "</div>" +
                "</div>" +
                "<div class = 'post'>" + data[i]['post'] + "</div>" +
                    "<hr>" +
                "</div>")
        }
    });
}
/*
    возвращает дату в формате "ДД.ММ.ГГГГ"
    Если не установлен параметр time, вернёт текущую дату
 */
function date (time)
{
    var dat = new Date();
    time = time || dat.getTime();
    dat.setTime(time);
    return ('0' + dat.getDate()).slice(-2) + '.' + ('0' + (dat.getMonth() + 1)).slice(-2) + '.' + dat.getFullYear();
}
//============ Сокращённая форма "ajax" ======================
function ajaxInquiry(url, objSend, success)
{
    $.ajax({
        url:url,
        type:"GET",
        data:(objSend),
        dataType:'json',
        success:success
    });
}

//Проверяет залогинился ли пользователь
/*
     urlServer - адрес сервера
     urlPage - адрес переадресации
 */
function authorizationCheck(urlServer, urlPage) {
    ajaxInquiry(urlServer,{check:true},function (data) {
        if(!data)
        {
            $(location).attr('href', urlPage);
        }
    });
}
/*
    Для страницы "people"
*/
//Выводит на экран строки из выборки (для страницы "Люди")
function addStrUserInfo (data)
{
    var str;
    while(str = data.shift())
    {
        window.data.push(str['login']);
        var numb = parseInt($("#sampling a:last-child p:first-child").text()) || 0;
        $("#sampling").append("<a href='#'>" +
            "<p class='№'>" + (numb + 1) + "</p>" +
            "<p class='name'>" +str['name']+ "</p>" +
            "<p class='date'>" +str['login']+ "</p>" +
            "<p class='location'>" +str['location']+ "</p></a>");
    }
}

/*
    Динамическая проверка (для логина и почты)
    watch - элемент, события которого отслеживаются
    change - блок, куда вставляется спрайт
*/
function dynamicCheck(watch, change) {
    //убираем спрайт
    $(watch).on("focus", function ()
    {
        $(change).removeClass();
    });
    //проверяем на повторяемость
    $(watch).on('focusout', function()
    {
        $.ajax({
            url: "../server.php",
            type: "GET",
            data: ({
                checkForm:true,
                login:$(watch).val()
            }),
            dataType: "json",
            beforeSend: function () {
                $(change).addClass("loading")
            },
            success: function(data){
                $(change).removeClass("loading");
                if(data != 'error') //если не пустой
                {
                    data = Number(data);
                    if(!data)
                        $(change).addClass("true");
                }
                if(data == 'error' || data)
                    $(change).addClass("false");
            }
        });
    });
}
