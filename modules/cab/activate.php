<?php
// Если существует хэш, то изменяем в БД в поле active на 1 (значит активирован), тому пользователю, чей хеш равен хешу при регистрации и айдишнику
 if(isset($_GET['key1'],$_GET['key2'])){
	 q("
		UPDATE `users` SET `active` = 1, `access` = 1
		WHERE `id` = ".(int)$_GET['key1']."
		AND `hash` = '".es($_GET['key2'])."'
	 ");
	 $info = 'Активация прошла успешно!';
 } else{
	 $info = 'Вы прошли по неверной ссылке';
 }
?>