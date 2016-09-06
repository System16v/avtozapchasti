<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 18.08.2016
 * Time: 16:58
 */
include './modules/cat/main.php';
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/css/cat.css">';
Core::$META['title'] = 'Оформление заказа Автозапчасти Новошахтинск';
// Если нажали создать заказ, то проверяем введенные данные
if(isset($_POST['zakazok'])){
     // создаем массив ошибок
    $error = array();
    // если поле имя пустое и при это юзер не авторизован то создаем ошибку в имени для вывода
    if(empty($_POST['imya']) && !isset($_SESSION['user'])){
        $error['imya'] = 'Вы не ввели имя';
    }else{
        if(isset($_POST['imya']) && !preg_match('#^[а-яa-z0-9\-_]+$#ui',$_POST['imya'])){
            $error['imya'] = 'Символы ` / \ + * . ) ( \' запрещены';
        }
    }
    // если поле телефона пустое то создаем ошибку телефона
    if(empty($_POST['tel'])){
        $error['tel'] = 'Вы не ввели телефон';
    }else{ // иначе если поле содержит телефон, проверяем его на правильность ввода, если формат не телефона - создаем ошибку
        if(!preg_match('#^( +)?((\+?7|8) ?)?((\(\d{3}\))|(\d{3}))?( )?(\d{3}[\- ]?\d{2}[\- ]?\d{2})( +)?$#ui',$_POST['tel'])){
            $error['tel'] = 'Вы ввели телефон не правильно';
        }
    }
    // если поле емаил пустое и при этом юзер не авторизован, то создаем ошибку
    if(empty($_POST['email']) && !isset($_SESSION['user'])){
        $error['email'] = 'Вы не ввели e-mail';
    }else{ // иначе проверяем если юзер не авторизован на валидацию емаила, если введено не правильно, то создаем ошибку
        if(!isset($_SESSION['user']) && !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $error['email'] = 'Вы ввели некорректный email';
        }
    }
    // проверяем наличие выбранной доставки, если ни один не выбран - то создаем ошибку
    if(!isset($_POST['som'])){
        $error['som'] = 'Вы не выбрали доставку';
    }else{ // в противном случае проверяем что выбрали, если выбрал на дом или срочную, но при этом поле адреса пустое - создаем ошибку
        if(($_POST['som'] == '60' || $_POST['som'] == '100') && empty($_POST['addrs'])){
            $error['addrs'] = 'Вы не ввели адрес доставки,<br> введите адрес, или выберете<br> вариант доставки - Самовывоз';
        }
    }
    // после проверок ошибок, проверяем есть ли у нас хотя бы одна ошибка, если ошибок нет, выбираем товары из корзины по айдишника в куках
    if(!count($error)) {
        if(isset($_COOKIE['addt'])) {
            $num2 = '';
            $m2 = count($_COOKIE['addt']); // считаем сколько всего товаров
            $z2 = 1; // делаем счетчик
            foreach ($_COOKIE['addt'] as $k => $v) {
                if($z2!=$m2) { // если у нас не последний товар, то перечисляем айдишники через запятую
                    $num2 .= (int)$k . ',';
                }else{
                    $num2 .= (int)$k; // в противном случае у нас последний товар, и запятая не нужна
                }
                ++$z2;
            }
            // выбираем из БД товары по айдишникам которые были в корзине
            $res2 = q("
                      SELECT *
                      FROM `tovari`
                      WHERE `id` IN (" . $num . ")
                    ");
            // если у нас товаров таких не оказалось - выводим ошибку
            if(!$res2->num_rows){
                echo 'Вы не выбрали товары';
                exit();
            }else{ // в противном случае, вытаскиваем все товары что были выбраны и записываем их в переменную
                while ($r2= $res2->fetch_assoc()){
                  if(!isset($zk)){
                    $zk = '';
                  }
                  $zk .= $r2['name'].' '.$r2['model'].' '.$r2['firm'].' '.$_COOKIE['addt'][$r2['id']]['kol'].' шт. * '.$r2['cena'].',00р.<br>';
                    if(!isset($sum)){
                        $sum = 0;
                    }
                    $sum += $r2['cena'] * $_COOKIE['addt'][$r2['id']]['kol'];
                }
            }
        }
        if($_POST['som'] == 0){
            $dostavka = 'Самовывоз';
        }else if($_POST['som'] == 60){
            $dostavka = 'На дом';
            $sum +=60;
        }else{
            $dostavka = 'Срочная';
            $sum +=100;
        }
        // смотрим авторизован ли пользователь, если да, то добавляем в БД заказ, указывая его ник и емаил из сессии
        if(!isset($_SESSION['user'])) {
            q("
              INSERT INTO `zakazi`
              SET `login` = '".es($_POST['imya'])."', `zakaz` = '" . $zk . "', `phone` = '".$_POST['tel']."', `email` = '".es($_POST['email'])."',
              `adress` = '".es($_POST['addrs'])."', `dostavka` = '".$dostavka."', `summ` = $sum, `comm` = '".es($_POST['comm'])."', `data` = NOW()
             ");
            // и удаляем все куки
            foreach ($_COOKIE['addt'] as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    setcookie('addt[' . $k . '][' . $k1 . ']', '', time() - 3600,'/');
                }
            }
            foreach ($_COOKIE['addt'] as $k => $v) {
                setcookie('addt[' . $k . ']', '', time() - 3600,'/');
            }
            setcookie('addt[]', '', time() - 3600,'/');
            setcookie('klt', '', time() - 3600,'/');
            // вытаскиваем последний добавленный айдишник, т.е. айдишник только что созданного заказа
            $id = DB::_()->insert_id;
            // отправляем письмо на почту
            Meil::$to = $_POST['email'];
            Meil::$subject = 'Вами был создан заказ';
            Meil::$text = 'Вами был оформлен заказ. Вашему заказу был присвоен № '.$id.'.<br>В ближайшее время мы свяжемся с Вами, по телефону, который вы указали при оформлении заказа.';
            Meil::send();

            Meil::$to = 'System88_@mail.ru';
            Meil::$subject = 'Cоздан заказ';
            Meil::$text = 'Заказ № - '.$id;
            Meil::send();

            // создаем сессию что все ок, и заказ создан, и редиректим на страницу где вывод что заказ создан
            $_SESSION['zakaz'] = 'ok';
            header("Location: /cat/ok");
            exit();
        }else{ // если пользователь не авторизован, то создаем заказ вводя все данные из формы
            q("
              INSERT INTO `zakazi`
              SET `login` = '".$_SESSION['user']['login']."', `zakaz` = '" . $zk . "', `phone` = '".$_POST['tel']."', `email` = '".es($_SESSION['user']['email'])."',
              `adress` = '".es($_POST['addrs'])."', `dostavka` = '".$dostavka."', `summ` = $sum, `comm` = '".es($_POST['comm'])."', `data` = NOW()
             ");
            $id = DB::_()->insert_id;

            foreach ($_COOKIE['addt'] as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    setcookie('addt[' . $k . '][' . $k1 . ']', '', time() - 3600,'/');
                }
            }
            foreach ($_COOKIE['addt'] as $k => $v) {
                setcookie('addt[' . $k . ']', '', time() - 3600,'/');
            }
            setcookie('addt[]', '', time() - 3600,'/');
            setcookie('klt', '', time() - 3600,'/');

            Meil::$to = $_SESSION['user']['email'];
            Meil::$subject = 'Вами был создан заказ';
            Meil::$text = 'Вами был оформлен заказ. Вашему заказу был присвоен № '.$id.'.<br>В ближайшее время мы свяжемся с Вами, по телефону, который вы указали при оформлении заказа. Состояние заказа можно посмотреть в личном кабинете.';
            Meil::send();

            Meil::$to = 'System88_@mail.ru';
            Meil::$subject = 'Cоздан заказ';
            Meil::$text = 'Заказ создал пользователь - '.$_SESSION['user']['login'].' Заказ № '.$id;
            Meil::send();

            $_SESSION['zakaz'] = 'ok';
            header("Location: /cat/ok");
            exit();
        }
    }
}