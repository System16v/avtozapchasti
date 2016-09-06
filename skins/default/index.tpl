<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<!--свойства страницы-->
<head> 
<!--кодировка для русского языка-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="ru">
<!--название указывается в закладке-->
<title><?php echo hc(Core::$META['title']); ?> </title> 
<!--указывается описание в поисковике о чем сайт-->
<meta name="description" content="<?php echo hc(Core::$META['description']); ?>">  
<meta name="keywords" content="<?php echo hc(Core::$META['keywords']); ?>">
<?php Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/css/style-default.css">'; ?>
<?php Core::$CSS[] = '  <link rel="stylesheet" href="/skins/default/css/authf.css">'; ?>
<?php if(count(Core::$CSS)) {echo implode("\n",Core::$CSS);} ?>
<?php if(count(Core::$JS)) {echo implode("\n",Core::$JS);} ?>
<link type="text/css" rel="stylesheet" href="/skins/default/css/auth.css">
<link type="text/css" rel="stylesheet" href="/css/table.css">
<link type="text/css" rel="stylesheet" href="/css/paginator.css">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<script src="/js/jquery-3.1.0.js"></script>
<script src="/js/jquery.mousewheel-3.0.6.pack.js"></script>

<script src="/js/jquery.fancybox.pack.js"></script>
<script src="/js/jquery.fancybox.js"></script>
<link rel="stylesheet" href="/css/jquery.fancybox.css">
<link rel="stylesheet" href="/css/style-nav.css">
<link rel="stylesheet" href="/css/style-menu.css">
<script type="text/javascript" src="/skins/default/js/scripts_v1.js"></script>

</head>
<!--  Показывает кто авторизован на сайте -->
<body>

    <header>
	  <div id="nav">
		  <nav> <!-- навигация -->
              <section class="container">
                  <nav>
                      <ul class="nav">
                          <li><a href="/" class="nav-icon" title="Главная"><span class="icon-home">Главная</span></a></li>
                          <li <?php if(isset($_GET['page']) && $_GET['page'] == 'info')echo 'class="active"';?>><a href="/static/info" title="Информация">Информация</a></li>
                          <li <?php if(isset($staticpage['module']) && $staticpage['module'] == 'pay')echo 'class="active"';?>><a href="/pay" title="Оплата и доставка">Оплата и доставка</a></li>
                          <li <?php if(isset($staticpage['module']) && $staticpage['module'] == 'order')echo 'class="active"';?>><a href="/order" title="Как заказать">Как оформить заказ</a></li>
                          <li <?php if(isset($staticpage['module']) && $staticpage['module'] == 'contacts')echo 'class="active"';?>><a href="/contacts" title="Контакты">Контакты</a></li>
                      </ul>
                  </nav>
              </section>
		  </nav>
	  </div>

      <div class="logo"><img src="/img/logo.png" alt="Логотип"></div>
      <div class="auth">
        <?php
        if(!isset($_SESSION['user']) && !isset($_COOKIE['autoauth'])){
            echo '<a href="" onclick="auth(); return false;">Логин</a>'.' '.'<a href="/cab/registration">Регистрация</a>';
        }else{
            echo '<div class="prt">Здравствуйте <a href="/cab/lc">'.$_SESSION['user']['login'].'</a></div>';
            echo '<div class="ext"><a href="/cab/exit">Выход</a></div>';
            if($_SESSION['user']['access'] == 5){ ?>
                <span class="user"><a href="/cab/user">Пользователи</a></span>
                <span class="allz"><a href="/cab/allz">Все заказы</a></span>
<?php       }
        }
        ?>
      </div>
      <div class="logo-t">Автозапчасти ВАЗ ОКА<br><div class="t">иномарки под заказ</div></div>

      <div id="korzina">
          <span id="k">
              <?php
                if(!isset($_COOKIE['addt'])) {
                    echo 'Ваша корзина пуста';
                } else{
                    if($_COOKIE['klt'] == 1){
                        echo 'В корзине<br>'.$_COOKIE['klt'].' товар';
                    }else if($_COOKIE['klt'] == 2 || $_COOKIE['klt'] == 3 || $_COOKIE['klt'] == 4){
                        echo 'В корзине<br>'.$_COOKIE['klt'].' товара';
                    }else{
                        echo 'В корзине<br>'.$_COOKIE['klt'].' товаров';
                     }
                  };
              ?>
          </span>
          <?php
            if(!isset($_COOKIE['addt'])){ // Если у нас не существует кук рисуем пустую корзину, иначе полную
              echo '<a id="kimg" href="/cat"><img src="/img/cat1.png" alt="cat" title="Посмотреть товары в корзине"></a>';
            }else{
                echo '<a id="kimg" href="/cat"><img src="/img/catt.png" alt="cat" title="Посмотреть товары в корзине"></a>';
            };
          ?>
      </div>

    </header>
    <div id="menu">
        <?php include './modules/static/menu.php';
        include './skins/default/static/menu.tpl';
        ?>
    </div>
    <div id="content">

        <?php
        if(isset($_SESSION['regok'])){ // Если существует сессия регистрации - значит регистрировались
            unset($_SESSION['regok']); // Удаляем сессию чтобы потом заново вывести форму
            ?>
            <div class="sreg">
                <h1>Поздравляем, Вы успешно зарегистрировались на сайте!</h1>
            </div>
            <?php
        }
        if(isset($_SESSION['rp'])){ // Если существует сессия регистрации - значит регистрировались
            unset($_SESSION['rp']); // Удаляем сессию чтобы потом заново вывести форму
            ?>
            <div class="srpswd">
                <h3>На указанную Вами почту, было отправлено письмо с ссылкой для изменение пароля.</h3>
            </div>
            <?php
        }
        if(isset($_SESSION['npass'])){ // Если существует сессия регистрации - значит регистрировались
            unset($_SESSION['npass']); // Удаляем сессию чтобы потом заново вывести форму
            ?>
            <div class="sokrpswd">
                <h2>Ваш пароль успешно изменен!</h2>
            </div>
            <?php
        }
        ?>
      <?php
		echo $content;// Выводим инклуды на экран (если будут ошибки, они будут в BODY,а не в html)
	  ?>
  </div>

  <footer class="ft">
	  <?php
	  	if(date("Y")> Core::$CREATED) {
			echo '&copy ' . Core::$CREATED . ' - ' . date("Y").'г.  Автозапчасти Новошахтинск';
		}else{
			echo '&copy '.Core::$CREATED.'г. Автозапчасти Новошахтинск';
		}
	  ?>
  </footer>
  <!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'pYOIvMp6dK';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->
</body>
</html>