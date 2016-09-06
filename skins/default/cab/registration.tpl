<div class="registration">
	<div id="page-wrap">
		<form id="myform" method="post" action="">
			<div>
				<div class="field">
					<label for="personname" >Логин</label>
					<input class="inputfield textfield" name="login" type="text" value="<?php echo @htmlspecialchars($_POST['login']); ?>">
					<div class="erreg"><?php echo @htmlspecialchars($errors['login']); ?></div>
				</div>
				<div class="field">
					<label for="email" >E-mail</label>
					<input class="inputfield textfield" name="email" type="text" value="<?php echo @$_POST['email']; ?>">
					<div class="erreg"><?php echo @htmlspecialchars($errors['email']); ?></div>
				</div>
				<div class="field">
					<label for="website" >Пароль</label>
					<input class="inputfield textfield" name="password" type="password" />
					<div class="erreg"><?php echo @htmlspecialchars($errors['password']); ?></div>
				</div>
				<div class="field">
					<label for="details" >Повторите пароль</label>
					<input type="password" class="inputfield textfield" name="password2" >
					<div class="erreg"><?php echo @htmlspecialchars($errors['password2']); ?></div>
				</div>
			</div>

			<input class="submitbutton" type="submit" name="sendreg" value="Зарегистрироваться" />

		</form>
	</div>
</div><div class="clear"></div>
<div class="zc"><a href="/"><img src="/img/cross.png" alt="крестик"></a></div>