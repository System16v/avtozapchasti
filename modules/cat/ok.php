<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 19.08.2016
 * Time: 12:10
 */
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/css/cat.css">';

if(isset($_SESSION['zakaz'])) {
   $r = q("
    SELECT MAX(`id`)
    FROM `zakazi`
    ");

    $rz = $r->fetch_assoc();

}