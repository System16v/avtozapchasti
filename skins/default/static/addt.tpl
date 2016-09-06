<?php
if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){ ?>
    <div class="db">
        <span class="dbt">Добавление товара</span>
    <form action="" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>
                    Название товара:
                </td>
                <td>
                    <input type="text" name="nz">
                </td>
            </tr>
            <tr>
                <td>
                    Модель:
                </td>
                <td>
                    <input type="text" name="md">
                </td>
            </tr>
            <tr>
                <td>
                    Фирма:
                </td>
                <td>
                    <input type="text" name="fr">
                </td>
            </tr>
            <tr>
                <td>
                    Наличие(да\под заказ):
                </td>
                <td>
                    <input type="text" name="nl">
                </td>
            </tr>
            <tr>
                <td>
                    Цена:
                </td>
                <td>
                    <input type="text" name="cen">
                </td>
            </tr>
            <tr>
                <td>
                    Категория:
                </td>
                <td>
                    <select name="ct"><?php
                        while($c = $catg->fetch_assoc()){
                            echo '<option value="'.$c['name'].'">'.$c['name'].'</option>';
                        }
                        ?></select>
                </td>
            </tr>
        </table>
		<input type="file" id="file" name="file"><br>
        <input type="submit" class="dbtv" name="dob" value="Добавить">
    </form>
    </div>
<?php
}
?>