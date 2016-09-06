<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 23.08.2016
 * Time: 11:55
 */


if($_GET['page'] == 't' && isset($_GET['key1']) && isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){
    // Если нажали удалить товар - удаляем
    if($_GET['key1'] == 'del' && isset($_GET['key2'])){
        q("
        DELETE
        FROM `tovari`
        WHERE `id` = '".(int)$_GET['key2']."'
        ");
        header("Location: /static/main/Каталог товаров");
        exit();
    }
    // Если нажали удалить фото - удаляем
    if(isset($_GET['key1']) && $_GET['key1'] == 'img'){
        // вытаскиваем путь картинки чтобы удалить картинку из папки
        $im = q("
                SELECT `img`
                FROM `tovari`
                WHERE `id` = '".(int)$_GET['key2']."'
              ");
        $img = $im->fetch_assoc();
        // удаляем путь в БД
        q("
            UPDATE `tovari` 
            SET `img` = ''
            WHERE `id` = '".(int)$_GET['key2']."'
        ");
        // удаляем файл картинки
        unlink('.'.$img['img']);
        header("Location: /static/main/Каталог товаров");
        exit();
    }

    $t = q("
        SELECT *
        FROM `tovari`
        WHERE `id` = '".(int)$_GET['key1']."'
    ");
    $c = q("
        SELECT *
        FROM `categorii`
    ");

    if(!$t->num_rows){
        header("Location: /404");
        exit();
    }else{
        $tr = $t->fetch_assoc();
    }
}else{
    header("Location: /404");
    exit();
}
if(isset($_POST['okt']) && isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){
    q("
    UPDATE `tovari`
    SET `name` = '".es($_POST['nm'])."', `model` = '".es($_POST['m'])."', `firm` = '".es($_POST['fr'])."',
    `cena` = '".es($_POST['cn'])."', `cat` = '".es($_POST['ct'])."'
    WHERE `id` = '".(int)$_GET['key1']."'
    ");
    header("Location: /static/main/Каталог товаров");
    exit();
}