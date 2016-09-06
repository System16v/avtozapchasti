<?php  include './modules/cab/auth.php';
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/skins/default/css/users.css">';
Core::$limit = 25;
if(isset($_SESSION['s']) && !isset($_GET['key1'])){
    $_SESSION['s'] +=1;
}

if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && !isset($_POST['srch'])&& !isset($_SESSION['srch'])) {
    $chetchik = q("
              SELECT count(*)
              FROM `users`
            ");
    $chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
    $kl = $chet[0]; // вытаскиваем значение записей
// Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
    $pk = ceil($kl / Core::$limit);
}elseif(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && isset($_SESSION['srch'])){
    $chetchik = q("
              SELECT count(*)
              FROM `users`
              WHERE `login` LIKE '%".$_SESSION['srch']."%'
            ");
    $chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
    $kl = $chet[0]; // вытаскиваем значение записей
// Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
    $pk = ceil($kl / Core::$limit);
}

if(isset($_GET['key1']) && (int)$_GET['key1'] == 0){
    unset($_SESSION['srch']);
    unset($_SESSION['s']);
}
// Если у нас существует сессия счетчика и она не равна 2ум, значит нажимали на каталог, т.е. поиск не нужен, удаляем сессию
// не равен 2, потому что при поиске у нас редирект, и при редиректе прибавится +1 к счетчику
if(isset($_SESSION['s']) && $_SESSION['s']!=2){
    unset($_SESSION['srch']);
    unset($_SESSION['s']);
}

if((isset($_GET['key1']) && $_GET['key1'] > $pk) || (isset($_GET['key1']) && (int)$_GET['key1'] == 0) || (isset($_GET['key1']) && preg_match('#^[0-9]+[a-zа-яё\/\-+\,\*\`]+$#ui',$_GET['key1']))){
    header("Location: /404");
    exit();
}
	// Если мы авторизованы как админ и есть права, то делаем запросы в БД
	if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && !isset($_GET['key1']) && !isset($_POST['srch']) && !isset($_SESSION['srch'])) {
        $res = q("
			SELECT *
			FROM `users`
			ORDER BY `login` ASC
			LIMIT 0," . Core::$limit . "
		 ");
    }elseif(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && isset($_GET['key1']) && !isset($_POST['srch']) && !isset($_SESSION['srch'])) {
        $res = q("
			SELECT *
			FROM `users`
			ORDER BY `login` ASC
			LIMIT " . ($_GET['key1'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
		 ");
    }elseif(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && !isset($_GET['key1']) && isset($_POST['srch'])){
        $_SESSION['srch'] = $_POST['srch'];
        $_SESSION['s'] = 1;
        header("Location: /cab/user");
        exit();
    }elseif(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && !isset($_GET['key1']) && isset($_SESSION['srch'])){
        $res = q("
			SELECT *
			FROM `users`
			WHERE `login` LIKE '%".es($_SESSION['srch'])."%'
			ORDER BY `login` ASC
			LIMIT 0," . Core::$limit . "
		 ");
    }elseif(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && isset($_GET['key1']) && isset($_SESSION['srch'])){
        $res = q("
			SELECT *
			FROM `users`
			WHERE `login` LIKE '%".es($_SESSION['srch'])."%'
			ORDER BY `login` ASC
			LIMIT " . ($_GET['key1'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
		 ");
    }else{
		header("Location: /404");
		exit();
	}
?>
