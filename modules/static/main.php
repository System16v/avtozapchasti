<?php
Core::$META['title'] = 'Автозапчасти Новошахтинск';
Core::$META['description'] = 'Автозапчасти ВАЗ ОКА и иномарки под заказ, Новошахтинск. У нас Вы можете выбрать, оформить заказ, а так же заказать товар.';
if(isset($_GET['key1'])){
    Core::$META['title'] = 'Каталог товаров Автозапчасти Новошахтинск';
}

// Если нажали добавить товар - открываем страницу с добавлением товара
if(isset($_POST['addt']) && isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){
    header("Location: /static/addt");
    exit();
}

if(isset($_SESSION['user']) && $_SESSION['user']['access']==5){
    Core::$limit = 15;
}
// Если выбрали удалить выбранные товары - то удаляем
if(isset($_POST['delall']) && isset($_SESSION['user']) && $_SESSION['user']['access'] == 5) {
    if (isset($_POST['idst'])) {
        foreach ($_POST['idst'] as $k => $v) {
            $_POST['idst'][$k] = (int)$v;
        }
        $idst = implode(',', $_POST['idst']);  // преобразуем массив в строку через запятую

        // В запросе используем IN, чтобы удалились все записи по существующим айдишникам(выбранным чекбоксам)
        q("				
              DELETE FROM `tovari`			  
	          WHERE `id` IN (" . $idst . ")        
              ");
    }
    header("Location: /static/main/Каталог товаров");
    exit();
}
// проверяем введенную категорию товаров
if(isset($_GET['key1']) && $_GET['key1'] !='Каталог товаров'){
    $c = q("
        SELECT *
        FROM `categorii`
        WHERE `name` = '".es($_GET['key1'])."'
        ");
    if(!$c->num_rows){
        header("Location: /404");
        exit();
    }
}

//----------------------------------------------------------------------------------------------------------------------

// проверяем введенный кей2, если у нас больше 1го символа и это не цифры - то 404
if((isset($_GET['key2']) && mb_strlen($_GET['key2'],'utf-8') != 1) && (int)$_GET['key2']==0){
    header("Location: /404");
    exit();
    // если ввели цифры с буквами - 404
}elseif(isset($_GET['key2']) && preg_match('#^[0-9]+[a-zа-яё\/\-+\,\*\`]+$#ui',$_GET['key2'])) {
    header("Location: /404");
    exit();
}elseif((isset($_GET['key3']) && preg_match('#^[0-9]+[a-zа-яё\/\-+\,\*\`]+$#ui',$_GET['key3'])) || (isset($_GET['key3']) && preg_match('#^[a-zа-яё\/\-+\,\*\`]+[0-9]+$#ui',$_GET['key3'])) || (isset($_GET['key3']) && preg_match('#^[a-zа-яё\/\-+\,\*\`]+$#ui',$_GET['key3']))){
    header("Location: /404");
    exit();
}elseif(isset($_GET['key2']) && preg_match('#^[ыьъйё\/\-+\,\*\`]+$#ui',$_GET['key2'])) {
    header("Location: /404");
    exit();
}

if(isset($_GET['key1'])) {
    // Если поиск - ищем по сессии что в поиске
    if(isset($_GET['search'])){
        $chetchik = q("
              SELECT count(*)
              FROM `tovari`
              WHERE `name` LIKE '" . es($_GET['search']) . "%'      
            ");
        $chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
        $kl = $chet[0]; // вытаскиваем значение записей
        // Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
        $pk = ceil($kl / Core::$limit);
        // если каталог общий и существует буква
    }elseif ($_GET['key1'] == 'Каталог товаров' && isset($_GET['key2']) && (int)$_GET['key2'] == 0) {
        // В противном случае у нас и буква
        $chetchik = q("
              SELECT count(*)
              FROM `tovari`
              WHERE `name` LIKE '" . es($_GET['key2']) . "%'      
            ");
        $chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
        $kl = $chet[0]; // вытаскиваем значение записей
        // Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
        $pk = ceil($kl / Core::$limit);
        // если каталог не общий и нет буквы, или мы в каталоге и есть страница
    }elseif (($_GET['key1'] != 'Каталог товаров' && !isset($_GET['key2'])) || ($_GET['key1'] != 'Каталог товаров' && isset($_GET['key2']) && (int)$_GET['key2']!=0)){
// В противном случае если у нас есть категория - считаем кол-во новостей выбранной категории
        $chetchik = q("
              SELECT count(*)
              FROM `tovari`
              WHERE `cat` = '" . $_GET['key1'] . "'
            ");
        $chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
        $kl = $chet[0]; // вытаскиваем значение записей
        // Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
        $pk = ceil($kl / Core::$limit);
        // если мы в категории и есть буква
    }elseif($_GET['key1'] != 'Каталог товаров' && isset($_GET['key2']) && (int)$_GET['key2'] == 0){
        $chetchik = q("
              SELECT count(*)
              FROM `tovari`
              WHERE `cat` = '" . $_GET['key1'] . "' AND `name` LIKE '" . es($_GET['key2']) . "%'
            ");
        $chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
        $kl = $chet[0]; // вытаскиваем значение записей
        // Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя
        $pk = ceil($kl / Core::$limit);
        // в противном случае мы в общем каталоге и нет буквы
    }else {
        $chetchik = q("
              SELECT count(*)
              FROM `tovari`
            ");
        $chet = $chetchik->fetch_row(); // Будет содержать массив с кол-вом записей
        $kl = $chet[0]; // вытаскиваем значение записей
        // Делим значение записей на лимит 1 страницы, для получения кол-ва страниц, округляя в большую сторону
        $pk = ceil($kl / Core::$limit);
    }
    if(isset($_GET['key2']) && (int)$_GET['key2']!=0 && $_GET['key2'] > $pk && !isset($_GET['search'])){
        header("Location: /404");
        exit();
    }elseif(isset($_GET['key3']) && (int)$_GET['key3']!=0 && $_GET['key3'] > $pk){
        header("Location: /404");
        exit();
    }
// Если не существует страницы и категории значит мы на 1й странице и если записей больше 5ти(больше 1 стр)
// выводим первые от 0 до 5 записей
     if(isset($_GET['search']) && !isset($_GET['key2'])){ // если существует сессия (т.е. был поиск после редиректа)
        $tovari = q("
                SELECT *
                FROM `tovari`
                WHERE `name` LIKE '%" . es($_GET['search']) . "%'
                ORDER BY `name` ASC
                LIMIT 0," . Core::$limit . "
        ");
         if($_GET['key1']!='Каталог товаров'){ // если искали в другом каталоге - редиректим в общий каталог
             header("Location: /static/main/Каталог товаров?search=".$_GET['search']."");
             exit();
         }
        // если существует поиск и страница то выбираем по лимиту
    }elseif(isset($_GET['search']) && isset($_GET['key2'])){
        if((int)$_GET['key2']!=0) { // проверяем - если у нас поиск был на какой-то странице - то делаем запрос
            $tovari = q("
                SELECT *
                FROM `tovari`
                WHERE `name` LIKE '%" . es($_GET['search']) . "%'
                ORDER BY `name` ASC
                LIMIT " . ($_GET['key2'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
        ");
        }else{ // иначе значит нажали поиск находясь в каталоге с буквой, редиректим обратно на каталог
            header("Location: /static/main/Каталог товаров?search=".$_GET['search']."");
            exit();
        }
         // если товаров нет значит нажали поиск на какой-то странице - редиректим назад в поиск но без страницы
         if(!$tovari->num_rows){
             header("Location: /static/main/Каталог товаров?search=".$_GET['search']."");
             exit();
         }
        // Если у нас категория общая,а страницы нет и не буква и не поиск - выбираем первые 10 товаров
    } elseif (isset($_GET['key1']) && $_GET['key1'] == 'Каталог товаров' && !isset($_GET['key3']) && !isset($_GET['key2']) && !isset($_POST['search'])) {
        $tovari = q("
                SELECT *
                FROM `tovari`
                ORDER BY `name` ASC
                LIMIT 0," . Core::$limit . "
        ");
        // Если у нас существует номер страницы без категории и буквы,
    } elseif (isset($_GET['key2']) && (int)$_GET['key2'] != 0 && isset($_GET['key1']) && $_GET['key1'] == 'Каталог товаров' && !isset($_GET['key3'])) {
// то выводим по лимиту
        $tovari = q("
                SELECT *
                FROM `tovari`
                ORDER BY `name` ASC
                LIMIT " . ($_GET['key2'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
        ");
        // Если мы в категории а номера страницы нет и нет буквы - выбираем по лимит записи по данной категории
    } elseif (!isset($_GET['key2']) && isset($_GET['key1']) && $_GET['key1'] != 'Каталог товаров' && !isset($_GET['key3'])) {
        $tovari = q("
                SELECT *
                FROM `tovari`
                WHERE `cat` = '" . $_GET['key1'] . "'
                ORDER BY `name` ASC
                LIMIT 0," . Core::$limit . "
        ");
        // если мы в категории и существует номер страницы без буквы - выбираем по лимиту
    } elseif (isset($_GET['key2']) && (int)$_GET['key2'] != 0 && isset($_GET['key1']) && $_GET['key1'] != 'Каталог товаров' && !isset($_GET['key3'])) {
        $tovari = q("
                SELECT *
                FROM `tovari`
                WHERE `cat` = '" . $_GET['key1'] . "'
                ORDER BY `name` ASC
                LIMIT " . ($_GET['key2'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
        ");
        // если у нас нет категории но есть страница и буква - выбираем по лимиту
    } elseif (isset($_GET['key2']) && isset($_GET['key1']) && $_GET['key1'] == 'Каталог товаров' && isset($_GET['key3'])) {
        $tovari = q("
                SELECT *
                FROM `tovari`
                WHERE `name` LIKE '" . es($_GET['key2']) . "%'
                ORDER BY `name` ASC
                LIMIT " . ($_GET['key3'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
        ");
        // если у нас есть категория есть страница и есть буква - выбираем по лимиту
    } elseif (isset($_GET['key2']) && isset($_GET['key1']) && $_GET['key1'] != 'Каталог товаров' && isset($_GET['key3'])) {
        $tovari = q("
                SELECT *
                FROM `tovari`
                WHERE `cat` = '" . $_GET['key1'] . "' AND `name` LIKE '" . es($_GET['key2']) . "%'
                ORDER BY `name` ASC
                LIMIT " . ($_GET['key3'] * Core::$limit - Core::$limit) . "," . Core::$limit . "
        ");
        // категория общая - буква есть - страницы нет
    } elseif (isset($_GET['key2']) && (int)$_GET['key2'] == 0 && isset($_GET['key1']) && $_GET['key1'] == 'Каталог товаров' && !isset($_GET['key3'])) {
        $tovari = q("
                SELECT *
                FROM `tovari`
                WHERE `name` LIKE '" . es($_GET['key2']) . "%'
                ORDER BY `name` ASC
                LIMIT 0," . Core::$limit . "
        ");
        // если у нас есть категория и есть буква без страницы
    } elseif (isset($_GET['key2']) && (int)$_GET['key2'] == 0 && isset($_GET['key1']) && $_GET['key1'] != 'Каталог товаров' && !isset($_GET['key3'])) {
        $tovari = q("
                SELECT *
                FROM `tovari`
                WHERE `cat` = '".$_GET['key1']."' AND `name` LIKE '" . es($_GET['key2']) . "%'
                ORDER BY `name` ASC
                LIMIT 0," . Core::$limit . "
        ");
    }
}
//----------------------------------------------------------------------------------------------------------------------

//------------------------------------- ЗАГРУЗКА ФОТО ------------------------------------------------------------------
// Блок проверок для загрузки изображений
$array = array('image/gif','image/jpeg','image/png'); // Создаем массив с допустимыми типами файлов
// для загрузки
$array2 = array('jpg','jpeg','gif','png');  // для проверки расширения загружаемых файлов
if(isset($_POST['df'])){
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
                $name = '/imgt/'.date('dmY-His').'img'.rand(10000,99999).'.'.$matches[1];
                // Если расширение файла отличное от допустимых, которые есть в массиве2, то выдаем ошибку
                if(!in_array($matches[1],$array2)){
                    echo 'Неверное расширение файла';
                    // Проверяем, если тип загружаемого файла не содержится в начальном массиве (не картинка),
                    // то выдаем ошибку, а если содержится - загружаем его
                } elseif(!in_array($temp['mime'],$array)){
                    echo 'Неверный тип файла, можно загружать только картинки';
                    // Возвращает TRUE - если файл загружен и FALSE - если не загружен
                }elseif(!move_uploaded_file($_FILES['file']['tmp_name'],'.'.$name)){
                    echo 'Изображение не загружено!';
                } else{
                    echo 'Изображение загружено успешно!';
                    // Смотрим его ширину\высоту
                    if($temp[0]>800 || $temp[1]>800){
                        // Проверяем длину\ширину загружаемого изображения, если она больше 200 - то урезаем его
                        // Если ширина больше высоты то новая ширина = 200, а высота равна = высота*200/ширину
                        if($temp[0]>$temp[1]){
                            $newH = $temp[1]*800/$temp[0];
                            $newW = 800;
                            $newH = round($newH);
                        } else{  // Иначе если высота больше ширины, то новая высота = 200, а ширина =
                            //ширина*200/высоту
                            $newH = 800;
                            $newW = $temp[0]*800/$temp[1];
                            $newW = round($newW);
                        }
                        // Создаем новый jpeg с новой шириной\высотой которую вычислили выше
                        $im = imagecreatetruecolor($newW,$newH);
                        // Создаем новый путь с новой картинкой
                        $name2 = '/imgt/'.date('dmY-His').'img'.rand(10000,99999).'.'.$matches[1];
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
							UPDATE `tovari` SET `img` = '".es($name2)."'
							WHERE `id` = '".$_POST['ids']."'
						  ");
                        header("Location: /static/main/".$_GET['key1']."");
                        exit();
                    } else{
                        // Иначе в БД просто добавляем 1ый рисунок
                        q("
					      UPDATE `tovari` SET `img` = '".es($name)."'
						  WHERE `id` = '".$_POST['ids']."'
					    ");
                        header("Location: /static/main/".$_GET['key1']."");
                        exit();
                    }
                }
            } else{
                echo 'Данный файл не содержит расширение';
            }
        }
    } else{
        echo 'Вы не загрузили файл, или произошла ошибка!';
    }
}
?>