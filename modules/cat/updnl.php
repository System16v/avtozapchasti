<?php
/**
 * Created by PhpStorm.
 * User: System16v
 * Date: 23.08.2016
 * Time: 22:13
 */
    $link = mysqli_connect('localhost', 'v95152te_14444', 'Vaz_21124', 'v95152te_14444');

    mysqli_query($link, "
    UPDATE `tovari`
    SET `nalichie` = '" . mysqli_escape_string($link, $_POST['upd']) . "'
    WHERE `id` = '" . (int)$_POST['id'] . "'
") or exit(mysqli_error($link));
