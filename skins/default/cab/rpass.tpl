<?php if(isset($_GET['key1'],$_GET['key2'])){?>
<div id="<?php if(!empty($errors)){echo 'rpass2';}else{ echo 'rpass';} ?>">
    <form method="post" action="/cab/rpass/<?php echo $_GET['key1']; ?>/<?php echo $_GET['key2'];?>" class="login" <?php if(!empty($errors)){ echo 'style="display: block;"';}?>>
        <p>
            <span class="lge">Введите новый пароль:</span><br><br>
            <span class="label1">Пароль:</span>
            <input type="password" class="txt" name="npass" id="login" value="<?php if(isset($_POST['login']))echo $_POST['login'];?>">
        </p>

        <p>
            <span class="label1">Повторите:</span>
            <input type="password" class="pswd" name="n2pass" id="password" value="">
        </p>

        <p class="login-submit1">
            <button type="submit" class="login-button1">Login</button>
        </p>
        <div class="er"><?php  echo @$error; ?></div>
    </form>
</div>
<?php }?>
<div class="errors"><?php
            echo @$errors['email'].'<br>'.@$errors['pass'].'<br>'.@$errors['login']; ?>
</div>