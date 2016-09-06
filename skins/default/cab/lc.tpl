	<?php if(isset($_SESSION['er']))echo '<span style="color: red; padding-left: 100px;">'.$_SESSION['er'].'</span>'; unset($_SESSION['er']);?>
<?php
	if(isset($_SESSION['user']) && !isset($_GET['key1'])){ ?>
	<div class="lc">
	<form action="" method="post" enctype="multipart/form-data">
	<table class="table_blur">
		<th colspan="2" style="text-align: center;">Личный кабинет пользователя</th>
		<tr>
			<td colspan="2" style="width:120px; padding-left: 160px;"><b><a href="/cab/lc/<?php echo $_SESSION['user']['id']; ?>">Мои заказы</a></b></td>
		</tr>
	<tr>
		<td style="width:120px;"><b>Зарегистрирован:</b></td>
		<td><?php // конвертируем дату в удобный вид
			$rd = q("
			SELECT DATE_FORMAT('".$rcb['data']."','%d-%m-%Y')
			");
			$rdr = $rd->fetch_assoc();
			foreach ($rdr as $v)
				echo $v; ?></td>
	</tr>

	<tr>
		<td><b>Логин:</b></td>
		<td><?php echo $rcb['login']; ?></td>
	</tr>
	
	<tr>
		<td><b>Дата рождения:</b></td>
		<td><input type="date" name="bth" value="<?php echo $rcb['bday']; ?>"></td>
	</tr>

	<tr>
		<td><b>Пол:</b></td>
		<td><select name="sex"><option value="муж">Мужской</option><option <?php if($rcb['sex'] == 'жен') echo 'selected'; ?> value="жен">Женский</option></select></td>
	</tr>

	<tr>	
		<td><b>Город:</b></td>
		<td><input type="text" name="city" value="<?php echo $rcb['city']; ?>"></td>
	</tr>
	
	<tr>
		<td><b>Web-site:</b></td>
		<td><input type="text" name="web" value="<?php echo hc($rcb['webs']); ?>"></td>
	</tr>
	
	<tr>
		<td><b>Skype:</b></td>
		<td><input type="text" name="skype" value="<?php echo hc($rcb['skype']); ?>"></td>
	</tr>
		<tr>
			<td><b>Телефон:</b></td>
			<td><input type="text" name="phone" value="<?php if($rcb['phone'] == 0){echo ' ';}else{ echo hc($rcb['phone']);} ?>"></td>
		</tr>
	<tr>
		<td><b>Аватар:</b></td>
		<td><?php
			if(!empty($rcb['img'])){ ?>
				<img src="<?php echo $rcb['img']; ?>">
				<div class="delav"><a href="/cab/lc/<?php echo $_SESSION['user']['id'];?>/delav">
						<img src="/img/cross.png" alt="delete" title="Удалить аватар" onclick="if(!confirm('Вы действительно хотите удалить аватар?'))return false;"></a>
				</div> <?php
			} else{
				echo 'У вас нет аватара';
			  } ?>
		</td>
	</tr>
		<tr>
			<td colspan="2"><input type="file" name="file">
				<input type="submit" name="sub" value="Загрузить аватар"></td>
		</tr>
	<tr>
		<td style="width:120px;"><b>Ваши увлечения:</b><br><br></td> 
		<td><textarea name="hobby"><?php echo hc($rcb['hobby']); ?></textarea><br><br></td>
	</tr>  
    
	</table>
	<table>
	<tr style="width: 400px;">
	  <input style="margin-left: 160px;" type="submit" name="submit" value="Сохранить" class="sav">
	</tr>
	</table>
  </form>
	</div>
<?php	
	}
?>

<?php if(isset($_GET['key1']) && isset($_SESSION['user']) && $pk!=0){ ?>
	<div class="lcz">
	<span class="zk"><b>Мои заказы</b></span><br>
	<table class="table_blur">
		<tr>
		<th class="number">№</th>
		<th class="opis2">Товары</th>
		<th class="d">Доставка</th>
		<th class="sost">Состояние заказа</th>
		<th class="oplata">Оплата</th>
		</tr>
<?php	$number=1;	while($r = $res->fetch_assoc()){ ?>
				<tr>
					<td class="nmz" colspan="5"><?php
							echo 'Заказ';
						$rd = q("
								SELECT DATE_FORMAT('".$r['data']."','%d-%m-%Y %H:%i:%s')
							  ");
						$rdr = $rd->fetch_assoc();
						foreach ($rdr as $v)
							echo '<br>'.$v;
						?></td>
				</tr>
				<tr>
					<td class="number"><?php echo $r['id']?></td>
					<td class="opis2"><a class="inz" href="" onclick=" return false;">Заказ</a> <div class="ops" style="display: none;"> <?php
						echo $r['zakaz'];
						if($r['dostavka'] == 'На дом'){
							echo '<br>Доставка на дом 60,00р.';
						}else if($r['dostavka'] == 'Срочная'){
							echo '<br>Доставка на дом, срочная 100,00р.';
						} ?></div></td>
					<td class="d"><?php echo $r['dostavka']; ?></td>
					<td class="sost">
						<?php
							if($r['status'] == 'завершен'){
								echo '<span style="color: green !important;">'.$r['status'].'</span>';
							}elseif($r['status']=='отказ'){
								echo '<span style="color: red !important;">'.$r['status'].'</span>';
							}elseif($r['status'] == 'заказан'){
								echo '<span style="color: #ff7f0b !important;">' .$r['status'].'</span>';
							}else{
								echo '<span style="color: blue !important;">'.$r['status'].'</span>';} ?>
					</td>
					<td class="oplata">
						<?php
							if($r['oplata'] == 'оплачено'){
								echo '<span style="color: green !important;">'.$r['oplata'].'</span>';
							}else{
								echo $r['oplata'];
							}?>
					</td>
				</tr>
				<tr>
					<td class="summa" colspan="5"><?php echo 'Общая сумма: '.$r['summ'].',00р.'; ?></td>
				</tr>
				<tr>
					<td colspan="5"><hr></td>
				</tr>
<?php	$number +=1; } ?>
</table></div>
	<div class="paginator_nu">
		<div class="pagination"><ul><?php
				if(!isset($_GET['key2'])){ // если у нас нет страницы то создаем ее
					$_GET['key2'] = 1;
				}
				if(isset($pk)&&$pk!=0) {
					// Если у нас key2 - цифра, значит мы в категории или каталоге без буквы
					if (is_numeric($_GET['key2'])) {
						if ($_GET['key2'] != 1) {
							echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/1" title="В начало" class="greencurve"> << </a></li>';
							if ($_GET['key2'] - 1 != 0) {
								echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] - 1) . '" title="На 1 страницу назад" class="greencurve"> <</a></li>';
							}
						} else {
							echo '<li><a href="" class="greencurve" onclick="return false"><< </a></li>';
							echo '<li><a href="" class="greencurve" onclick="return false"><</a></li>';
						}

						if ($_GET['key2'] - 5 > 0) {
							echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] - 5) . '" class="greencurve">' . ($_GET['key2'] - 5) . '</a></li>';
						}
						if ($_GET['key2'] - 4 > 0) {
							echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] - 4) . '" class="greencurve">' . ($_GET['key2'] - 4) . '</a></li>';
						}
						if ($_GET['key2'] - 3 > 0) {
							echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] - 3) . '" class="greencurve">' . ($_GET['key2'] - 3) . '</a></li>';
						}
						if ($_GET['key2'] - 2 > 0) {
							echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] - 2) . '" class="greencurve">' . ($_GET['key2'] - 2) . '</a></li>';
						}
						if ($_GET['key2'] - 1 > 0) {
							echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] - 1) . '" class="greencurve">' . ($_GET['key2'] - 1) . '</a></li>';
						}
						echo '<li><a href="" class="greencurveactive" onclick="return false">' . $_GET['key2'] . '</a></li>'; // вывод текущей страницы
						// если у нас не последняя страница - выводим ссылку
						if ($_GET['key2'] != $pk) {
							if ($_GET['key2'] + 1 <= $pk) {
								if ($_GET['key2'] + 1 <= $pk) {
									echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] + 1) . '" class="greencurve">' . ($_GET['key2'] + 1) . '</a></li>';
								}
								if ($_GET['key2'] + 2 <= $pk) {
									echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] + 2) . '" class="greencurve">' . ($_GET['key2'] + 2) . '</a></li>';
								}
								if ($_GET['key2'] + 3 <= $pk) {
									echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] + 3) . '" class="greencurve">' . ($_GET['key2'] + 3) . '</a></li>';
								}
								if ($_GET['key2'] + 4 <= $pk) {
									echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] + 4) . '" class="greencurve">' . ($_GET['key2'] + 4) . '</a></li>';
								}
								if ($_GET['key2'] + 5 <= $pk) {
									echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] + 5) . '" class="greencurve">' . ($_GET['key2'] + 5) . '</a></li>';
								}
								echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . ($_GET['key2'] + 1) . '" title="На 1 страницу вперед" class="greencurve">> </a></li>';
							}
							echo '<li><a href="/cab/lc/' . $_GET['key1'] . '/' . $pk . '" title="В конец" class="greencurve">>></a></li>';
						} else {
							echo '<li><a href="" class="greencurve" onclick="return false;"> ></a></li>';
							echo '<li><a href="" class="greencurve" onclick="return false;"> >></a></li>';
						}
					}
				}
				?></ul>
		</div></div>
	<?php if($number==1){?><span class="nz">У Вас пока еще нет заказов</span> <?php } ?>
<?php } ?>
