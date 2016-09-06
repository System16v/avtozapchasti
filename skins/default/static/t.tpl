<?php if($_GET['page'] == 't' && isset($_GET['key1']) && isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){ ?>
<div class="tt">
    <form action="" method="post">
        <table>
            <tr>
              <td>
                  Название товара:
              </td>
              <td>
                  <input type="text" name="nm" value="<?php echo $tr['name']?>" size="23px">
              </td>
            </tr>
            <tr>
                <td>
                    Модель:
                </td>
                <td>
                    <input type="text" name="m" value="<?php echo $tr['model']?>" size="23px">
                </td>
            </tr>
            <tr>
                <td>
                    Фирма:
                </td>
                <td>
                    <input type="text" name="fr" value="<?php echo $tr['firm']?>" size="23px;">
                </td>
            </tr>
            <tr>
                <td>
                    Цена:
                </td>
                <td>
                    <input type="text" name="cn" value="<?php echo $tr['cena']?>" size="23px;">
                </td>
            </tr>
            <tr>
                <td>
                    Категория:
                </td>
                <td>
                    <select name="ct"><?php
                        while($ct = $c->fetch_assoc()) {
                            if ($ct['name'] == $tr['cat']) {
                                echo '<option value="'.$ct['name'].'" selected>' . $ct['name'] . '</option>';
                            } else {
                                echo '<option value="' . $ct['name'] . '">' . $ct['name'] . '</option>';
                            }
                        }
                        ?></select>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="knp"> <input type="submit" name="okt" value="Сохранить"></td>
            </tr>
        </table>

    </form>
</div>
<?php }else{
    header("Location: /404");
    exit();
} ?>