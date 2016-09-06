<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 15.08.2016
 * Time: 12:32
 */

if(isset($_GET['page']) && $_GET['page'] == 'menu'){
    header("Location: /404");
    exit();
}
$menu = q("
        SELECT *
        FROM `categorii`
        ORDER BY `name` ASC 
        ");
?>