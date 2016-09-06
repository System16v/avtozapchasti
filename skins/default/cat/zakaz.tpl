<div class="zakaz">
    <span class="zkzt">Заказать товар</span>
    <table class="table_blur">
        <tr>
            <th class="f">Фото</th>
            <th class="b">Бренд</th>
            <th class="ops">Описание</th>
            <th class="m">Модель</th>
            <th class="kolich">Кол-во</th>
        </tr>
        <tr>
            <td class="f">
                <a class="gallery" href="<?php if(!empty($pr['img'])){echo $pr['img'];}else{ echo ' - '; };?>"><img src="/img/fotom.png" alt="photo"></a>
            </td>
            <td class="b">
                <?php echo $pr['firm']; ?>
            </td>
            <td class="ops">
                <?php echo $pr['name']; ?>
            </td>
            <td class="m">
                <?php echo $pr['model']; ?>
            </td>
            <td class="fr">
                <form action="" method="post">
                    <input type="text" name="kol" value="<?php if(isset($_POST['kol'])){echo (int)$_POST['kol'];}else{echo 1;}?>" size="1" style="text-align: center;">
                    <input type="hidden" name="id" value="<?php echo $pr['id']; ?>">

            </td>
        </tr>
    </table><br>
        <div class="forma">
            <span class="zak"> Заказ<br><br></span>
        <table class="zkz">
            <tr>
                <td class="zim">Имя *</td>
                <td>
                    <span class="ofnfe"><?php echo '&nbsp;'.@$error['imya'].'<br>'; ?></span>
                    <?php if(isset($_SESSION['user'])){echo $_SESSION['user']['login'];}else{ ?>&nbsp;<input type="text" name="imya" value="<?php if(isset($_POST['imya']))echo $_POST['imya'];?>"> <?php } ?></td>

            </tr>
            <tr>
                <td class="zph">Телефон *</td>
                <td>
                <span class="ofnfe"><?php echo '&nbsp;'.@$error['tel'].'<br>'; ?></span>
                    <?php if(!isset($_SESSION['user'])){ ?>
                        &nbsp;<input type="text" name="tel" value="<?php if(isset($_POST['tel']))echo $_POST['tel'];?>">
                    <?php } else{
                        echo '&nbsp;<input type="text" name="tel" value="'.$_SESSION['user']['phone'].'">';
                    }?><br></td>

            </tr>
            <tr>
                <td class="zph">E-mail *</td>
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
                <td>
                    <span class="ofnfe"><?php echo '&nbsp;'.@$error['addrs']; echo '&nbsp;'.@$error['som'].'<br>'; ?></span>
                    <input type="radio" id="sm" class="develery" name="som" value="0">Самовывоз, ул. Харьковская 119<br>&nbsp;&nbsp;&nbsp;&nbsp; с 09-00 до 18-00<br>
                    <input type="radio" id="home" class="develery" name="som" value="60">На дом. В течении дня, до 21-00<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+60,00р. к заказу<br>
                    <input type="radio" id="homes" class="develery" name="som" value="100">Срочная. В течении часа, после<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;создания заказа, +100,00р. к заказу</td>
            </tr>
        </table>
            <table>
                <tr id="address" style="display: none">
                    <div class="zkad">
                        <td><b>Адрес</b>:*</td>
                        <td class="zkadt"><textarea name="addrs" rows="3" cols="21" value="<?php if(isset($_POST['addrs']))echo $_POST['addrs'];?>"></textarea></td>
                    </div>
                </tr>
                <tr>
                    <td>Комментарий</td>
                    <td><textarea name="comm" cols="21" rows="5"><?php if(isset($_POST['comm']))echo hc($_POST['comm']);?></textarea> </td>
                </tr>
            </table>
            <br>Заказывая товар под заказ, Вы обязуетесь забрать товар,<br>
            после того как он будет заказан. Срок поставки зависит от<br>
            наличия товара на складе у поставщика. При обработке и<br>
            перед заказом Вашего товара, мы обязательно позвоним<br>
            Вам по указанному Вами телефону, и проинформируем Вас<br>
            о его наличии и о сроках его поставки. Если Вас не устроит<br>
            срок поставки, Вы можете отказаться от заказа, пока товар<br>
            еще не был заказан.<br>
            <br>Оплата за товар производится по факту, при вручении.<br><br>
        <span class="knz"><input type="submit" name="zk" value="Заказать" class="zkaz"></span>
    </form>
    </div>
</div>
