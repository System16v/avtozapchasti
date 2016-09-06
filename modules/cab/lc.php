<?php
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/css/lc.css">';
Core::$META['title'] = 'Личный кабинет Автозапчасти Новошахтинск';
Core::$limit = 3;

// считаем кол-во заказов
$chetchik = q("
              SELECT count(*)
              FROM `zakazi`
              WHERE `login` = '" .es($_SESSION['user']['login']). "' AND `email` = '".es($_SESSION['user']['email'])."'
            ");
$chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
$kl = $chet[0]; // вытаскиваем значение записей
// Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
$pk = ceil($kl / Core::$limit);

if((isset($_GET['key2']) && $_GET['key2'] > $pk) || (isset($_GET['key2']) && (int)$_GET['key2'] == 0) || (isset($_GET['key2']) && preg_match('#^[0-9]+[a-zа-яё\/\-+\,\*\`]+$#ui',$_GET['key2']))){
    header("Location: /404");
    exit();
}

// Если нажали на мои заказы, проверяем айдишник введенный с сессией если совпали - значит показываем заказы
// если у нас отутствует страница - значит выводим по лимиту первую
if(!isset($_GET['key2']) && isset($_SESSION['user']) && isset($_GET['key1'])){
    if($_SESSION['user']['id'] == $_GET['key1']){
        $res = q("
                SELECT *
                FROM `zakazi`
                WHERE `login` = '".es($_SESSION['user']['login'])."' AND `email` = '".es($_SESSION['user']['email'])."'
                ORDER BY `data` DESC
                LIMIT 0,".Core::$limit."
                ");
        Core::$META['title'] = 'Мои заказы Автозапчасти Новошахтинск';
    }else{
        header("Location: /404");
        exit();
    }
}elseif(isset($_GET['key2']) && isset($_SESSION['user']) && isset($_GET['key1'])){ // иначе у нас есть страница
    if($_SESSION['user']['id'] == $_GET['key1']){
        $res = q("
                SELECT *
                FROM `zakazi`
                WHERE `login` = '".es($_SESSION['user']['login'])."' AND `email` = '".es($_SESSION['user']['email'])."'
                ORDER BY `data` DESC
                LIMIT " . ($_GET['key2'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
                ");
        Core::$META['title'] = 'Мои заказы Автозапчасти Новошахтинск';
    }else{
        header("Location: /404");
        exit();
    }
}

if(isset($_SESSION['user']) && isset($_GET['key1'],$_GET['key2']) && $_GET['key2'] == 'delav') {
    // вытаскиваем путь картинки чтобы удалить картинку из папки
    $im = q("
                SELECT `img`
                FROM `users`
                WHERE `id` = '".$_SESSION['user']['id']."'
              ");
    $img = $im->fetch_assoc();

    q("
    UPDATE `users`
    SET `img` = ''
    WHERE `id` = '".$_SESSION['user']['id']."'
    ");

    unlink('.'.$img['img']);
    header("Location: /cab/lc");
    exit();
}
    if(isset($_SESSION['user'])){
		$rc = q("
				SELECT * FROM `users`
				WHERE `id` = '".$_SESSION['user']['id']."'
			  ");
		$rcb = mysqli_fetch_assoc($rc);
	}
	if(isset($_POST['submit']) && isset($_SESSION['user'])){
	q("
	UPDATE `users` SET `bday`  = '".es($_POST['bth'])."',
					  `city`  = '".es($_POST['city'])."',
					  `webs`  = '".es($_POST['web'])."',
					  `skype` = '".es($_POST['skype'])."',
					  `hobby` = '".es($_POST['hobby'])."',
					  `phone` = '".es($_POST['phone'])."',
					  `sex`   = '".es($_POST['sex'])."'
	WHERE `id` = '".$_SESSION['user']['id']."'
	");
	// Если обновляли данные - то обновляем сразу значения, иначе дважды нужно перегружать страницу чтобы
	// обновились даные
	$rc = q("
				SELECT * FROM `users`
				WHERE `id` = '".$_SESSION['user']['id']."'
			  ");
	$rcb = mysqli_fetch_assoc($rc);
        header("Location: /cab/lc");
        exit();
	}

//--------------------------------------------------------------------------------------------------------
// Блок проверок для загрузки изображений
$array = array('image/gif','image/jpeg','image/png'); // Создаем массив с допустимыми типами файлов 
													   // для загрузки
  $array2 = array('jpg','jpeg','gif','png');  // для проверки расширения загружаемых файлов	
	if(isset($_POST['sub'])){
	  if($_FILES['file']['error'] == 0){ 
	    // Если файл загружен и ошибок нет, проверяем размер файла, от 5кб до 50мб
	    if($_FILES['file']['size'] < 5000 || $_FILES['file']['size'] > 50000000){
			 echo 'Размер изображения слишком мал или слишком большой';
		} else{
			// Ищем окончание (расширение) загружаемого файла
			preg_match('#\.([a-z]+)$#iu',$_FILES['file']['name'],$matches);
			// Если нашли, то делаем проверки дальше
			if(isset($matches[1])){
			  // Если у нас расширение с больших букв - уменьшаем их до маленьких
			  $matches[1] = mb_strtolower($matches[1]);
			  // Проверяем загружаемый файл, добавляя инфу о нем в $temp
			  $temp = getimagesize($_FILES['file']['tmp_name']);
			  // Присваеваем имя файлу, используя date и rand чтобы имена были всегда разными
			  // В начале имени указываем путь к папке - куда будет добавляться изображение  
			  $name = '/imgt/users/'.date('dmY-His').'img'.rand(10000,99999).'.'.$matches[1];
			  // Если расширение файла отличное от допустимых, которые есть в массиве2, то выдаем ошибку
			  if(!in_array($matches[1],$array2)){
				$error = 'Неверное расширение файла';
			   // Проверяем, если тип загружаемого файла не содержится в начальном массиве (не картинка),
			  // то выдаем ошибку, а если содержится - загружаем его
			  } elseif(!in_array($temp['mime'],$array)){
                  $error = 'Неверный тип файла, можно загружать только картинки';
			      // Возвращает TRUE - если файл загружен и FALSE - если не загружен
			    }elseif(!move_uploaded_file($_FILES['file']['tmp_name'],'.'.$name)){
                  $error = 'Изображение не загружено!';
			      } else{
					  echo 'Изображение загружено успешно!';
					  // Смотрим его ширину\высоту
					  if($temp[0]>100 || $temp[1]>100){
					  // Проверяем длину\ширину загружаемого изображения, если она больше 100 - то урезаем его
					  // Если ширина больше высоты то новая ширина = 100, а высота равна = высота*100/ширину
					    if($temp[0]>$temp[1]){
							$newH = $temp[1]*100/$temp[0];
							$newW = 100;
							$newH = round($newH);
						} else{  // Иначе если высота больше ширины, то новая высота = 100, а ширина = 	
							//ширина*100/высоту
							$newH = 100;
							$newW = $temp[0]*100/$temp[1];
							$newW = round($newW);
					      }
					      // Создаем новый jpeg с новой шириной\высотой которую вычислили выше
					      $im = imagecreatetruecolor($newW,$newH);
						  // Создаем новый путь с новой картинкой
						  $name2 = '/imgt/users/'.date('dmY-His').'img'.rand(10000,99999).'.'.$matches[1];
						  // Если у нас джипег - то создаем джипег
						  if($matches[1]=='jpg' || $matches[1]=='jpeg'){
							// Копируем в переменную исходное изображение
							$img =imagecreatefromjpeg('.'.$name);
							// создаем масштарибруемый рисунок из исходного по найденым ширине\высоте
							imagecopyresampled($im,$img,0,0,0,0,$newW,$newH,$temp[0],$temp[1]);
							// Сохраняем полученный рисунок в папке uploaded
							imagejpeg($im,'.'.$name2,100);
						  } elseif($matches[1]=='png'){ // Если у нас png - то создаем png 
							  $img =imagecreatefrompng('.'.$name);
							  imagecopyresampled($im,$img,0,0,0,0,$newW,$newH,$temp[0],$temp[1]);
						      imagepng($im,'.'.$name2,0);
						  } else{ // Иначе делаем gif-ку, т.к. других рисунков у нас быть не может
							  $img =imagecreatefromgif('.'.$name);	
							  imagecopyresampled($im,$img,0,0,0,0,$newW,$newH,$temp[0],$temp[1]);
						      imagegif($im,'.'.$name2);
							}
					      // Освобождаем память занятую рисунком
					      imagedestroy($im); 
						  // Если масштабировали рисунок - значит удаляем исходный
						  unlink('.'.$name);
						  // Добавляем рисунок в БД полученный рисунок
						  q("
							UPDATE `users` SET `img` = '".es($name2)."'
							WHERE `id` = '".$_SESSION['user']['id']."'
						  ");
				     } else{
						// Иначе в БД юзеры добавляем 1ый рисунок
						q("
						  UPDATE `users` SET `img` = '".es($name2)."'
						  WHERE `id` = '".$_SESSION['user']['id']."'
					    ");
						$rc = q("
								SELECT * FROM `users`
								WHERE `id` = '".$_SESSION['user']['id']."'
							  ");
						$rcb = mysqli_fetch_assoc($rc);
					  }
					}
		    } else{
			    echo 'Данный файл не содержит расширение';
			  }
		 } 
	  } else{
			echo 'Вы не загрузили файл, или произошла ошибка!';
		}
		if(isset($error)) {
            $_SESSION['er'] = $error;
        }
	header("Location: /cab/lc");
    exit();
    }
?>