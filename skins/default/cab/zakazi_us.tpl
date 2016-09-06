<div class="zkus">
<span class="zk"><b>Заказы <?php echo $row['login'];?></b></span><br>
<table class="table_blur">
    <tr>
        <th class="opis2">Товары</th>
        <th class="d">Доставка</th>
        <th class="sost">Состояние заказа</th>
        <th class="oplata">Оплата</th>
        <th class="delz">Уд.</th>
    </tr>
    <?php		$number = 1; while($r = $resz->fetch_assoc()){ ?>
        <tr>
            <form action="" method="post">
            <td class="nmz" colspan="5"><?php
                echo 'Id - '.$r['id'];
                $rd = q("
								SELECT DATE_FORMAT('".$r['data']."','%d-%m-%Y %H:%i:%s')
							  ");
                $rdr = $rd->fetch_assoc();
                foreach ($rdr as $v)
                    echo '<br>'.$v;
                ?></td>
        </tr>
        <tr>
            <td class="opis2"><textarea name="oz" rows="3" cols="42"><?php
                echo $r['zakaz']; ?></textarea>
<?php                if($r['dostavka'] == 'На дом'){
                       echo 'Доставка на дом 60,00р.';
                    }
                    if($r['dostavka'] == 'Срочная'){
                       echo 'Доставка на дом, срочная 100,00р.';
                    } ?>
            </td>
            <td class="d">
                <select name="ds">
                    <option name="Самовывоз" value="Самовывоз" <?php if($r['dostavka'] == 'Самовывоз')echo 'selected';?>>Самовывоз</option>
                    <option name="На дом" value="На дом" <?php if($r['dostavka'] == 'На дом')echo 'selected';?>>На дом</option>
                    <option name="Срочная" value="Срочная" <?php if($r['dostavka'] == 'Срочная')echo 'selected';?>>Срочная</option>
                </select>
            </td>
            <td class="sost">
                <select name="sost">
                    <option name="Принят" value="принят" <?php if($r['status'] == 'принят')echo 'selected';?>>Принят</option>
                    <option name="В обработке" value="в обработке" <?php if($r['status'] == 'в обработке')echo 'selected';?>>В обработке</option>
                    <option name="Сформирован" value="сформирован" <?php if($r['status'] == 'сформирован')echo 'selected';?>>Сформирован</option>
                    <option name="Заказан" value="заказан" <?php if($r['status'] == 'заказан')echo 'selected'; ?>>Заказан</option>
                    <option name="Доставляется" value="доставляется" <?php if($r['status'] == 'доставляется')echo 'selected';?>>Доставляется</option>
                    <option name="Завершен" value="завершен" <?php if($r['status'] == 'завершен')echo 'selected';?>>Завершен</option>
                    <option name="Отказ" value="отказ" <?php if($r['status'] == 'отказ')echo 'selected';?>>Отказ</option>
                </select>
            </td>
            <td class="oplata">
                <select name="opl">
                    <option name="Оплачено" value="оплачено" <?php if($r['oplata'] == 'оплачено')echo 'selected';?>>Оплачено</option>
                    <option name="Не оплачено" value="не оплачено" <?php if($r['oplata'] == 'не оплачено')echo 'selected';?>>Не оплачено</option>
                </select>
            </td>
            <td class="delz"><a href="/cab/zakazi_us/<?php echo $row['id'];?>/<?php echo $_GET['key2']?>/del/<?php echo $r['id'];?>" onclick="if(!confirm('Вы действительно хотите удалить заказ?'))return false;"><img src="/img/cross.png" alt="delete"></a></td>
        </tr>
        <tr>
            <td class="summa" colspan="5">Общая сумма: <textarea name="sum" cols="20" rows="1"><?php echo $r['summ']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                Адрес:
            </td>
            <td colspan="4">
                <textarea name="adrs" rows="1" cols="56"><?php echo $r['adress']?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                Телефон:
            </td>
            <td colspan="4">
                <textarea name="phone" rows="1" cols="56"><?php echo $r['phone']?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                Email:
            </td>
            <td colspan="4">
                <textarea name="email" rows="1" cols="56"><?php echo $r['email']?></textarea>
            </td>
        </tr>
        <tr>
            <td>
               Комментарий:
            </td>
            <td colspan="4">
                <textarea name="comm" rows="1" cols="56"><?php echo $r['comm']?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="5"><span class="save"><input class="delal" type="submit" name="save" value="Сохранить"></span><hr></td>
        </tr><input type="hidden" name="id" value="<?php echo $r['id'];?>"> </form>
        <?php	++$number; } ?>
</table></div><?php if($number!=1){ ?>
<div class="paginator_nu">
    <div class="pagination"><ul><?php
            if(!isset($_GET['key2'])){ // если у нас нет страницы то создаем ее
                $_GET['key2'] = 1;
            }
            if(isset($pk)&&$pk!=0) {
                // Если у нас key2 - цифра, значит мы в категории или каталоге без буквы
                if (is_numeric($_GET['key2'])) {
                    if ($_GET['key2'] != 1) {
                        echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/1" title="В начало" class="greencurve"> << </a></li>';
                        if ($_GET['key2'] - 1 != 0) {
                            echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] - 1) . '" title="На 1 страницу назад" class="greencurve"> <</a></li>';
                        }
                    } else {
                        echo '<li><a href="" class="greencurve" onclick="return false"><< </a></li>';
                        echo '<li><a href="" class="greencurve" onclick="return false"><</a></li>';
                    }

                    if ($_GET['key2'] - 5 > 0) {
                        echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] - 5) . '" class="greencurve">' . ($_GET['key2'] - 5) . '</a></li>';
                    }
                    if ($_GET['key2'] - 4 > 0) {
                        echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] - 4) . '" class="greencurve">' . ($_GET['key2'] - 4) . '</a></li>';
                    }
                    if ($_GET['key2'] - 3 > 0) {
                        echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] - 3) . '" class="greencurve">' . ($_GET['key2'] - 3) . '</a></li>';
                    }
                    if ($_GET['key2'] - 2 > 0) {
                        echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] - 2) . '" class="greencurve">' . ($_GET['key2'] - 2) . '</a></li>';
                    }
                    if ($_GET['key2'] - 1 > 0) {
                        echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] - 1) . '" class="greencurve">' . ($_GET['key2'] - 1) . '</a></li>';
                    }
                    echo '<li><a href="" class="greencurveactive" onclick="return false">' . $_GET['key2'] . '</a></li>'; // вывод текущей страницы
                    // если у нас не последняя страница - выводим ссылку
                    if ($_GET['key2'] != $pk) {
                        if ($_GET['key2'] + 1 <= $pk) {
                            if ($_GET['key2'] + 1 <= $pk) {
                                echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] + 1) . '" class="greencurve">' . ($_GET['key2'] + 1) . '</a></li>';
                            }
                            if ($_GET['key2'] + 2 <= $pk) {
                                echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] + 2) . '" class="greencurve">' . ($_GET['key2'] + 2) . '</a></li>';
                            }
                            if ($_GET['key2'] + 3 <= $pk) {
                                echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] + 3) . '" class="greencurve">' . ($_GET['key2'] + 3) . '</a></li>';
                            }
                            if ($_GET['key2'] + 4 <= $pk) {
                                echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] + 4) . '" class="greencurve">' . ($_GET['key2'] + 4) . '</a></li>';
                            }
                            if ($_GET['key2'] + 5 <= $pk) {
                                echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] + 5) . '" class="greencurve">' . ($_GET['key2'] + 5) . '</a></li>';
                            }
                            echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . ($_GET['key2'] + 1) . '" title="На 1 страницу вперед" class="greencurve">> </a></li>';
                        }
                        echo '<li><a href="/cab/zakazi_us/' . $_GET['key1'] . '/' . $pk . '" title="В конец" class="greencurve">>></a></li>';
                    } else {
                        echo '<li><a href="" class="greencurve" onclick="return false;"> ></a></li>';
                        echo '<li><a href="" class="greencurve" onclick="return false;"> >></a></li>';
                    }
                }
            }
            ?></ul>
    </div></div><?php } ?>
<?php if($number==1){ ?> <span class="nz">У пользователя нет заказов</span><?php } ?>