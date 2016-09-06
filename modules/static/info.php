<?php
Core::$META['title'] = 'Информация Автозапчасти Новошахтинск';
Core::$META['description'] = 'Текущие новости и поступление нового товара, Автозапчасти Новошахтинск.';
Core::$META['keywords'] = 'Автозапчасти запчасти Новошахтинск Ваз Ока иномарки';

Core::$limit = 4;

$chetchik = q("
              SELECT count(*)
              FROM `news`
            ");
$chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
$kl = $chet[0]; // вытаскиваем значение записей
// Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
$pk = ceil($kl / Core::$limit);

    if(isset($_GET['key1']) && (preg_match('#^[a-zа-яё\/\-+\,\*\`]+[0-9]+$#ui',$_GET['key1']) || preg_match('#^[0-9]+[a-zа-яё\/\-+\,\*\`]+$#ui',$_GET['key1']))){
        header("Location: /404");
        exit();
    }
    if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && isset($_GET['key1']) && $_GET['key1'] == 'del'){
    q("
    DELETE FROM `news`
    WHERE `id` = '".(int)$_GET['key2']."'
    ");
        header("Location: /static/info");
        exit();
    }

if(!isset($_GET['key1'])) {
    $res = q("
        SELECT *
        FROM `news`
        ORDER BY `data` DESC 
        LIMIT 0, ".Core::$limit."
    ");
}else{
    $res = q("
        SELECT *
        FROM `news`
        ORDER BY `data` DESC
        LIMIT " . ($_GET['key1'] * Core::$limit - Core::$limit) . ", ".Core::$limit."
    ");
}
    if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && isset($_POST['addnews'])){
        q("
        INSERT INTO `news`
        SET `news` = '".es($_POST['news'])."', `data` = NOW()
        ");
        header("Location: /static/info");
        exit();
    }
    if(isset($_GET['key2']) || ((isset($_GET['key1'])) && (int)$_GET['key1'] > $pk)){
        header("Location: /404");
        exit();
    }
?>