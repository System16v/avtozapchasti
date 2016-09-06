<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 24.08.2016
 * Time: 14:09
 */
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/css/lc.css">';
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/skins/default/css/users.css">';
Core::$limit = 2;

if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){
// Вытаскиваем по айдишнику логин
    if(isset($_GET['key1'])) {
        $res = q("
			SELECT `login`, `id`
			FROM `users`
			WHERE `id`='" . $_GET['key1'] . "'
			LIMIT 1
		");
        if(!$res->num_rows){
            header("Location: /404");
            exit();
        }
        $row = $res->fetch_assoc();

        $chetchik = q("
              SELECT count(*)
              FROM `zakazi`
              WHERE `login` = '" . es($row['login']) . "'
            ");
        $chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
        $kl = $chet[0]; // вытаскиваем значение записей
        // Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
        $pk = ceil($kl / Core::$limit);

        if((isset($_GET['key2']) && $_GET['key2'] > $pk) || (isset($_GET['key2']) && (int)$_GET['key2'] == 0) || (isset($_GET['key2']) && preg_match('#^[0-9]+[a-zа-яё\/\-+\,\*\`]+$#ui',$_GET['key2']))){
            header("Location: /404");
            exit();
        }

        if(!isset($_GET['key2'])) {
            // Вытаскиваем все заказы по логину
            $resz = q("
            SELECT *
            FROM `zakazi`
            WHERE `login` = '" . es($row['login']) . "'
            ORDER BY `data` DESC
            LIMIT 0, ".Core::$limit."
            ");
        }else{
            $resz = q("
            SELECT *
            FROM `zakazi`
            WHERE `login` = '" . es($row['login']) . "'
            ORDER BY `data` DESC
            LIMIT " . ($_GET['key2'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
            ");
        }
    }else{
        header("Location: /");
        exit();
    }
    // если что-то изменяли и нажали сохранить - обновляем данные заказа
    if(isset($_POST['save'])){
        q("
        UPDATE `zakazi`
        SET `zakaz` = '".$_POST['oz']."', `dostavka` = '".es($_POST['ds'])."', `summ` ='".(int)$_POST['sum']."',
            `status` = '".es($_POST['sost'])."', `oplata` = '".es($_POST['opl'])."', `comm`= '".es($_POST['comm'])."',
            `adress` = '".$_POST['adrs']."', `phone` = '".$_POST['phone']."', `email` = '".es($_POST['email'])."'
        WHERE `id` = '".(int)$_POST['id']."'
        ");
        header("Location: /cab/zakazi_us/".$_GET['key1']."");
        exit();
    }

    if(isset($_GET['key3']) && $_GET['key3'] == 'del' && isset($_GET['key4'])){
        q("
        DELETE FROM `zakazi`
        WHERE `id` = '".(int)$_GET['key4']."'
        ");
        header("Location: /cab/zakazi_us/".$row['id']."");
        exit();
    }
}else{
    header("Location: /404");
    exit();
}
?>