<?php
/**
 * Created by PhpStorm.
 * User: don
 * Date: 08.07.18
 * Time: 16:59
 */

if(!empty($_GET)) {

    $http_query = http_build_query($_GET);

    $url = "http://server1/index.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $http_query);
    $returned = json_decode(curl_exec($ch), true);
    curl_close($ch);

    $db_host = "localhost";
    $db_username = "test";
    $db_password = "test";
    $db_name = "test_task";

    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
    if($mysqli->connect_errno) {
        printf("Не удалось подключиться %s\n", $mysqli->connect_error);
        exit();
    }

    $file_patterns = $returned['patterns'];
    $search_text = $returned['text'];
    $paths = $returned['paths'];

    $query = sprintf("INSERT INTO `search` (`patterns`, `text`, `paths`, `date`) VALUES ('%s', '%s', '%s', now())", $file_patterns, $search_text, $paths);
    if(!$mysqli->query($query)) {
        printf("Ошибка базы данных %s", $mysqli->error);
    }
    echo $paths;
}