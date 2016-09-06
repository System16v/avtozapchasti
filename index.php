<?php

header('Content-Type: text/html;charset=utf-8'); // кодировка
ini_set('log_errors', 'On');
ini_set('error_log', './logs/php.log');
error_reporting(-1); // чтобы если в коде есть какие-то ошибки - то о них выводилось на экран и в логи
ini_set('session.cookie_lifetime',1200);
session_start();  // Включение сессий, если этог оне писать то с сессиями нельзя работать

// Конфиг для сайта
include_once './config.php';
include_once './libs/default.php';
include_once './libs/default2.php';
include_once './variables.php';

if(isset($_POST['ext'])){
	header("Location: ./cab/exit");
}

if(isset($_SESSION['user']) && $_SESSION['user']['access']==2){
    exit('Вы забанены');
}
ob_start();
	include './'.Core::$CONT.'/allpages.php';
	if(!file_exists('./'.Core::$CONT.'/'.$_GET['module'].'/'.$_GET['page'].'.php') || !file_exists('./skins/'.Core::$SKIN.'/'.$_GET['module'].'/'.$_GET['page'].'.tpl')){
		header("Location: /404");
		exit();
	}
if(!isset($_SESSION['user']) && !isset($_COOKIE['autoauth'])) {
	include './'.Core::$CONT.'/cab/auth.php';
	include './skins/'.Core::$SKIN.'/cab/auth.tpl';
}

	include './'.Core::$CONT.'/'.$_GET['module'].'/'.$_GET['page'].'.php';
	include './skins/'.Core::$SKIN.'/'.$_GET['module'].'/'.$_GET['page'].'.tpl';


$content = ob_get_contents();
ob_end_clean();

if(isset($_GET['ajax'])){
	echo $content;
	exit;
}
include './skins/'.Core::$SKIN.'/index.tpl';

?>

