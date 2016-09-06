  <!--  Если не существует кук и сессий то выводим форму для
		авторизации -->
  <?php 
    if(!isset($_SESSION['user']) && !isset($_COOKIE['autoauth']['id'])){ 

  ?>
<!-- Чтобы форма не скрывалась при неправильном вводе логина\пароля делаем блок if, что если вводили данные, то block -->
<div id="at" <?php if(isset($_POST['login']) && (isset($_GET['page']) && $_GET['page']!='rpass')){echo 'style="display:block;"';}else{ echo 'style="display:none;"';}?>>
  <form method="post" action="" class="login">
    <p>
      <span class="label">Логин:</span>
      <input type="text" class="txt" name="login" id="login" value="<?php if(isset($_POST['login']))echo $_POST['login'];?>">
    </p>

    <p>
      <span class="label">Пароль:</span>
      <input type="password" class="pswd" name="pass" id="password" value="">
    </p>

    <p class="login-submit">
      <button type="submit" class="login-button">Login</button>
    </p>
    <div class="er"><?php  echo @$error; ?></div>
    <p class="forgot-password"><a href="/cab/rpass" onclick="rpass(); return false;">Забыли пароль?</a></p>
    <div class="tch">Запомнить:<input class="check" type="checkbox" name="autoauth"></div>
  </form>
</div>
  <?php } ?>

  <div id="rpass" <?php if(isset($_GET['page']) && $_GET['page'] == 'rpass'){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"';}?>>
    <form method="post" action="/cab/rpass" class="login">
      <p>
        <span class="lge">Введите Ваш логин и E-mail</span><br><br>
        <span class="label">Логин:</span>
        <input type="text" class="txt" name="login" id="login" value="<?php if(isset($_POST['login']))echo $_POST['login'];?>">
      </p>

      <p>
        <span class="label">E-mail:</span>
        <input type="text" class="pswd" name="email" id="password" value="<?php if(isset($_POST['email']))echo $_POST['email'];?>">
      </p>

      <p class="login-submit1">
        <button type="submit" class="login-button1">Login</button>
      </p>
      <div class="er"><?php  echo @$error; ?></div>
    </form>
    <div class="zkr">
      <a href=""><img src="/img/cross.png" alt="закрыть"></a>
    </div>
  </div>
