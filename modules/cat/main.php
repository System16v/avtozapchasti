<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 17.08.2016
 * Time: 11:43
 */
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/css/cat.css">';
Core::$META['title'] = 'Корзина Автозапчасти Новошахтинск';
// Если нажали удалить 1 товар - то удаляем и уменьшаем кол-во товаров на 1
if(isset($_GET['key1']) && $_GET['key1'] == 'del' && isset($_GET['key2'])) {
    setcookie('addt['.$_GET['key2'].'][kol]','', time()-3600,'/');
    setcookie('klt',$_COOKIE['klt']-1,time()+172800,'/');
    header("Location: /cat");
    exit();
}

// Если нажали удалить все - удаляем все
if(isset($_POST['deleteAll'])) {
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
    header("Location: /cat");
    exit();
}

// Если существуют куки - то вытаскиваем айдишники добавленных товаров в корзину
if(isset($_COOKIE['addt'])) {
    $num = '';
    $m = count($_COOKIE['addt']); // считаем сколько всего товаров
    $z = 1; // делаем счетчик
    foreach ($_COOKIE['addt'] as $k => $v) {
        if($z!=$m) { // если у нас не последний товар, то перечисляем айдишники через запятую
            $num .= (int)$k . ',';
        }else{
            $num .= (int)$k; // в противном случае у нас последний товар, и запятая не нужна
        }
        ++$z;
    }

    $res = q("
        SELECT *
        FROM `tovari`
        WHERE `id` IN (" . $num . ")
    ");
}
// Если существую куки с айдишниками и количеством товаров - значит нажали пересчет, обновляем куки и редиректим на себя
if(isset($_POST['ids']) && isset($_POST['kol'])) {
    foreach ($_POST['ids'] as $k=>$v){
        foreach ($_POST['kol'] as $k1=>$v1){
            if($k==$k1) // т.е. ключи совпали, значит мы нашли необходимое совпадение айдишника товара и его количества
                // поэтому меняем количество товара на введенное при пересчете
                setcookie('addt['.$v.'][kol]',(int)$v1,time()+172800,'/');
        }
    }
    // Если просто нажимали пересчитать - то редиректим на себя, в противном случае нажали оформить заказ, поэтому
    // редиректим на оформить
    if(!isset($_POST['zakaz'])) {
        header("Location: /cat");
        exit();
    }else{
        header("Location: /cat/of");
        exit();
    }
}

