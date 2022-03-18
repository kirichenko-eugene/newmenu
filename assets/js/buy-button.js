function addToOrder(elem){
  var dish_id = $(elem).attr('id'); //получаем id этой кнопки
        var dish_name = $(elem).attr('dish_name');
        var dish_price = $(elem).attr('dish_price');
        // для передачи данных фронт-енд - бекенд принято использовать json формат, 
        $.ajax({//передаем ajax-запросом данные
            type: 'POST', //метод передачи данных
            url: 'cart/addtocart.php',//php-файл для обработки данных
            dataType: 'json',   // говорим, что ответ сервера ожидаем в формате json
            data: {dish_id: dish_id, dish_name: dish_name, dish_price: dish_price},  //передаем наши данные - ид блюда и имя блюда
            success: function(data) {   // вот тут в data - массив у нас, jquery сам распарсивает json строку в массив.
                //console.log('Отправили блюдо '+dish_name+' в корзину');
                $('.basker_kol').html(data['value']);//меняем сумму на кнопке корзины 
            },
            error: function(jqXHR, status, msg){
                console.log(jqXHR); console.log(msg+' '+status);
            }
        });
}

$(document).on('click', '.btn-buy', function() {
   addToOrder($(this));
});