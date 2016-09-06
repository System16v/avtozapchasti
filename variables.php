<?php 
//--------------------------------------------------------------------------------------------------------
/*
  http://lesson26.ru/index.php?module=cab&page=registration.php - старое название
  http://lesson26.ru/cab/registration  - новое название, в $_GET[route] = cab/registration
  Необходимо теперь просто разложить новое название на части 
  
  lesson26.ru/cab/registration/b/c
  $_GET['module'] = cab
  $_GET['page'] = registration
  $_GET['key1'] = b
  $_GET['key2'] = c
  
*/
//wtf($_GET);
// Если существует переменная роут, значит надо ее разложить на части (из строки сделать массив)
if(isset($_GET['route'])){
	$temp = explode('/',$_GET['route']);
	
	// Если в дальнейшем кроме админа будут еще отдельные модули, то передавать данные можно через
	// массив in_array($temp[0],array('admin','partners','adversment'))
	
	if($temp[0] == 'admin'){			   // Если захотели ввойти под админом, то меняем скин default
	  
	  Core::$CONT = Core::$CONT.'/admin'; // на admin, модули на модуль/админ и удаляем ее из temp[0], 
	  Core::$SKIN = 'admin';			 // чтобы не мешалась 
	  unset($temp[0]);				    // для этого ставим ниже счетчик,чтобы в модуль прописалось
	}								   // 2ое значение с массива, иначе бы в модуль попал бы 'admin'
	
	$i = 0;
	foreach($temp as $k=>$v){ 			 // перебираем получившийся массив
		if($i == 0){					// если встретили 1 значение, то это название модуля
			if(!empty($v)){			   // проверка на случай случайно поставленного слэша
				$_GET['module'] = $v;
			}
		} elseif($i == 1){			  // если встретили 2 значение, то это название page
			if(!empty($v)){			 // чтобы исключить при вводе адресной строки пустого '/'
			  $_GET['page'] = $v;	// Если ввести test.ru/cab/ - и не указать проверки, будет ошибка
			}					   // и создатся пустая переменная page в массиве GET
		} else{					  // если встретили другие значения, то просто записываем их как ключи
			if(!empty($v))
			  $_GET['key'.($k-1)] = $v;  // $k-1 - чтобы ключи начинались с 1 а не с 2
		}
	++$i;
	}
	unset($_GET['route']); // Удаляем роут чтобы не мешался, можно и не удалять
// wtf($_GET,1);
//	wtf($_POST);
}
//--------------------------------------------------------------------------------------------------------

/* 
$allowed = array('static','contacts','aboutus','game','program','file','errors','cab','comments','news','goods');
if(!isset($_GET['module'])){
	$_GET['module'] = 'static';
}elseif(!in_array($_GET['module'],$allowed) && Core::$SKIN != 'admin'){
	// Т.к. у нас есть роутер, то теперь не обязательно указывать полный путь, хватит просто указать
	// названия ключей
	// header("Location: /index.php?module=errors&page=404"); 
	header("Location: /errors/404");
	exit();
}
*/

// Аналог кода который приведен выше, но с использованием БД, чтобы лишний раз не создавался массив 
// 1 - статичная страница 0 - не статичная страница
if(!isset($_GET['module'])){
	$_GET['module'] = 'static';
}else{
	$result = q("
				SELECT *
				FROM `pages`
				WHERE `module` = '".es($_GET['module'])."'
				LIMIT 1
	          ");
	// Если данных нет, т.е. название модуля не существует - редиректим на 404 страницу, если есть 
	// и страница статичная - то выводим ее на экран, в противном случае если модуль не статичный,
	// то просто ничего не делаем, т.е. просто загрузится данный модуль
	if(!$result->num_rows){
		header("Location: /404");
		exit();
	} else{
		$staticpage = $result->fetch_assoc();
		$result->close(); // Очищаем память т.к. она нам уже не нужна
		if($staticpage['static'] == 1){
			$_GET['module'] = 'staticpage';
			$_GET['page'] = 'main';
		} 
	  }
}
// Проверяем название page
if(!isset($_GET['page'])){
	$_GET['page'] = 'main';
}elseif(!preg_match('#^[a-z\-_]*$#ui',$_GET['page'])){
	header("Location: /404");
	exit();
}

// Если существует переменная POST, то обработаем ее и удалим все лишние пробелы
if(isset($_POST)){
	$_POST = trimAll($_POST);
}
?>