<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 24.08.2016
 * Time: 17:25
 */
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/css/allz.css">';

if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){
    Core::$limit = 15;
    if(isset($_POST['srch'])){
        $zakazi = q("
            SELECT *
            FROM `zakazi`
            WHERE `id` = '".(int)$_POST['srch']."'
        ");
        if(!$zakazi->num_rows){
            $_SESSION['error'] = 'Заказа с таким номером не существует';
            header("Location: /cab/allz");
            exit();
        }
    }else {


        $chetchik = q("
              SELECT count(*)
              FROM `zakazi`
            ");
        $chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
        $kl = $chet[0]; // вытаскиваем значение записей
        // Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
        $pk = ceil($kl / Core::$limit);
        if((isset($_GET['key1']) && (int)$_GET['key1']!=0 && $_GET['key1'] > $pk) || (isset($_GET['key1']) && (int)$_GET['key1']==0 && !isset($_GET['key2']))){
            header("Location: /404");
            exit();
        }
    if(isset($_GET['key2'])){ // если существует 2 ключ - значит нажали на показать заказ
        $zakazi = q("
                SELECT *
                FROM `zakazi`
                WHERE `login` = '".es($_GET['key1'])."' AND `id` = '".(int)$_GET['key2']."'
                LIMIT 1
        ");
        $z=$zakazi->fetch_assoc();
    }elseif(!isset($_GET['key1'])) { // иначе мы на главной и просто выводим список
        $zakazi = q("
                SELECT *
                FROM `zakazi`
                ORDER BY `id` DESC
                LIMIT 0, ".Core::$limit."
              ");
    }else{ // иначе мы на странице и выбираем по лимиту
        $zakazi = q("
                SELECT *
                FROM `zakazi`
                ORDER BY `id` DESC
                LIMIT " . ($_GET['key1'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
              ");
    }

    }

    if(isset($_POST['save'])){
        q("
        UPDATE `zakazi`
        SET `zakaz` = '".$_POST['oz']."', `dostavka` = '".es($_POST['ds'])."', `summ` ='".(int)$_POST['sum']."',
            `status` = '".es($_POST['sost'])."', `oplata` = '".es($_POST['opl'])."', `comm`= '".es($_POST['comm'])."',
            `adress` = '".$_POST['adrs']."', `phone` = '".$_POST['phone']."', `email` = '".es($_POST['email'])."'
        WHERE `id` = '".(int)$_POST['id']."'
        ");
        header("Location: /cab/allz/".$_GET['key1']."/".$_GET['key2']."");
        exit();
    }

    if(isset($_GET['key1']) && $_GET['key1'] == 'del' && isset($_GET['key2'])){
        q("
        DELETE FROM `zakazi`
        WHERE `id` = '".(int)$_GET['key2']."'
        ");
        header("Location: /cab/allz");
        exit();
    }

}else{
    header("Location: /404");
    exit();
}

?>