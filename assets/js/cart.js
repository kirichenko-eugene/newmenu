// после загрузки страницы установим обработчики кнопок:
window.addEventListener('load', function(){
    $('.minus, .plus').click(function () {
        updateQuantity(this);
    });
    $('.quantity').on('change', function(){
        onQuantityChange(this);
    });
    //$('.make-order').on('click', makeOrder);
});

// кнопки плюс и минус
function updateQuantity(elem){
    let $quentityElem = $(elem).parent().find('[type=number]');
    let val = $(elem).hasClass('plus') ? 1 : -1;
    $quentityElem.val( parseInt($quentityElem.val()) + val ).change();
}

// изменение количеста товара
function onQuantityChange(quantityElem)
{
    // получим текукщую строку (элемент <tr>):
    let $currentRow = $(quantityElem).closest('tr');
    // получим элемент с ценой
    let $priceElem = $currentRow.find('.count_price');
    // сохраним старую сумму блюда:
    let oldSum = parseInt($priceElem.html());
    // цена блюда:
    let dishPrice = $priceElem.data('price');
    // введенное количество:
    let quantity = $(quantityElem).val();
    // если значение меньше единицы, установим 1 и обновим поле:
    if(quantity < 1){
        quantity = 1;
        $(quantityElem).val(1);
    }
    // посчитаем новую цену:
    let newSum = dishPrice * quantity;
    // обновим цену в поле:
    $priceElem.html(newSum);
    // пересчитаем общую сумму:
    recountTotal();
    // сохраним количество блюда в корзину:
    updateCart($priceElem.attr('id'), quantity);
}

function updateCart(dishId, dishCount){
    $.ajax({
        type: 'POST',
        url: 'cartamount.php',
        data: {id_tov: dishId, col_tov: dishCount},
        success: function(data) {
            console.log('цена товара успешно сохранена!');
        }
    });
}

function recountTotal(){
    var sum = 0;
    $('.count_price').each(function(){
        sum += parseInt($(this).html());
    });
    $('.total_sum_num').html(sum);
}

//удаление товара
$('.btn-del').click(function () { //клик на кнопку
    var id = $(this).attr('id'); //получаем id товара
//console.log(id);
    $.ajax({//аякс-запрос
        type: 'POST',//метод
        url: 'cartdel.php',//куда передаем
        data: {id_tov: id},//данные
        success: function (data) {//получаем результат
            //тут можно пересчитать сумму
            $('tr#' + id).remove();//скрываем строку таблицы
            recountTotal();
        }
    });
});