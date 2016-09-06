<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 30.08.2016
 * Time: 12:01
 */
if((isset($_GET['key1'],$_GET['key2']) || isset($_POST['login'],$_POST['email'])) && !isset($_SESSION['user'])) {
    $errors = array();
    // проверяем почту
    if (isset($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Не верно введен email';
    }
    //если ошибок нет - делаем запрос
    if (!count($errors) && isset($_POST['login'])) {
        $res = q("
            SELECT *
            FROM `users`
            WHERE `login` = '" . es($_POST['login']) . "' AND `email` = '" . es($_POST['email']) . "'
            LIMIT 1
           ");
        // если такой юзер с и емаилом не существует, создаем ошибку
        if (!$res->num_rows) {
            $errors['login'] = 'Не верно введен логин или E-mail, или данный email с логином не зарегистрирован';
        } else { // в противном случае такой юзер есть - высылаем ему письмо на указанный ящик с хешем логина и пароля
            $r = $res->fetch_assoc();

            Meil::$to = $_POST['email']; // отсылаем на почту хеш для изменения пароля
            Meil::$subject = 'Восстановление пароля';
            Meil::$text = 'Для изменения пароля перейдите по ссылке ' . Core::$DOMAIN . '/cab/rpass/' . $r['id'] . '/' . myHash($_POST['login'] . $_POST['email']) . ' , если вы не запрашивали восстановление пароля, то проигнорируйте данное сообщение'; // отправляем на почту текст с ссылкой для
            // подтверждения регистрации
            Meil::send();            // отправляем письмо
            $_SESSION['rp'] = 'OK';  // Для смены формы поле регистрации, и вывода на экран успешного завершения
            header("Location: /");
            exit();
        }
    }
    // если перешли по ссылке которая была в письме, делаем запрос по айдишнику
    if(isset($_GET['key1']) && !preg_match('#^[0-9]+[a-zа-яё\/\-+\,\*\`]+$#ui',$_GET['key1'])) {
        if (isset($_GET['key1'], $_GET['key2'])) {
            $rs = q("
        SELECT *
        FROM `users`
        WHERE `id` = '" . (int)$_GET['key1'] . "'
        LIMIT 1
        ");
            if (!$rs->num_rows) {
                header("Location: /404");
                exit();
            }
            $rsp = $rs->fetch_assoc();
            // если хеши в БД и по ссылке не совпали - редиректим на 404
            if (myHash($rsp['login'] . $rsp['email']) != $_GET['key2']) {
                header("Location: /404");
                exit();
            }
        }
    }else{
        header("Location: /404");
        exit();
    }
    if(isset($_POST['npass'],$_POST['n2pass']) && !empty($_POST['npass']) && !empty($_POST['n2pass'])){
        if($_POST['npass'] != $_POST['n2pass']){
            $errors['pass'] = 'Введенные пароли не совпадают';
        }else{
            q("
                UPDATE `users`
                SET `password` = '".myHash($_POST['npass'])."'
                WHERE `id` = '".$_GET['key1']."'
            ");
            $_SESSION['npass'] = 'OK';
            header("Location: /");
            exit();
        }
    }elseif(isset($_POST['npass'],$_POST['n2pass']) && (empty($_POST['npass']) || empty($_POST['n2pass']))){
        $errors['pass'] = 'Вы не ввели пароль';
    }

}else{
    header("Location: /404");
    exit();
}