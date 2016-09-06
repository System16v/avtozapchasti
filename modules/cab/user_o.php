<?php 
if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/skins/default/css/users.css">';
 $res = q("
			SELECT * FROM `users`
			WHERE `id`='".$_GET['key1']."'
			LIMIT 1
		");

 $row = mysqli_fetch_assoc($res);

    if(isset($_GET['key1'],$_GET['key2']) && $_GET['key2'] == 'delav') {
        // вытаскиваем путь картинки чтобы удалить картинку из папки
        $im = q("
                SELECT `img`
                FROM `users`
                WHERE `id` = '".$_GET['key1']."'
              ");
        $img = $im->fetch_assoc();

        q("
          UPDATE `users`
          SET `img` = ''
          WHERE `id` = '".$_GET['key1']."'
    ");

        unlink('.'.$img['img']);
        header("Location: /cab/user_o/".$_GET['key1']."");
        exit();
    }


 // Если нажали отредактировать, то проверяем
 if(isset($_POST['redct'])){
   foreach($_POST as $k=>$v)   // Убираем в переданных значениях пробелы в начале и конце строки
		$_POST[$k]=trim($v);
   // Если изменился емаил - то изменяем (сделано так потому что ругается индекс в MySQL что есть дубли)
   if($_POST['email']!=$row['email'])
     q("
	     UPDATE `users` SET
	     `email` = '".es($_POST['email'])."'
	     WHERE `id` = '".$_GET['key1']."'
     ");
   // Если изменился логин - меняем логин
   if($_POST['login']!=$row['login'])
     q("
	     UPDATE `users` SET
	     `login` = '".es($_POST['login'])."'
	     WHERE `id` = '".$_GET['key1']."'
     ");
   // Если изменился доступ с неактивного или активного на забанен - то меняем доступ
   if(($row['active']==0 || $row['active']== 1) && isset($_POST['ds']) && $_POST['ds'] == 'Забанить')
     q("
	     UPDATE `users` SET
	     `active` = 2
	     WHERE `id` = '".$_GET['key1']."'
     ");
	// Если изменился доступ с неактивного или забаненого на разрешен - то меняем доступ
    if(($row['active']==0 || $row['active']== 2) && isset($_POST['ds']) && $_POST['ds'] == 'Разрешён')
     q("
	     UPDATE `users` SET
	     `active` = 1
	     WHERE `id` = '".$_GET['key1']."'
     ");
    // Если изменились права с пользователя или неактивного - меняем на админа
    if(($row['access']==1 || $row['access']==0) && isset($_POST['sel']) && $_POST['sel'] == 'Администратор')
     q("
  	     UPDATE `users` SET
	     `access` = 5
	     WHERE `id` = '".$_GET['key1']."'
     ");
	// Если изменились права с неактивного или админа - то меняем на пользователя
    if(($row['access']==0 || $row['access']==5) && isset($_POST['sel']) && $_POST['sel'] == 'Пользователь')
      q("
	    UPDATE `users` SET
	     `access` = 1
	     WHERE `id` = '".$_GET['key1']."'
       ");
	 // Если изменились права с пользователя или админа на неактивного - меняем на неактивного
     if(($row['access']==1 || $row['access']==5) && isset($_POST['sel']) && $_POST['sel'] == 'Не активирован')
       q("
	     UPDATE `users` SET
	     `access` = 0
	     WHERE `id` = '".$_GET['key1']."'
     ");
    // Если поле пароль не пустое - меняем пароль
    if(!empty($_POST['pass'])){
      q("
	     UPDATE `users` SET
	     `password` = '".myHash(hc($_POST['pass']))."'
	     WHERE `id` = '".$_GET['key1']."'
	  ");
     };
	// Если изменяли что-то - редиректим назад
    header("Location: /cab/user");
 };
 
 // Если нажали удалить юзера - удаляем
 if(isset($_POST['dell'])){
   q("
		DELETE FROM `users` 
		WHERE `login` = '".$_POST['login']."'
   ");
	header("Location: /cab/user");
 };
}else{
    header("Location: /404");
    exit();
};
?>