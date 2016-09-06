<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 22.08.2016
 * Time: 15:43
 */

$catg = q("
        SELECT *
        FROM `categorii`
");

if(isset($_POST['dob'])){
    q("
    INSERT INTO `tovari`
    SET `name` = '".es($_POST['nz'])."', `model` = '".es($_POST['md'])."', `firm` = '".es($_POST['fr'])."',
        `nalichie` = '".es($_POST['nl'])."', `cena` = '".es($_POST['cen'])."', `cat` = '".es($_POST['ct'])."', `date` = NOW()
    ");
    
	$id = DB::_()->insert_id;
//------------------------------------- ЗАГРУЗКА ФОТО ------------------------------------------------------------------
// Блок проверок для загрузки изображений
$array = array('image/gif','image/jpeg','image/png'); // Создаем массив с допустимыми типами файлов
// для загрузки
$array2 = array('jpg','jpeg','gif','png');  // для проверки расширения загружаемых файлов

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
							WHERE `id` = '".$id."'
						  ");
                        header("Location: /static/main/Каталог товаров");
                        exit();
                    } else{
                        // Иначе в БД просто добавляем 1ый рисунок
                        q("
					      UPDATE `tovari` SET `img` = '".es($name)."'
						  WHERE `id` = '".$id."'
					    ");
                        header("Location: /static/main/Каталог товаров");
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
