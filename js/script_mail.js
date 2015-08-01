function show(state){
    document.getElementById('window').style.display = state;
    document.getElementById('wrap').style.display = state;
}

jQuery(document).on('click', '#masege', function (){
    var text = jQuery("#text").val();
    jQuery.ajax({
        url: myajax.url, //url, к которому обращаемся
        type: "POST",
        data: "action=massage&text="+text, //данные, которые передаем. Обязательно для action указываем имя нашего хука
        success: function(data){
            //возвращаемые данные попадают в переменную data
            //jQuery('#excurtions').html(data);
            alert ('Ваше сообщение отправлено. В ближайшее время с Вами свяжуться');
        }
    });

    text = jQuery("#text").val('');

});

jQuery(document).on('click', '.close', function (){
    $('#callme').modal('hide');
});