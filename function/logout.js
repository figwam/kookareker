$("#logout a").on('click', function () {
    ajaxInquiry("../server.php",{logout:true},function () {
        $(location).attr('href', '../index.php');
    });
});