<?php
// Если попытаются вручную зайти во вкладки добавить\редактировать\юзеры без доступа то редиректим на
// главную
	if(!isset($_SESSION['user']) || $_SESSION['user']['access'] != 5){
		if($_GET['page'] == 'edit' || $_GET['page'] == 'add' || $_GET['page'] == 'user' || $_GET['page'] == 'user_o' || $_GET['page'] == 'addt'){
		    header("Location: /404");
			exit();
		}
    }elseif(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && $_GET['module'] == 'cab' &&$_GET['page'] == 'user_o' && !isset($_GET['key1'])){
		header("Location: /cab/user"); // Если вдруг под админом пытаются открыть какого-то юзера и
		exit();								// не ввели айдишник - редиректим в юзеры
	}
	// Если пытаются зайти в личный кабинет неавторизованными - редиректим в 404. elseif нельзя использовать т.к. в первом if уже есть проверка на существование сессии, из-за этого если сессии не существует, то выполнится 1ый if и остальные elseif будут игнорироваться
	if(!isset($_SESSION['user']) && $_GET['module'] == 'cab' && $_GET['page'] == 'lc'){
	    header("Location: /404");
		exit();
	}

	// Если нажали авторизацию и она успешна - редиректим на главную или в админку
	if(isset($_POST['login'],$_POST['pass']) && !empty($_POST['login']) && !empty($_POST['pass'])){
	 $res = q("
		SELECT *
		FROM `users`
		WHERE `login` = '".es($_POST['login'])."'
		AND `password` = '".myHash($_POST['pass'])."'
		AND `active` = 1
		LIMIT 1
	 ");
	 $r=mysqli_fetch_assoc($res);
        if(mysqli_num_rows($res)){
			// Если заходили не из под админа в обычной авторизации - редиректим туда где были
			if(Core::$SKIN == 'default' && $r['access']!=5){
			header("Location: /".$_GET['module']."");
			}
		    // Если заходили из под админки или с обычной авторизации под админом - редиректим в админку
		    if(Core::$SKIN == 'admin' || $r['access']==5)
			  header("Location: /".$_GET['module']."");
        }
    }
//--------------------------------------------------------------------------------------------------------

	// Если нажимали авторизацию с автоматической авторизацией, то создаем куки на месяц
	if(isset($_POST['login'],$_POST['pass'],$_POST['autoauth'])){
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
		  setcookie('autoauth[id]',(int)$row['id'],time()+2678400, '/');
		  setcookie('autoauth[hash]',myHash($row['login'].$row['email'].$row['id']),time()+2678400,'/');
		  setcookie('autoauth[user_agent]',$_SERVER['HTTP_USER_AGENT'],time()+2678400,'/');
		  setcookie('autoauth[ip]',$_SERVER['REMOTE_ADDR'],time()+2678400,'/');
		  q("
		  UPDATE `users` SET `hash` = '".myHash($row['login'].$row['email'].$row['id'])."'
		  WHERE `login` = '".$row['login']."'
		  ");
		  q("
		  UPDATE `users` SET `ip` = '".es($_SERVER['REMOTE_ADDR'])."'
		  WHERE `login` = '".$row['login']."'
		  ");
		  q("
		  UPDATE `users` SET `user_agent` = '".es($_SERVER['HTTP_USER_AGENT'])."'
		  WHERE `login` = '".$row['login']."'
		  ");
	    } 
    }
//--------------------------------------------------------------------------------------------------------
// Если пользователь авторизован, то обновляем его данные, на случай динамического управления, 
// например для забана
if(isset($_SESSION['user'])){
	$res = q("
			SELECT *
			FROM `users`
			WHERE `id` = ".$_SESSION['user']['id']."
			LIMIT 1
	");
	$_SESSION['user'] = mysqli_fetch_assoc($res);
	 
	// И проверяем статус юзера, если он не 1, т.е. не активен(забанен), то перенаправляем на страницу 
	// выхода
	if($_SESSION['user']['active'] != 1){
	  header ("Location: index.php?module=cab&page=exit");
	}
		// Иначе проверяем куки - если они есть, значит включена авто-авторизация
} else{ // Автоавторизация будет выполняться если сессии не существует
		// проверять возможность на автоматическую авторизацию, т.е. если была галочка установлена на авт. авторизацию, то проверяем хеши и если все ок - создаем сессию с юзером
		
		// Для безопасности и в БД и в Куках для сравнения хранить бонусом - 
		// ip $_SERVER['REMOTTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] (браузер)
	
	// Если мы раньше нажимали запомнить нас и куки существуют, то сверяемся и если все совпадает 
	// авторизуемся
	if(isset($_COOKIE['autoauth']['id'],$_COOKIE['autoauth']['hash'],$_COOKIE['autoauth']['user_agent'],$_COOKIE['autoauth']['ip'])){	
	  $res = q("
	   	 SELECT *
		 FROM `users`
		 WHERE `id` = '".es($_COOKIE['autoauth']['id'])."'
		 LIMIT 1
	     ");
	  $row = mysqli_fetch_assoc($res);

	  // Если все совпало создаем сессию что мы авторизованы
	  if($_COOKIE['autoauth']['hash'] == $row['hash']&&$_COOKIE['autoauth']['ip'] == $row['ip']&&$_COOKIE['autoauth']['user_agent'] == $row['user_agent']){
		$res = q("
			SELECT *
			FROM `users`
			WHERE `login` = '".es($row['login'])."'
			AND `hash` = '".es($row['hash'])."'
			AND `active` = 1
			LIMIT 1
		");
	   	$_SESSION['user'] = mysqli_fetch_assoc($res);
		// обновляем время
		q("
			UPDATE `users` 
			SET `lastdate` = NOW()
			WHERE `login` = '".es($row['login'])."'
		  ");
	  } else{   // Если куки не совпали - перенаправляем на выход, чтобы удалить сессии и куки
			header("Location: /index.php?module=cab&page=exit");
		}
    }
  }
//--------------------------------------------------------------------------------------------------------
  // Если существуют куки - то обновляем их динамически
 if(isset($_COOKIE['autoauth']['hash'],$_COOKIE['autoauth']['user_agent'],$_COOKIE['autoauth']['ip'],$_COOKIE['autoauth']['id'])){
	$res = q("
			SELECT *
			FROM `users`
			WHERE `id` = '".$_SESSION['user']['id']."'
			LIMIT 1
		   ");
	$row = mysqli_fetch_assoc($res);
	
		setcookie('autoauth[id]',$row['id'],time()+2678400,'/');
		setcookie('autoauth[hash]',myHash($row['login'].$row['email'].$row['id']),time()+2678400,'/');
		setcookie('autoauth[user_agent]',$_SERVER['HTTP_USER_AGENT'],time()+2678400,'/');
		setcookie('autoauth[ip]',$_SERVER['REMOTE_ADDR'],time()+2678400,'/');
  }
?>