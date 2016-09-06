<?php
        if(isset($_GET['key1'])){ ?>
<div id="shapka">
    <table>
        <tr>
            <td class="tov">
                <div class="tb">Товары на букву :
                <?php
                    $abc = array();
                foreach (range(chr(0xC0), chr(0xDF)) as $b) // создаем диапазон букв русских
                    $abc[] = iconv('CP1251', 'UTF-8', $b); // записываем символ в массив с кодировкой
                foreach ($abc as $k=>$v) {
                    if($k != 9 && $k != 26 && $k != 27 && $k != 28)
                        if(!isset($_GET['key1'])){ // если не существует выбраная категория
                            if(isset($_GET['key2']) && $_GET['key2'] == $v) { // но существует выбранная буква, то ищем в общем каталоге
                                // и рисуем выделенной
                                echo '<a class="vd" href="/static/main/Каталог товаров/' . $v . '">' . mb_strtolower($v, 'utf8') . '</a> ';
                            } else{ // иначе выводим не выделенной
                                echo '<a href="/static/main/Каталог товаров/' . $v . '">' . mb_strtolower($v, 'utf8') . '</a> ';
                            }
                        } else{ // иначе у нас существует категория.Если выбрали букву - рисуем ее выделенной
                            if(isset($_GET['key2']) && $_GET['key2'] == $v) {
                                echo '<a class="vd" href="/static/main/' . $_GET['key1'] . '/' . $v . '">' . mb_strtolower($v, 'utf8') . '</a> ';
                            }else{ // иначе просто рисуем буквы
                                echo '<a href="/static/main/'. $_GET['key1'] .'/' . $v . '">' . mb_strtolower($v, 'utf8') . '</a> ';
                            }
                        }
                }
                ?></div>
            </td>
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['access']==5){ ?>
            <td class="addt">
                <div class="addt">
                    <form action="" method="post">
                        <input class="delal" type="submit" name="addt" value="Добавить товар">
                    </form>
                </div>
            </td> <?php }else{ ?>
                <td class="addtn"> </td><?php } ?>
            <td class="poisk">
                <form action="" method="get" >
                    <input type="text" id="search" name="search" value="Поиск..." onfocus="och()" onblur="och2()">
                </form>
            </td>
        </tr>
    </table>
</div>
<div class="goods"><form action="" method="post" enctype="multipart/form-data">
<!-- Выводим сами товары -->
    <table class="table_blur">
    <tr>
        <th class="f">Фото</th>
        <th class="b">Бренд</th>
        <th class="opis">Описание</th>
        <th class="m">Модель</th>
        <th class="c">Цена</th>
        <th class="nal">Наличие</th>
        <th class="cat">В корзину</th>
    </tr>
<?php
    // Выводим товары, кол-во товаров завист от запроса в main.php
        $x = 1;
        while ($t= $tovari->fetch_assoc()) { ?>
            <tr>
                <?php ++$x; ?>
                <td class="f"><?php
                    if (!empty($t['img'])) { ?>
                        <div id="foto"><a class="gallery" href="<?php echo $t['img']; ?>"><img src="/img/fotom.png" alt="photo"></a></div>
<?php                 if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){ ?>
                            <a href="/static/t/img/<?php echo $t['id']; ?>" onclick="if(!confirm('Вы действительно хотите удалить фото?'))return false;"><img src="/img/cross.png" alt="delete" title="Удалить фото"></a>
<?php                 }
                    } else {
                        echo '-';
                    } ?>
                </td>
                <td class="b"><?php echo $t['firm']; ?></td>
                <td class="opis"><?php
                    if (isset($_SESSION['user']) && $_SESSION['user']['access'] == 5) { ?>
                        <input type="checkbox" name="idst[]" value="<?php echo $t['id']; ?>">
                        <a href="/static/t/<?php echo $t['id']; ?>"><?php echo $t['name']; ?></a>
                        <a href="/static/t/del/<?php echo $t['id']; ?>">Удалить</a>
<?php               }else {
                        echo $t['name'];
                    } ?>
                    <?php
                    if (isset($_SESSION['user']) && $_SESSION['user']['access'] == 5) { ?>
                        <div>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="file" id="file" name="file">
                                <input type="submit" name="df" value="ок">
                                <input type="hidden" name="ids" value="<?php echo $t['id']; ?>">
                            </form>
                        </div>
                        <?php
                    } ?>
                </td>
                <td class="m"><?php echo $t['model']; ?></td>
                <td class="c"><?php echo $t['cena'] . ',00р.'; ?></td>
                <td class="nal"><?php if ($t['nalichie'] == 'да') {
                    if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){ ?>
                        <form action="" method="post">
                            д<input type="radio" id="nl" name="nal" class="nlc" value="В наличии" checked="checked">
                            н<input type="radio" id="n" name="nal" class="nlc" value="Под заказ">
                             <input type="hidden" class="id" name="id" value="<?php echo $t['id'];?>">
<?php                    } ?>
                        <div class="status"><img src="/img/nlm.png" alt="В наличии" title="В наличии"></div></form>
  <?php             } else {
                        if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){ ?>
                            <form action="" method="post">
                            д<input type="radio" id="nl" name="nal" class="nlc" value="В наличии">
                            н<input type="radio" id="n" name="nal" class="nlc" value="Под заказ" checked="checked">
                             <input type="hidden" class="id" name="id" value="<?php echo $t['id']; ?>">
<?php                    } ?>
                            <div class="status"><img src="/img/zkm.png" alt="Под заказ" title="Под заказ"></div></form>
