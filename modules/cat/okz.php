<?php
Core::$CSS[] = '<link type="text/css" rel="stylesheet" href="/css/cat.css">';

if(isset($_SESSION['zakaz'])) {
    $r = q("
    SELECT MAX(`id`)
    FROM `zakazi`
    ");

    $rz = $r->fetch_assoc();

}