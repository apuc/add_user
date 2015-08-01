jQuery(document).on('click', '#check', function (){
    jQuery.ajax({
            url: myajax.url, //url, к которому обращаемся
            type: "POST",
            data: "action=mail", //данные, которые передаем. Обязательно для action указываем имя нашего хука
            success: function(data){
                //возвращаемые данные попадают в переменную data
                //jQuery('#excurtions').html(data);
                alert ('Ваш запрос отправлен. В ближайшее время Вам на почту прийдет счет ');
            }
        });

});

jQuery(document).on('click', '.close', function (){
    $('#callme').modal('hide');
});