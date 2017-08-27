//Динамическая проверка верности введённых значений при регистрации
$(document).ready(function () {
    //проверка логина
    dynamicCheck('#log', '#logSpr');
    //проверка почты
    dynamicCheck('#mail', '#emailSpr');
    //+++++++++++ПРОВЕРКА ПАРОЛЯ+++++++++
    //убираем спрайт
    $("#passFirst, #passSecond").on("focus",function(){

            $("#passFirstSpr, #passSecondSpr").removeClass("true false");
    });
    //проверяем верность паролей
    $("#passFirst, #passSecond").on("focusout",function(){
        if($("#passSecond").val() != $("#passFirst").val() && $("#passSecond").val()!="" ||
            $("#passSecond").val() == "" || $("#passFirst").val() == "")
        {
            $("#passFirstSpr, #passSecondSpr").addClass("false");
        }
        else if($("#passFirst").val() == $("#passSecond").val())
        {
            $("#passFirstSpr, #passSecondSpr").addClass("true");
        }
    });
});