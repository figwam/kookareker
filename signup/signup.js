
$('#signup').on('click', function () {

    $('#logSpr, #passFirstSpr, #passSecondSpr, #emailSpr').removeClass();
    $.ajax({
        url:'../server.php',
        type:'GET',
        data:{
            signup:true,
            login:$('#log').val(),
            passFirst:$('#passFirst').val(),
            passSecond:$('#passSecond').val(),
            email:$('#mail').val()
            },
        dataType:'json',
        success:function(data){
            alert(data);
            if(data !== true)//В случае неуспешной регистрации
            {
                if($.inArray('login',data) != -1)
                    $('#logSpr').addClass('errorSignup');
                else
                    $('#logSpr').addClass('true');

                if($.inArray('password',data) != -1)
                    $('#passFirstSpr #passSecondSpr').addClass('errorSignup');
                else
                    $("#passFirstSpr, #passSecondSpr").addClass("true");

                if($.inArray('email',data) != -1)
                    $('#emailSpr').addClass('errorSignup');
                else
                    $('#emailSpr').addClass('true');
            }
            else//В случае успешной регистрации
            {

                $(location).attr('href','../settings/profileSettings.php');
            }
        }
    });
});