$(".button").on("click",function(){
    $.ajax({
        url:"../server.php",
        type:"GET",
        data:({
            name:$("#name").val(),
            gender:$("#gender").val(),
            date:$("#date").val(),
            location:$("#location").val()
        }),
        dataType:"json",
        success:function () {
            $(location).attr('href', '../my_page/my_page.php');
        }
    });
});