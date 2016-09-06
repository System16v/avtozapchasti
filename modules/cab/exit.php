<?php 
// Выход из авторизации

session_unset();
session_destroy();

setcookie('autoauth[id]',' ',time()-3600,'/');
setcookie('autoauth[hash]',' ',time()-3600,'/');
setcookie('autoauth[ip]',' ',time()-3600,'/');
setcookie('autoauth[user_agent]',' ',time()-3600,'/');
setcookie('autoauth[]',' ',time()-3600,'/');

 header("Location: /");
exit();
?>