<div class="allzak">
    <?php if(!isset($_GET['key1'],$_GET['key2'])){?>
        <span class="tallz">Все заказы</span>
        <div class="sr">
            <form action="" method="post">
            <input type="text" name="srch" placeholder="поиск заказа по №..." size="14">
        <?php if(isset($_SESSION['error'])){
                echo $_SESSION['error'];
                unset($_SESSION['error']);
        }?>
        </form>
        </div>
        <table class="table_blur">
        <tr>
            <th>
                №
            </th>
            <th>
                Id
            </th>
            <th>
                Пользователь
            </th>
            <th>
                Время создания
            </th>
            <th class="delz">Уд.</th>
        </tr>
    <?php $n = 1; while($z = $zakazi->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $n;?></td>
                        <td><?php echo $z['id'];?></td>
                        <td><a href="/cab/allz/<?php echo $z['login']; ?>/<?php echo $z['id'];?>"><?php echo $z['login'];?></a></td>
                        <td><?php
                                    $rd = q("
                                           SELECT DATE_FORMAT('".$z['data']."','%d-%m-%Y %H:%i:%s')
                                            ");
                            $rdr = $rd->fetch_assoc();
                            foreach ($rdr as $v)
                            echo $v;
                            ?></td>
                        <td class="delz">
                            <a href="/cab/allz/del/<?php echo $z['id'];?>" onclick="if(!confirm('Вы действительно хотите удалить заказ?'))return false;"><img src="/img/cross.png" alt="delete"></a>
                        </td>
                    </tr>
    <?php ++$n; } ?>
    </table>
    <?php }else{ ?>
        <span class="tzone">Заказ пользователя <?php echo $z['login']; ?> № <?php echo $z['id']; ?></span>
             <div class="tblz">
                 <form action="" method="post">
                 <table class="table_blur">
                  <tr>
                      <th class="opis2">Заказ</th>
                      <th class="d">Доставка</th>
                      <th class="sost">Состояние заказа</th>
                      <th class="oplata">Оплата</th>
                  </tr>
                 <tr>
                     <td class="opis2"><textarea name="oz" rows="3" cols="42"><?php
                             echo $z['zakaz']; ?></textarea><br>
                         <?php                if($z['dostavka'] == 'На дом'){
                             echo 'Доставка на дом 60,00р.';
                         }
                         if($z['dostavka'] == 'Срочная'){
                             echo 'Доставка на дом, срочная 100,00р.';
                         } ?>
                     </td>
                     <td class="d">
                         <select name="ds">
                             <option name="Самовывоз" value="Самовывоз" <?php if($z['dostavka'] == 'Самовывоз')echo 'selected';?>>Самовывоз</option>
                             <option name="На дом" value="На дом" <?php if($z['dostavka'] == 'На дом')echo 'selected';?>>На дом</option>
                             <option name="Срочная" value="Срочная" <?php if($z['dostavka'] == 'Срочная')echo 'selected';?>>Срочная</option>
                         </select>
                     </td>
                     <td class="sost">
                         <select name="sost">
                             <option name="Принят" value="принят" <?php if($z['status'] == 'принят')echo 'selected';?>>Принят</option>
                             <option name="В обработке" value="в обработке" <?php if($z['status'] == 'в обработке')echo 'selected';?>>В обработке</option>
                             <option name="Сформирован" value="сформирован" <?php if($z['status'] == 'сформирован')echo 'selected';?>>Сформирован</option>
                             <option name="Заказан" value="заказан" <?php if($z['status'] == 'заказан')echo 'selected'; ?>>Заказан</option>
                             <option name="Доставляется" value="доставляется" <?php if($z['status'] == 'доставляется')echo 'selected';?>>Доставляется</option>
                             <option name="Завершен" value="завершен" <?php if($z['status'] == 'завершен')echo 'selected';?>>Завершен</option>
                             <option name="Отказ" value="отказ" <?php if($z['status'] == 'отказ')echo 'selected';?>>Отказ</option>
                         </select>
                     </td>
                     <td class="oplata">
                         <select name="opl">
                             <option name="Оплачено" value="оплачено" <?php if($z['oplata'] == 'оплачено')echo 'selected';?>>Оплачено</option>
                             <option name="Не оплачено" value="не оплачено" <?php if($z['oplata'] == 'не оплачено')echo 'selected';?>>Не оплачено</option>
                         </select>
                     </td>
                     <tr>
                         <td class="summa" colspan="5">Общая сумма: <textarea name="sum" cols="20" rows="1"><?php echo $z['summ']; ?></textarea>
                         </td>
                     </tr>
                     <tr>
                         <td>
                             Адрес:
                         </td>
                         <td colspan="4">
                             <textarea name="adrs" rows="1" cols="56"><?php echo $z['adress']?></textarea>
                         </td>
                     </tr>
                     <tr>
                         <td>
                             Телефон:
                         </td>
                         <td colspan="4">
                             <textarea name="phone" rows="1" cols="56"><?php echo $z['phone']?></textarea>
                         </td>
                     </tr>
                     <tr>
                         <td>
                             Email:
                         </td>
                         <td colspan="4">
                             <textarea name="email" rows="1" cols="56"><?php echo $z['email']?></textarea>
                         </td>
                     </tr>
                     <tr>
                         <td>
                             Комментарий:
                         </td>
                         <td colspan="4">
                             <textarea name="comm" rows="1" cols="56"><?php echo $z['comm']?></textarea>
                         </td>
                     </tr>
                     <tr>
                         <td colspan="4"><span class="save"><input type="submit" name="save" value="Сохранить"></span><hr></td>
                     </tr><input type="hidden" name="id" value="<?php echo $z['id'];?>">
                 </table>
                 </form>
             </div>
<?php    } ?>
</div><?php if(!isset($_GET['key1'],$_GET['key2'])){ ?>
<div class="paginator_n">
    <div class="pagination"><ul><?php
            if(!isset($_GET['key1'])){ // если у нас нет страницы то создаем ее
                $_GET['key1'] = 1;
            }
            if(isset($pk) && $pk!=0 && !isset($_GET['key2'])) {
                // Если у нас key2 - цифра, значит мы в категории или каталоге без буквы
                if (is_numeric($_GET['key1'])) {
                    if ($_GET['key1'] != 1) {
                        echo '<li><a href="/cab/allz/1" title="В начало" class="greencurve"> << </a></li>';
                        if ($_GET['key1'] - 1 != 0) {
                            echo '<li><a href="/cab/allz/' . ($_GET['key1'] - 1) . '" title="На 1 страницу назад" class="greencurve"> <</a></li>';
                        }
                    } else {
                        echo '<li><a href="" class="greencurve" onclick="return false"><< </a></li>';
                        echo '<li><a href="" class="greencurve" onclick="return false"><</a></li>';
                    }

                    if ($_GET['key1'] - 5 > 0) {
                        echo '<li><a href="/cab/allz/'. ($_GET['key1'] - 5) . '" class="greencurve">' . ($_GET['key1'] - 5) . '</a></li>';
                    }
                    if ($_GET['key1'] - 4 > 0) {
                        echo '<li><a href="/cab/allz/'. ($_GET['key1'] - 4) . '" class="greencurve">' . ($_GET['key1'] - 4) . '</a></li>';
                    }
                    if ($_GET['key1'] - 3 > 0) {
                        echo '<li><a href="/cab/allz/'. ($_GET['key1'] - 3) . '" class="greencurve">' . ($_GET['key1'] - 3) . '</a></li>';
                    }
                    if ($_GET['key1'] - 2 > 0) {
                        echo '<li><a href="/cab/allz/'. ($_GET['key1'] - 2) . '" class="greencurve">' . ($_GET['key1'] - 2) . '</a></li>';
                    }
                    if ($_GET['key1'] - 1 > 0) {
                        echo '<li><a href="/cab/allz/'. ($_GET['key1'] - 1) . '" class="greencurve">' . ($_GET['key1'] - 1) . '</a></li>';
                    }
                    echo '<li><a href="" class="greencurveactive" onclick="return false">' . $_GET['key1'] . '</a></li>'; // вывод текущей страницы
                    // если у нас не последняя страница - выводим ссылку
                    if ($_GET['key1'] != $pk) {
                        if ($_GET['key1'] + 1 <= $pk) {
                            if ($_GET['key1'] + 1 <= $pk) {
                                echo '<li><a href="/cab/allz/'. ($_GET['key1'] + 1) . '" class="greencurve">' . ($_GET['key1'] + 1) . '</a></li>';
                            }
                            if ($_GET['key1'] + 2 <= $pk) {
                                echo '<li><a href="/cab/allz/'. ($_GET['key1'] + 2) . '" class="greencurve">' . ($_GET['key1'] + 2) . '</a></li>';
                            }
                            if ($_GET['key1'] + 3 <= $pk) {
                                echo '<li><a href="/cab/allz/'. ($_GET['key1'] + 3) . '" class="greencurve">' . ($_GET['key1'] + 3) . '</a></li>';
                            }
                            if ($_GET['key1'] + 4 <= $pk) {
                                echo '<li><a href="/cab/allz/'. ($_GET['key1'] + 4) . '" class="greencurve">' . ($_GET['key1'] + 4) . '</a></li>';
                            }
                            if ($_GET['key1'] + 5 <= $pk) {
                                echo '<li><a href="/cab/allz/'. ($_GET['key1'] + 5) . '" class="greencurve">' . ($_GET['key1'] + 5) . '</a></li>';
                            }
                            echo '<li><a href="/cab/allz/'. ($_GET['key1'] + 1) . '" title="На 1 страницу вперед" class="greencurve">> </a></li>';
                        }
                        echo '<li><a href="/cab/allz/'. $pk . '" title="В конец" class="greencurve">>></a></li>';
                    } else {
                        echo '<li><a href="" class="greencurve" onclick="return false;"> ></a></li>';
                        echo '<li><a href="" class="greencurve" onclick="return false;"> >></a></li>';
                    }
                }
            }
            ?></ul>
    </div></div>
<?php } ?>