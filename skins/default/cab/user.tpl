<?php
 // Если мы авторизованы как админ и есть права, то выводим список Юзеров
  if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && !isset($_POST['srch'])){ ?>

<div class="tus"> Пользователи</div>
<div class="users">
		<div class="frus"><form action="" method="post">
		<input type="text" name="srch" placeholder="поиск пользователя..." size="15">
		</form></div>
<?php
	while($row=mysqli_fetch_assoc($res)){ ?>
		<div class="useri">
			<a href="/cab/user_o/<?php echo hc($row['id']); ?>"><?php echo $row['login']; ?></a>
		</div>
	<?php
	}
  }else{ // Если нажимали поиск пользователей - выводим найденные или пишем что таких нет
	if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5 && isset($_POST['srch'])){ ?>
		<span class="srt">Найденные пользователи</span>
		<div class="sr">
			<div class="frus"><form action="" method="post">
				<input type="text" name="srch" placeholder="поиск пользователя..." size="15">
			</form></div>
		</div>
<?php
		while ($resr = mysqli_fetch_assoc($sr)) { ?>
			<div class="useri"><a href="/cab/user_o/<?php echo hc($resr['id']); ?>"><?php echo $resr['login']; ?></a>
			</div>
	<?php
		}
		if (!mysqli_num_rows($sr)) {
			echo '<br><span class="srt2">Извините - пользователей удовлетворяющих запросу не найдено</span><br>';
		}
	}
  }
?>
</div>
<div class="paginator_n">
	<div class="pagination">
		<ul>
			<?php
			if(!isset($_GET['key1'])){ // если у нас нет страницы то создаем ее
				$_GET['key1'] = 1;
			}
			if(isset($pk)&&$pk!=0) {
				// Если у нас key2 - цифра, значит мы в категории или каталоге без буквы
				if (is_numeric($_GET['key1'])) {
					if ($_GET['key1'] != 1) {
						echo '<li><a href="/cab/user/1" title="В начало" class="greencurve"> << </a></li>';
						if($_GET['key1']-1 != 0) {
							echo '<li><a href="/cab/user/'.($_GET['key1']-1).'" title="На 1 страницу назад" class="greencurve"> <</a></li>';
						}
					} else {
						echo '<li><a href="" class="greencurve" onclick="return false"><< </a></li>';
						echo '<li><a href="" class="greencurve" onclick="return false"><</a></li>';
					}

					if($_GET['key1']-5 > 0){
						echo '<li><a href="/cab/user/'.($_GET['key1']-5).'" class="greencurve">'.($_GET['key1']-5).'</a></li>';
					}
					if($_GET['key1']-4 > 0){
						echo '<li><a href="/cab/user/'.($_GET['key1']-4).'" class="greencurve">'.($_GET['key1']-4).'</a></li>';
					}
					if($_GET['key1']-3 > 0){
						echo '<li><a href="/cab/user/'.($_GET['key1']-3).'" class="greencurve">'.($_GET['key1']-3).'</a></li>';
					}
					if($_GET['key1']-2 > 0){
						echo '<li><a href="/cab/user/'.($_GET['key1']-2).'" class="greencurve">'.($_GET['key1']-2).'</a></li>';
					}
					if($_GET['key1']-1 > 0){
						echo '<li><a href="/cab/user/'.($_GET['key1']-1).'" class="greencurve">'.($_GET['key1']-1).'</a></li>';
					}
					echo '<li><a href="" class="greencurveactive" onclick="return false">'.$_GET['key1'].'</a></li>'; // вывод текущей страницы
					// если у нас не последняя страница - выводим ссылку
					if ($_GET['key1'] != $pk) {
						if($_GET['key1']+1 <= $pk) {
							if($_GET['key1']+1 <= $pk){
								echo '<li><a href="/cab/user/'.($_GET['key1']+1).'" class="greencurve">'.($_GET['key1']+1).'</a></li>';
							}
							if($_GET['key1']+2 <= $pk){
								echo '<li><a href="/cab/user/'.($_GET['key1']+2).'" class="greencurve">'.($_GET['key1']+2).'</a></li>';
							}
							if($_GET['key1']+3 <= $pk){
								echo '<li><a href="/cab/user/'.($_GET['key1']+3).'" class="greencurve">'.($_GET['key1']+3).'</a></li>';
							}
							if($_GET['key1']+4 <= $pk){
								echo '<li><a href="/cab/user/'.($_GET['key1']+4).'" class="greencurve">'.($_GET['key1']+4).'</a></li>';
							}
							if($_GET['key1']+5 <= $pk){
								echo '<li><a href="/cab/user/'.($_GET['key1']+5).'" class="greencurve">'.($_GET['key1']+5).'</a></li>';
							}
							echo '<li><a href="/cab/user/'.($_GET['key1']+1).'" title="На 1 страницу вперед" class="greencurve">> </a></li>';
						}
						echo '<li><a href="/cab/user/' . $pk . '" title="В конец" class="greencurve">>></a></li>';
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