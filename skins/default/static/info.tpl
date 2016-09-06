<div class="news">
    Новости и новые поступления<br>
    <div class="newst">
    <?php while($resn = $res->fetch_assoc()){ ?>
            <div class="date">
            <?php echo $resn['data']; ?>
            </div>
        <?php  echo $resn['news'];
                if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){ ?>
            <a href="/static/info/del/<?php echo $resn['id'];?>">Удалить</a>
            <?php } ?><hr><br>
    <?php } if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){ ?>
        <form action="" method="post">
            <textarea name="news" rows="5" cols="125"></textarea><br>
            <input type="submit" name="addnews" value="Добавить">
        </form>
    <?php } ?></div>
</div>
<div class="paginator_n">
    <div class="pagination"><ul><?php
            if(!isset($_GET['key1'])){ // если у нас нет страницы то создаем ее
                $_GET['key1'] = 1;
            }
            if(isset($pk) && $pk!=0 && !isset($_GET['key2'])) {
                // Если у нас key2 - цифра, значит мы в категории или каталоге без буквы
                if (is_numeric($_GET['key1'])) {
                    if ($_GET['key1'] != 1) {
                        echo '<li><a href="/static/info/1" title="В начало" class="greencurve"> << </a></li>';
                        if ($_GET['key1'] - 1 != 0) {
                            echo '<li><a href="/static/info/' . ($_GET['key1'] - 1) . '" title="На 1 страницу назад" class="greencurve"> <</a></li>';
                        }
                    } else {
                        echo '<li><a href="" class="greencurve" onclick="return false"><< </a></li>';
                        echo '<li><a href="" class="greencurve" onclick="return false"><</a></li>';
                    }

                    if ($_GET['key1'] - 5 > 0) {
                        echo '<li><a href="/static/info/'. ($_GET['key1'] - 5) . '" class="greencurve">' . ($_GET['key1'] - 5) . '</a></li>';
                    }
                    if ($_GET['key1'] - 4 > 0) {
                        echo '<li><a href="/static/info/'. ($_GET['key1'] - 4) . '" class="greencurve">' . ($_GET['key1'] - 4) . '</a></li>';
                    }
                    if ($_GET['key1'] - 3 > 0) {
                        echo '<li><a href="/static/info/'. ($_GET['key1'] - 3) . '" class="greencurve">' . ($_GET['key1'] - 3) . '</a></li>';
                    }
                    if ($_GET['key1'] - 2 > 0) {
                        echo '<li><a href="/static/info/'. ($_GET['key1'] - 2) . '" class="greencurve">' . ($_GET['key1'] - 2) . '</a></li>';
                    }
                    if ($_GET['key1'] - 1 > 0) {
                        echo '<li><a href="/static/info/'. ($_GET['key1'] - 1) . '" class="greencurve">' . ($_GET['key1'] - 1) . '</a></li>';
                    }
                    echo '<li><a href="" class="greencurveactive" onclick="return false">' . $_GET['key1'] . '</a></li>'; // вывод текущей страницы
                    // если у нас не последняя страница - выводим ссылку
                    if ($_GET['key1'] != $pk) {
                        if ($_GET['key1'] + 1 <= $pk) {
                            if ($_GET['key1'] + 1 <= $pk) {
                                echo '<li><a href="/static/info/'. ($_GET['key1'] + 1) . '" class="greencurve">' . ($_GET['key1'] + 1) . '</a></li>';
                            }
                            if ($_GET['key1'] + 2 <= $pk) {
                                echo '<li><a href="/static/info/'. ($_GET['key1'] + 2) . '" class="greencurve">' . ($_GET['key1'] + 2) . '</a></li>';
                            }
                            if ($_GET['key1'] + 3 <= $pk) {
                                echo '<li><a href="/static/info/'. ($_GET['key1'] + 3) . '" class="greencurve">' . ($_GET['key1'] + 3) . '</a></li>';
                            }
                            if ($_GET['key1'] + 4 <= $pk) {
                                echo '<li><a href="/static/info/'. ($_GET['key1'] + 4) . '" class="greencurve">' . ($_GET['key1'] + 4) . '</a></li>';
                            }
                            if ($_GET['key1'] + 5 <= $pk) {
                                echo '<li><a href="/static/info/'. ($_GET['key1'] + 5) . '" class="greencurve">' . ($_GET['key1'] + 5) . '</a></li>';
                            }
                            echo '<li><a href="/static/info/'. ($_GET['key1'] + 1) . '" title="На 1 страницу вперед" class="greencurve">> </a></li>';
                        }
                        echo '<li><a href="/static/info/'. $pk . '" title="В конец" class="greencurve">>></a></li>';
                    } else {
                        echo '<li><a href="" class="greencurve" onclick="return false;"> ></a></li>';
                        echo '<li><a href="" class="greencurve" onclick="return false;"> >></a></li>';
                    }
                }
            }
            ?></ul>
    </div></div>