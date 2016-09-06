<?php
/*
q(); Запрос
es(); mysqli_real_escape_string

$res = q(); Запрос с возвратом результата (здесь же делается соединение к БД, вызывает функция q() )
|||||||||||||||||||||||||||||||||||||||||
$res->num_rows; Запрос mysqli_num_rows()
$res->fetch_assoc(); Запрос mysqli_fetch_assoc()
$res->close(); Очищаем результат выборки с БД

РАБОТА С ПОДКЛЮЧЕННОЙ БД:
DB::_()->affected_rows; Кол-во измененных записей
DB::_()->insert_id(); Последний ид вставки
DB::_()->query(); аналог q()
DB::_()->multi_query(); Множественные запросы
DB::_()->real_escape_string(); аналог es();
DB::close(); Закрыть соединение с БД
*/

class DB{
	static public $mysqli = array();
	static public $connect = array();  // будет содержать параметры для соединения с БД
	// По умолчанию ключ равен 0, если будем указывать ключ - то будет подключаться к другой БД
	static public function _($key = 0){
		// Если не существует подключения к БД - то создаем его
		if(!isset(self::$mysqli[$key])){
		  // Если при вызове мы не создавали к какой БД коннектимся - то присваиваем значения по умолчанию
		  if(!isset(self::$connect['server']))
			  self::$connect['server'] = Core::$DB_LOCAL;
		  if(!isset(self::$connect['user']))
		  	  self::$connect['user'] = Core::$DB_LOGIN;
		  if(!isset(self::$connect['pass']))
			  self::$connect['pass'] = Core::$DB_PASS;
		  if(!isset(self::$connect['db']))
			  self::$connect['db'] = Core::$DB_NAME;
		  // ООП Стиль подключения к БД, @ - используется чтобы при ошибке подключения, данная ошибка не
		  // показывалась юзерам
		  self::$mysqli[$key] = @new mysqli(self::$connect['server'],self::$connect['user'],self::$connect['pass'],self::$connect['db']);
		// Если существуют ошибки при подключении к БД - то пишем ошибка
		if(mysqli_connect_errno()){
			echo 'Не удалось подключиться к БД';
		}
		// -> Стиль ООП, ссылание на объект
		if(!self::$mysqli[$key]->set_charset("utf8")){
			echo 'Ошибка при загрузке набора символов utf-8:'.self::$mysqli[$key]->error;
		}
	  }
	  return self::$mysqli[$key];
	}
	//
	static public function close($key = 0){
		self::$mysqli[$key]->close();
		unset(self::$mysqli[$key]);
	}
}
?>