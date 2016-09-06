<div id="cat">
    <?php
    if(!isset($_COOKIE['addt'])){
        echo '<span id="catt">Корзина пуста</span>';
    }elseif($res->num_rows){
        ?>
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
                                <td class="fr">
                                    <?php echo $v1; ?>
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
                </tr>
                <?php      } ?>
            </table>
        <span class="summof"><b>Общая сумма:</b>
            <?php
                echo '<div id="sum" class="summa">'.$sum.'</div>'.'<div class="sm"><b>,00р.</b></div><div class="clear"></div>'; // выводим общую сумму
            ?></span>
        <form action="" method="post">
            <div class="forma">
                <span class="ofz"> Оформление заказа:<br><br></span>
                <table class="ofzk">
                    <tr>
                        <td class="ofn">Имя * </td>
                        <td class="ofnf">
                            <span class="ofnfe"><?php echo '&nbsp;'.@hc($error['imya']).'<br>'; ?></span>
                            <?php if(!isset($_SESSION['user'])){ ?>&nbsp;<input type="text" name="imya" value="<?php if(isset($_POST['imya']))echo $_POST['imya'];?>"> <?php } else{ echo '&nbsp;'.$_SESSION['user']['login']; }?>
                        </td>
                    </tr>
                    <tr>
                        <td class="ofp">Телефон * </td>
                        <td>
                            <span class="ofpe"><?php echo '&nbsp;'.@$error['tel'].'<br>'; ?></span>
                            <?php if(!isset($_SESSION['user'])){ ?>
                                &nbsp;<input type="text" name="tel" value="<?php if(isset($_POST['tel']))echo $_POST['tel'];?>">
                            <?php } else{
                                         echo '&nbsp;<input type="text" name="tel" value="'.$_SESSION['user']['phone'].'">';
                                    }?><br></td>
                    </tr>
                    <tr>
                        <td class="ofp">E-mail * </td>
                        <td>
                            <span class="ofnfe"><?php echo '&nbsp;'.@$error['email'].'<br>'; ?></span>
                            <?php if(!isset($_SESSION['user'])){ ?>
                                &nbsp;<input type="text" name="email" value="<?php if(isset($_POST['email']))echo $_POST['email'];?>">
                            <?php } else{
                                echo '&nbsp;'.$_SESSION['user']['email'];
                            }?></td>
                    </tr>
                    <tr>
                        <td>Доставка * <br></td>
                        <td class="ofd">
                            <span class="ofnfe"><?php echo @$error['addrs']; echo '&nbsp;'.@$error['som'].'<br>'; ?></span>
                            <input type="radio" id="sm" class="develery" name="som" value="0">Самовывоз, ул. Харьковская 119<br>&nbsp;&nbsp;&nbsp;&nbsp; с 09-00 до 18-00<br>
                            <input type="radio" id="home" class="develery" name="som" value="60">На дом. В течении дня, до 21-00 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+60,00р. к заказу<br>
                            <input type="radio" id="homes" class="develery" name="som" value="100">Срочная. В течении часа, после<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;создания заказа, +100,00р. к заказу</td>
                    </tr>
                </table>
                <table>
                    <tr id="address" style="display: none">
                        <div class="dost">
                            <td class="addr"><b>Адрес</b>:*</td>
                            <td class="addrt"><textarea name="addrs" class="txar" value="<?php if(isset($_POST['addrs']))echo $_POST['addrs'];?>"></textarea></td>
                        </div>
                    </tr>
                    <tr>
                        <td>Комментарий</td>
                        <td><textarea name="comm" class="comm"></textarea> </td>
                    </tr>
                </table>
                <br>Оплата за товар производится по факту, при вручении.<br>
                <input type="submit" name="zakazok" value="Подтвердить" class="podt">
            </div>
        </form>
    <?php }else{
        echo '<span id="catt">Корзина пуста</span>';
    } ?>
</div>
