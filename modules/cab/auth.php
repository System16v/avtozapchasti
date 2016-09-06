<?php

// Авторизация, проверяем если существует логин\пароль, то проверяем, есть ли такой юзер с логином\паролем в БД и активен ли он, и если есть то активируем
  if(isset($_POST['login'],$_POST['pass']) && !empty($_POST['login']) && !empty($_POST['pass'])){
	 $res = q("
		SELECT *
		FROM `users`
		WHERE `login` = '".es($_POST['login'])."'
		AND `password` = '".myHash($_POST['pass'])."'
		AND `active` = 1
		LIMIT 1
	 ");
  if(mysqli_num_rows($res)){
	  $row = mysqli_fetch_assoc($res);
	  if(Core::$SKIN == 'default')
		  $_SESSION['user'] = $row;
	  // И записываем дату для мониторинга когда пользователь последний раз заходил на сайт
	  q("
			UPDATE `users` 
			SET `lastdate` = NOW(), `ip` = '".$_SERVER['REMOTE_ADDR']."'
			WHERE `login` = '".es($row['login'])."'
		");
  } else {
	   $error = 'Не верный логин или пароль';
  }
 }else if(isset($_POST['login'],$_POST['pass']) && (empty($_POST['login']) || empty($_POST['pass']))){
     $error = 'Вы не ввели логин или пароль';
  }
// для автоматической авторизации в куках необходимо хранить айдишник юзера + его хеш для авторазиции,
// например хеш логина,емаила и айдишника, необходимо галка для авт. авторизации, и если галка стоит, то
// создаем хеш в БД и авторизируем (все реализовано в allpages)
?>