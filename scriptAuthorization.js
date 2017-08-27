function funcSuccess(data){
    if($("#logSpr").hasClass("true"))
    {
        $("#logSpr").removeClass("true");
        $("#passSpr").removeClass("true");
    }
    if($("#logSpr").hasClass("false"))
    {
        $("#logSpr").removeClass("false");
        $("#passSpr").removeClass("false");
    }
    if($("#logSpr").hasClass("loading"))
    {
        $("#logSpr").removeClass("loading");
        $("#passSpr").removeClass("loading");
    }
    if(data == "true")
    {
        $("#logSpr").addClass("true");
        $("#passSpr").addClass("true");

        setTimeout("$(location).attr('href','my_page/my_page.php');",1000);
    }
    if(data == "false")
    {
        $("#logSpr").addClass("false");
        $("#passSpr").addClass("false");
    }
}
$("#do_login").on("click",function(){
    $.ajax({
        url: "server.php",
        type: "GET",
        data: ({logAutho:$("#logAutho").val(),
                passAutho:$("#passAutho").val()}),
        dataType:"json",
        beforeSend: function(){
            $("#logSpr").addClass("loading");
            $("#passSpr").addClass("loading");
        },
        success: funcSuccess
    });
});