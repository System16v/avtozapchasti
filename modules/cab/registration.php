<?php
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/skins/default/css/regf.css">';
Core::$JS[] = '<script type="text/javascript" src="/skins/default/js/jquery-1.4.2.min.js"></script>';
Core::$JS[] = '<script type="text/javascript" src="/skins/default/js/registration.js"></script>';
Core::$META['title'] = 'Регистрация Автозапчасти Новошахтинск';
// Обработка данных, если они существуют
if(!isset($_SESSION['user'])) {
    if (isset($_POST['login'], $_POST['email'], $_POST['password'], $_POST['sendreg'])) {
        $errors = array();
        if (empty($_POST['login'])) {
            $errors['login'] = 'Вы не заполнили логин';
        } elseif (mb_strlen($_POST['login']) < 2) {
            $errors['login'] = 'Логин слишком короткий';
        } elseif (mb_strlen($_POST['login']) > 16) {
            $errors['login'] = 'Логин слишком длинный';
        } elseif (!preg_match('#^[а-яa-z0-9\-_]+$#ui', $_POST['login'])) {
            $errors['login'] = 'Вы ввели не допустимые символы';
        }
        if (mb_strlen($_POST['password']) < 5) {
            $errors['password'] = 'Пароль должен быть длинней 4-х символов';
        }
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Вы не заполнили email';
        }

        // Если ввели верно все данные, проверяем на существование введенного логина в БД
        // Пытаемся выбрать из БД юзеров юзера с айдишником у которого логин точно такой же как ввели при
        // попытке зарегистрироваться.
        if (!count($errors)) {
            $res = q("
				SELECT `id`
				FROM `users`
				WHERE `login` = '" . es($_POST['login']) . "'
				LIMIT 1
		");
            // И если такой юзер был ($res!=0), значит такой логин уже зарегистрован
            if (mysqli_num_rows($res)) {
                $errors['login'] = 'Такой логин уже занят';
            }
        }

        // Точно также проверяем на существование email
        if (!count($errors)) {
            $res = q("
				SELECT `id`
				FROM `users`
				WHERE `email` = '" . es($_POST['email']) . "'
				LIMIT 1
		");
            // И если такой email был ($res=1), значит такой логин уже зарегистрован
            if (mysqli_num_rows($res)) {
                $errors['email'] = 'Данный email уже зарегистрирован';
            }
        }

        if (!count($errors)) {
            if ($_POST['password'] != $_POST['password2'])
                $errors['password2'] = 'Пароли не совпадают!';
        }

        if (!count($errors)) { // считывание количество ключей(данных) в массиве
            // Если массив с ошибками пуст,т.е. нет ошибок, то выполняем далее действия, добавляем юзера в БД
            // При этом хешируем пароль, и добавляем хешированный пароль в БД, и создаем хэщ для активации
            q("
		INSERT INTO `users` SET
		`login`    = '" . es($_POST['login']) . "',
		`password` = '" . myHash($_POST['password']) . "',  
		`email`    = '" . es($_POST['email']) . "',
		`hash`	   = '" . myHash($_POST['login'] . $_POST['email']) . "',
		`data`	   = '" . date('Y-m-d') . "'
	"); // Если в MySQL была ошибка, то выход и выводим на экран ошибку (указана в функции q)

            $id = DB::_()->insert_id; // Функция возвращает последний только что добавленный айдишник

            Meil::$to = $_POST['email']; // если зарегистрировали пользователя, то формируем ему письмо
            // а почту, для подтверждения регистрации
            Meil::$subject = 'Вы зарегистрировались на сайте';
            Meil::$text = 'На Ваш почтовый адрес была произведена регистрация на сайте. Если вы регистрировались, то подтвердите регистрациюю перейдя по сслыке ' . Core::$DOMAIN . '/cab/activate/' . $id . '/' . myHash($_POST['login'] . $_POST['email']) . ' , если вы не регистрировались, то проигнорируйте данное сообщение'; // отправляем на почту текст с ссылкой для
            // подтверждения регистрации
            Meil::send();            // отправляем письмо
            $_SESSION['regok'] = 'OK';  // Для смены формы поле регистрации, и вывода на экран успешного завершения

            header("Location: /");
            exit(); // Для очистки POST данных,если это не делать, то после регистрации нажав F5(обновить
            //  браузер), данные отправятся повторно,и в БД добавится дубликат
        }
    }
}else{
    header("Location: /404");
    exit();
}
?>