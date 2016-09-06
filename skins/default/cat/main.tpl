<div id="cat">
    <?php
    if(!isset($_COOKIE['addt'])){
        echo '<span id="catt">Корзина пуста</span>';
    }elseif($res->num_rows){
    ?> <form action="" method="post">
    <span id="catt">Корзина</span>
    <div id="attn"><br>
        Внимание! Позиции, находящиеся в корзине больше недели и не отправленные в заказ,
        будут удалены автоматически.
    </div>

    <table class="table_blur">
        <tr>
            <th>Фото</th>
            <th>Бренд</th>
            <th class="opis">Описание</th>
            <th class="md">Модель</th>
            <th class="md">Цена</th>
            <th class="kolich">Кол-во</th>
            <th>Сумма</th>
            <th>Уд.</th>
        </tr>
        <tr>
        <?php // Выводим товары которые в корзине на экран
           while($r = $res->fetch_assoc()){ ?>
                <td>
                    <div id="foto">
                        <a class="gallery" href="<?php if(!empty($r['img'])){ echo $r['img'];}else{ echo '-';};?>">
                            <img src="/img/fotom.png" alt="photo">
                        </a>
                    </div>
                </td>
                <td class="fr">
                  <?php echo $r['firm'];?>
                </td>
                <td class="opis">
                  <?php echo $r['name'];?>
                </td>
                <td class="fr">
                  <?php echo $r['model'];?>
                </td>
                <td class="fr">
                    <?php echo $r['cena'].',00р.';?>
                </td>

                    <?php
                    foreach ($_COOKIE['addt'] as $k=>$v){
                        foreach ($v as $k1=>$v1){
                            if($k1 == 'kol' && $k == $r['id']){ ?>
                                <td style="text-align: center;"> <!-- id="klzs" (было у инпут текст, хз для чего) -->
                                    <input type="text" name="kol[]" size="1" value="<?php echo $v1; ?>" style="text-align: center">
                                    <input type="hidden" name="ids[]" value="<?php echo $k; ?>">
                                </td>
<?php                       }
                        }
                    } ?>
                <td>
<?php          // Создаем переменную для суммы если ее не было
               if(!isset($sum))
                   $sum = 0;
               echo (int)$_COOKIE['addt'][$r['id']]['kol'] * $r['cena'].',00р.';
               $sum += (int)$_COOKIE['addt'][$r['id']]['kol'] * $r['cena']; // прибавляем текущую стоимость товара к общей сумме ?>
               </td>
               <td><a href="/cat/main/del/<?php echo $r['id']; ?>"><img src="/img/cross.png" alt="delete" title="Удалить"></a></td>
               </tr>
<?php      } ?>
    </table>
        <span class="summ">Общая сумма:&nbsp;&nbsp; <?php echo '<b>'.$sum.',00р.</b>'; // выводим общую сумму ?></span>
        <input type="submit" name="deleteAll" value="Очистить корзину" class="delall">
        <input type="submit" name="prs" class="prsch" value="Пересчитать">
        <input type="submit" name="zakaz" value="Оформить заказ" class="zakazat">
        </form></div>
<?php }else{
        echo '<span id="catt">Корзина пуста</span>';
    } ?>
</div>