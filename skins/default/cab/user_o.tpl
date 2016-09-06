<?php 
	if(isset($_SESSION['user']) && $_SESSION['user']['access'] == 5){ ?>
<div class="user_one">
<form action="" method="post">
Логин: <input type="text" name="login" value="<?php echo $row['login']; ?>"><br><br>
Пароль:<input type="text" name="pass"><br><br>
<!-- Если юзер активен (1) или забанен (2) - ставим Радио "Разрешён"\"Забанен"  - выбранным  -->
Доступ:<input type="radio" name="ds" <?php if($row['active']==1) echo 'checked'; ?> value="Разрешён">Разрешён 
	   <input type="radio" name="ds" <?php if($row['active']==2) echo 'checked'; ?> value="Забанить">Забанен(ить)<br><br>
E-mail:<input type="text" name="email" value="<?php echo $row['email']; ?>"><br><br>
Права:<select name="sel">
<!-- Смотрим какой доступ у юзера - и ставим нужную вкладку выбранной -->
		<option value="Не активирован" <?php if($row['access']==0) echo 'selected'; ?>>Не активен <br> 
	    <option value="Администратор" <?php if($row['access']==5) echo 'selected'; ?>>Администратор<br>
		<option value="Пользователь" <?php if($row['access']==1) echo 'selected'; ?>>Пользователь<br>
	  </select><br><br>
Ip-адрес: <?php echo $row['ip'];?><br><br>
Зарегистрирован: <?php $d = q("
					SELECT DATE_FORMAT('".$row['data']."', '%d-%m-%Y')
					");
			 $dt = mysqli_fetch_assoc($d);
			 foreach($dt as $v)
			   echo $v;   // Выводим отформатированную дату
		?><br><br>
Последняя активность: <?php $d = q("
									SELECT DATE_FORMAT('".$row['lastdate']."','%d-%m-%Y %H:%i:%s')
								 "); 
						    $dt = mysqli_fetch_assoc($d);
							foreach($dt as $v) 
								echo $v;  // Выводим отформатированную дату
					  ?><br><br>
	Аватарка:<?php
	if(!empty($row['img'])){ ?>
		<img src="<?php echo $row['img']; ?>">
		<div class="delav"><a href="/cab/user_o/<?php echo $row['id'];?>/delav">
				<img src="/img/cross.png" alt="delete" title="Удалить аватар" onclick="if(!confirm('Вы действительно хотите удалить аватар?'))return false;"></a>
		</div> <?php
	} else{
		echo ' -<br><br>';
	} ?>
	<a href="/cab/zakazi_us/<?php echo hc($row['id']); ?>"><?php echo 'Заказы '.$row['login']; ?></a><br><br>
	<input type="submit" class="redactr" name="redct" value="Отредактировать">
	<input type="submit" class="redactr" name="dell" value="Удалить пользователя" onclick="if(!confirm('Вы действительно хотите удалить пользователя?'))return false;">
</form>
</div>
<?php } ?>