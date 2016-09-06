<?php
error_reporting(-1); // чтобы если в коде есть какие-то ошибки - то о них выводилось на экран и в логи

ini_set('session.cookie_lifetime',10);
session_start();  // Включение сессий, если этого не писать то с сессиями нельзя работать
echo '<pre>';
print_r($_COOKIE);
echo '<br>SESSION:<br> ';
print_r($_SESSION['user']);
echo '</pre>';
?>