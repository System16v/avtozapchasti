/**
 * Created by System16v on 04.08.2016.
 */
// Открвываем или скрываем блок авторизации
    function auth() {
        if (document.getElementById('at').style.display == 'none') {
            document.getElementById('at').style.display = 'block';
        } else {
            document.getElementById('at').style.display = 'none';
        }
    }
// Открываем или скрываем блок для восстановления пароля
    function rpass() {
        $('#rpass').toggle();
        auth();
    }

window.onload = function () {

};

// Для формы поиска - при фокусе делаем цвет шрифта черным и очищаем форму
function och() {
    document.getElementById('search').style.color = '#000000';
    document.getElementById('search').value = '';
} // при потере фокуса - делаем цвет текста серым и пишем поиск
function och2() {
    document.getElementById('search').style.color = '#6B6B6B';
    document.getElementById('search').value = 'Поиск...';
}
// Выводим картинки товаров в отдельном окне при нажатии на ссылку через fancybox
$(document).ready(function() {
    $("a.gallery").fancybox();
});
// Добавление товара в корзину, путем создания COOKIE
function addc(id) {
    var x = id.dataset.title; // вытаскиваем айдишник товара из атрибута data-title
    $.ajax({
        url: '/modules/cat/add.php', // передаем данные в корзину
        type: "POST", // тип передачи данных
        cache: false, //
        data: {id: x},
        timeout: 15000, // если запрос не обработается в течение 15 секунд, то мы обрываем соединение и не ждем ответа
        success: function (msg) {
        cat(); // если товар добавили - то вызываем и меняем текст возле корзины
        }
    });
}
// Функция для вытаскивания значения куки
function getCookie(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset);
            if (end == -1) {
                end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return(setStr);
}
// Меняем текст возле корзины
function cat() {
    $.ajax({
        url: '/modules/cat/add.php', // передаем данные в корзину
        type: "POST", // тип передачи данных
        cache: false, //
        data: {},
        timeout: 15000, // если запрос не обработается в течение 15 секунд, то мы обрываем соединение и не ждем ответа
        success: function (msg) {
            // если товар добавили - то вызываем и меняем текст возле корзины
            var t;
            if(getCookie('klt') == 1){
                t = 'товар';
            }else if(getCookie('klt') == 2 || getCookie('klt') == 3 || getCookie('klt') == 4){
                t = 'товара';
            }else{
                t = 'товаров';
            }
            document.getElementById('k').innerHTML = 'В корзине<br>' + getCookie('klt') + ' ' + t;
            document.getElementById('kimg').innerHTML = '<img src="/img/catt.png" alt="коризна">';
        }
    });
}
// Функция для прибавления к сумме заказа за доставку, а так же открытия и скрытия блока адреса
$(function() {
    var sum = $('#sum').text(); // создаем переменную с суммой которая получилась при счете
    var sumMoney = parseFloat(sum); // создаем переменную численную

    $('.develery').on('change', function() { // СОБЫТИЕ: при изменении значения радиобаттона
        changeSumm($(this).val()); // прибавить значение радиобаттона которого нажали к итоговой сумме из переменной
        var value = $(this).val(); // присваиваем переменной значение радиобаттона
        if(value == 60 || value == 100){ // если у нас 60 или 100, значит выбрали доставку на дом, значит открываем блок ввода адреса
            $('tr#address').show();
        } else{ // в противном случае скрываем его
            $('tr#address').hide();
        }
    });
    function changeSumm(val) {
        var result = 0.00; // результат, ноль изначально
        if (val) { // если передали значение радиобаттона, то прибавляю его к результату
            result += parseFloat(val);
        }
        result += sumMoney; // а это прибавление сумму из атрибута
        $('#sum').text(result); // выводим сумму на экран
    }
});
// Функция для изменения статуса наличия товара и замены корзины
$(function() {
    $('.nlc').on('change', function() {
        var value = $(this).val(); // присваиваем значение нажатой радиокнопки
        var form = $(this).parents('.nal'); // присваиваем родителя ячейки с классом nal (где радиокнопка)нажатой радиокнопки
        var status = form.find('.status'); // ищем класс статус у родителя т.е. где лежит наша картинка
        var id = form.find('.id');     // ищем класс айди у родителя
        var idv = id.val(); // и записываем значение value (айдишник)
        var korz = $(this).parents('.nal').parent(); // перебираемся по дереву и находим ближайшего вышестоящего родителя т.е. строку tr
        var kor = korz.find('.cat'); // и по дереву находим объект cat, где находится корзина
        var k = kor.find('.korzina'); // ищем в ячейке с классом корзина div с классом корзина

        if(value == 'В наличии') { // и если у нас переданная кнопка в наличии - оставляем эту же картинку, т.к.
            // изначально радиокнопка выбрана, и выбирая другую - value будет противоположным, т.е. если у нас товар был
            // в наличии, изменяя его на НЕТ, у value будет значение Под заказ, и наоборот. Т.е. какую радиокнопку нажали -
            // такую и картинку нарисовали
            $.ajax({
                url: '/modules/cat/updnl.php', // передаем данные в корзину
                type: "POST", // тип передачи данных
                cache: false, //
                data: {upd: 'да', id: idv}, // передаем постом айдишник и статус
                timeout: 15000, // если запрос не обработается в течение 15 секунд, то мы обрываем соединение и не ждем ответа
                success: function (msg) {
                    // меняем содержимое блока статус который в объекте на который нажимали
                    status.html('<img src="/img/nlm.png" alt="В наличии" title="В наличии" >');
                    k.html('<a href="/" onclick="return false;"><img src="/img/catpm.png" alt="корзина" data-title="'+idv+'" title="Добавить в корзину" onclick="addc(this)"></a>');
                }
            });
        } else {
            $.ajax({
                url: '/modules/cat/updnl.php', // передаем данные в корзину
                type: "POST", // тип передачи данных
                cache: false, //
                data: {upd: 'под заказ', id: idv}, // передаем постом айдишник и статус
                timeout: 15000, // если запрос не обработается в течение 15 секунд, то мы обрываем соединение и не ждем ответа
                success: function (msg) {
                    status.html('<img src="/img/zkm.png" alt="Под заказ" title="Под заказ" >');
                    k.html('<a href="/cat/zakaz/'+idv+'"><img src="/img/cat.png" alt="корзина" data-title="'+idv+'" title="Заказать"></a>');
                }
            });
          }
    });
});
// скрываем или показываем заказы
 $(function() {
    $('.inz').click(function() {
        var form = $(this).parents('.opis2');
        var f = form.find('.ops');
   f.toggle(); // скрываем или показываем форму
});
});