<?php                    } ?></td>
                <td class="cat">
                    <?php // Выводим корзину на экран присваиваем айдишник товара атрибуту data-title, чтобы при
                         // добавлении товара по клику, вытащить айдишник добавляемого товара
                        if($t['nalichie'] == 'да') { ?>
                            <div class="korzina"><a href="/" onclick="return false;"><img src="/img/catpm.png" alt="корзина" data-title="<?php echo $t['id']; ?>" title="Добавить в корзину" onclick="addc(this)"></a></div>
<?php
                        }else{ ?>
                            <div class="korzina"><a href="/cat/zakaz/<?php echo $t['id']; ?>"><img src="/img/cat.png" alt="корзина" data-title="<?php echo $t['id'];?>" title="Заказать"></a></div>
<?php                        } ?>
                </td>
            </tr>
            <?php
        }
        if($x == 1){ ?>
            <td class="sp"></td><td class="f"></td><td class="b"><b>Товаров не найдено</b></td><td class="no"></td><td class="m"></td><td class="c"></td><td class="nal"></td>
<?php   }
?>
</table>
<?php
        if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && $x!=1){ ?>
            <div class="dl"><input type="submit" name="delall" value="Удалить выделенные" class="delal"></form></div>
            <div class="clear"></div>
<?php    }
?>    </div>
            <!-- Выводим пагинатор, поиск и переход на товары по букве -->
            <?php if(isset($_GET['key1']) && $x!=1){ ?>
                <div class="paginator_n">
                    <div class="pagination">
                        <ul>
                            <?php
                            if(!isset($_GET['key2'])){ // если у нас нет страницы то создаем ее
                                $_GET['key2'] = 1;
                            }
                            if(isset($pk)&&$pk!=0) {
                                // Если у нас key2 - цифра, значит мы в категории или каталоге без буквы
                                if (is_numeric($_GET['key2']) && !isset($_GET['search'])) {
                                    if ($_GET['key2'] != 1) {
                                        echo '<li><a href="/static/main/' . $_GET['key1'] . '/1" title="В начало" class="greencurve"> << </a></li>';
                                        if($_GET['key2']-1 != 0) {
                                            echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']-1).'" title="На 1 страницу назад" class="greencurve"> <</a></li>';
                                        }
                                    } else {
                                        echo '<li><a href="" class="greencurve" onclick="return false"><< </a></li>';
                                        echo '<li><a href="" class="greencurve" onclick="return false"><</a></li>';
                                    }

                                    if($_GET['key2']-5 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']-5).'" class="greencurve">'.($_GET['key2']-5).'</a></li>';
                                    }
                                    if($_GET['key2']-4 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']-4).'" class="greencurve">'.($_GET['key2']-4).'</a></li>';
                                    }
                                    if($_GET['key2']-3 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']-3).'" class="greencurve">'.($_GET['key2']-3).'</a></li>';
                                    }
                                    if($_GET['key2']-2 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']-2).'" class="greencurve">'.($_GET['key2']-2).'</a></li>';
                                    }
                                    if($_GET['key2']-1 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']-1).'" class="greencurve">'.($_GET['key2']-1).'</a></li>';
                                    }
                                    echo '<li><a href="" class="greencurveactive" onclick="return false">'.$_GET['key2'].'</a></li>'; // вывод текущей страницы
                                    // если у нас не последняя страница - выводим ссылку
                                    if ($_GET['key2'] != $pk) {
                                        if($_GET['key2']+1 <= $pk) {
                                            if($_GET['key2']+1 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']+1).'" class="greencurve">'.($_GET['key2']+1).'</a></li>';
                                            }
                                            if($_GET['key2']+2 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']+2).'" class="greencurve">'.($_GET['key2']+2).'</a></li>';
                                            }
                                            if($_GET['key2']+3 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']+3).'" class="greencurve">'.($_GET['key2']+3).'</a></li>';
                                            }
                                            if($_GET['key2']+4 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']+4).'" class="greencurve">'.($_GET['key2']+4).'</a></li>';
                                            }
                                            if($_GET['key2']+5 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']+5).'" class="greencurve">'.($_GET['key2']+5).'</a></li>';
                                            }
                                            echo '<li><a href="/static/main/'.$_GET['key1'].'/'.($_GET['key2']+1).'" title="На 1 страницу вперед" class="greencurve">> </a></li>';
                                        }
                                        echo '<li><a href="/static/main/' . $_GET['key1'] . '/' . $pk . '" title="В конец" class="greencurve">>></a></li>';
                                    } else {
                                        echo '<li><a href="" class="greencurve" onclick="return false;"> ></a></li>';
                                        echo '<li><a href="" class="greencurve" onclick="return false;"> >></a></li>';
                                    }
                                } elseif(!isset($_GET['search'])) { // иначе мы в каталоге с буквой и № старницы теперь key3
                                    if (!isset($_GET['key3'])) { // если у нас кей2 стал буквой, значит № страницы стал кей3
                                        $_GET['key3'] = 1;
                                    }
                                    if ($_GET['key3'] != 1) {
                                        echo '<li><a href="/static/main/' . $_GET['key1'] . '/' . $_GET['key2'] . '/1" title="В начало" class="greencurve"><<</a></li>';
                                        if($_GET['key3']-1 != 0) {
                                            echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']-1).'" title="На 1 страницу назад" class="greencurve"> <</a></li>';
                                        }
                                    } else {
                                        echo '<li><a href="" class="greencurve" onclick="return false;"><< </a></li>';
                                        echo '<li><a href="" class="greencurve" onclick="return false;">< </a></li>';
                                    }
                                    if($_GET['key3']-5 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']-5).'" class="greencurve">'.($_GET['key3']-5).'</a></li>';
                                    }
                                    if($_GET['key3']-4 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']-4).'" class="greencurve">'.($_GET['key3']-4).'</a></li>';
                                    }
                                    if($_GET['key3']-3 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']-3).'" class="greencurve">'.($_GET['key3']-3).'</a></li>';
                                    }
                                    if($_GET['key3']-2 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']-2).'" class="greencurve">'.($_GET['key3']-2).'</a></li>';
                                    }
                                    if($_GET['key3']-1 > 0){
                                        echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']-1).'" class="greencurve">'.($_GET['key3']-1).'</a></li>';
                                    }
                                    echo '<li><a href="" class="greencurveactive" onclick="return false;">'.$_GET['key3'].'</a></li>';

                                    // если у нас не последняя страница - выводим ссылку
                                    if ($_GET['key3'] != $pk) {
                                        if($_GET['key3']+1 <= $pk) {
                                            if($_GET['key3']+1 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']+1).'" class="greencurve">'.($_GET['key3']+1).'</a></li>';
                                            }
                                            if($_GET['key3']+2 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']+2).'" class="greencurve">'.($_GET['key3']+2).'</a></li>';
                                            }
                                            if($_GET['key3']+3 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']+3).'" class="greencurve">'.($_GET['key3']+3).'</a></li>';
                                            }
                                            if($_GET['key3']+4 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']+4).'" class="greencurve">'.($_GET['key3']+4).'</a></li>';
                                            }
                                            if($_GET['key3']+5 <= $pk){
                                                echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']+5).'" class="greencurve">'.($_GET['key3']+5).'</a></li>';
                                            }
                                            echo '<li><a href="/static/main/'.$_GET['key1'].'/'.$_GET['key2'].'/'.($_GET['key3']+1).'" title="На 1 страницу вперед" class="greencurve">> </a></li>';
                                        }
                                        echo '<li><a href="/static/main/' . $_GET['key1'] . '/' . $_GET['key2'] . '/' . $pk . '" title="В конец" class="greencurve">>></a></li>';
                                    } else {
                                        echo '<li><a href="" class="greencurve" onclick="return false;">> </a></li>';
                                        echo '<li><a href="" class="greencurve" onclick="return false;">>> </a></li>';
                                    }
                                }else{ // иначе мы в поиске
                                    if ($_GET['key2'] != 1) {
                                        echo '<li><a href="/static/main/Каталог товаров/1?search='.$_GET['search'].'" title="В начало" class="greencurve"> << </a></li>';
                                        echo '<li><a href="/static/main/Каталог товаров/1?search='.$_GET['search'].'" title="В начало" class="greencurve"> << </a></li>';
                                        if($_GET['key2']-1 != 0) {
                                            echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']-1).'?search='.$_GET['search'].'" title="На 1 страницу назад" class="greencurve"> <</a></li>';
                                        }
                                    } else {
                                        echo '<li><a href="" class="greencurve" onclick="return false"><< </a></li>';
                                        echo '<li><a href="" class="greencurve" onclick="return false"><</a></li>';
                                    }

                                    if($_GET['key2']-5 > 0){
                                        echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']-5).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']-5).'</a></li>';
                                    }
                                    if($_GET['key2']-4 > 0){
                                        echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']-4).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']-4).'</a></li>';
                                    }
                                    if($_GET['key2']-3 > 0){
                                        echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']-3).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']-3).'</a></li>';
                                    }
                                    if($_GET['key2']-2 > 0){
                                        echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']-2).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']-2).'</a></li>';
                                    }
                                    if($_GET['key2']-1 > 0){
                                        echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']-1).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']-1).'</a></li>';
                                    }
                                    echo '<li><a href="" class="greencurveactive" onclick="return false">'.$_GET['key2'].'</a></li>'; // вывод текущей страницы
                                    // если у нас не последняя страница - выводим ссылку
                                    if ($_GET['key2'] != $pk) {
                                        if($_GET['key2']+1 <= $pk) {
                                            if($_GET['key2']+1 <= $pk){
                                                echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']+1).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']+1).'</a></li>';
                                            }
                                            if($_GET['key2']+2 <= $pk){
                                                echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']+2).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']+2).'</a></li>';
                                            }
                                            if($_GET['key2']+3 <= $pk){
                                                echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']+3).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']+3).'</a></li>';
                                            }
                                            if($_GET['key2']+4 <= $pk){
                                                echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']+4).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']+4).'</a></li>';
                                            }
                                            if($_GET['key2']+5 <= $pk){
                                                echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']+5).'?search='.$_GET['search'].'" class="greencurve">'.($_GET['key2']+5).'</a></li>';
                                            }
                                            echo '<li><a href="/static/main/Каталог товаров/'.($_GET['key2']+1).'?search='.$_GET['search'].'" title="На 1 страницу вперед" class="greencurve">> </a></li>';
                                        }
                                        echo '<li><a href="/static/main/Каталог товаров/' . $pk . '?search='.$_GET['search'].'" title="В конец" class="greencurve">>></a></li>';
                                    } else {
                                        echo '<li><a href="" class="greencurve" onclick="return false;"> ></a></li>';
                                        echo '<li><a href="" class="greencurve" onclick="return false;"> >></a></li>';
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            <?php }
?>

<?php }else{ ?>
    <div class="tg">
        Добро пожаловать на наш сайт.<br><br>
        <div class="tgt">
            На сайте вы можете:<br><br>
            Найти необходимый товар и увидеть цены.<br><br>
            Добавить товар в корзину и оформить заказ.<br><br>
            Заказать необходимый товар,если он отсутствует в каталоге.<br><br>
            Заказать товар на иномарку.<br><br>
            Зарегистрироваться и отслеживать состояние Вашего заказа через личный кабинет.<br>
        </div>
    </div>
<?php } ?>
