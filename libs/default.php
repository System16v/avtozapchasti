<?php
// Для подгрузки классов, чтобы не писать постоянно инклуды, если в коде встречается неизвестная 
// переменая, она сразу же передается в функцию автолоад, где $class = имя класса 
function __autoload($class){
	include './libs/class_'.$class.'.php';
}

function wtf($array,$stop = false){
  echo '<pre>'.print_r($array,1).'</pre>';
  if(!$stop){
	  exit();
  }
}

// Переделанная функция под класс
function q($query,$key = 0){
	$res = DB::_($key)->query($query); 
	if($res === false){
		$info = debug_backtrace();   // Функция возвращает всю инфу об данной функции
		$error = "Адрес файла: ".$info[0]['file']."\r\n<br>Строка в файле №: ".$info[0]['line']."\r\n<br>Запрос: ".$query."<br>\r\n".DB::_($key)->error."\r\n<br>Время ошибки: ".date("d-m-Y H:i:s");
		// Для добавления ошибки в логи
		file_put_contents('./logs/mysql.log',strip_tags($error)."\r\n\r\n",FILE_APPEND);
		echo $error;
		exit();
	} else
		return $res;
}

function printR($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

// Рекурсивные функции для обработки данных массива, в котором могут содержаться в глубине другие массивы
function trimAll($v){
	if(!is_array($v)){  // Если переданная переменная не массив, то просто обрабатываем ее
		$v = trim($v);  // убирая лишние пробелы
	} else{
		$v = array_map('trimAll',$v);  // Если переменная массив, то рекурсией заново вызываем функцию
	}
	return $v;
}

function intAll($v){
	if(!is_array($v)){
		$v = (int)$v;    // преобразуем данные в int
	} else{
		$v = array_map('intAll',$v);
	}
	return $v;
}

function floatAll($v){
	if(!is_array($v)){
		$v = (float)$v;  // преобразуем данные в float
	} else{
		$v = array_map('floatAll',$v);
	}
	return $v;
}
// htmlspecialchars
function hc($v){
	if(!is_array($v)){
		$v = htmlspecialchars($v); // преобразуем специальные символы (типа тегов) в обычные символы
	} else{						  // для вывода на экран
		$v = array_map('hc',$v);
	}
	return $v;
}

// Аналог под класс 
function es($v,$key = 0){
	if(!is_array($v)){
		$v = DB::_($key)->real_escape_string($v);   // преобразуем спец. символы в обычные символы
	} else{										   // для добавления в БД
		$v = array_map('es',$v);
	}
	return $v;
}

// Функция для хешерирования пароля
function myHash($var){
	$salt = 'ABC'; // соль(мусор) для усложнения расхеширования
	$salt2= 'CBA'; // соль2(мусор) для усложнения расхеширования
	$var = crypt(md5($var.$salt),$salt2); // в начале хешируем пароль в md5, а потом в crypt
	return $var;
}
?>