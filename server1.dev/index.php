<?php
/**
 * Created by PhpStorm.
 * User: don
 * Date: 07.07.18
 * Time: 23:09
 */

if(!empty($_GET))
{
    $db_host = "localhost";
    $db_username = "test";
    $db_password = "test";
    $db_name = "test_task";

    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
    if($mysqli->connect_errno) {
        printf("Не удалось подключиться %s\n", $mysqli->connect_error);
        exit();
    }

    $patterns = $_GET['checkboxes'];
    $search_text = $_GET['search'];
    $files = [];

    foreach ($patterns as $pattern) {
        if ($dir = opendir('.')) {
            while (($file = readdir($dir)) !== false) {
                foreach (glob($file . "/*." . $pattern) as $file) {
                    $text = file_get_contents($file);
                    if(strstr($text, $search_text) != false) {
                        $files[] = $file;
                    }
                }
            }
        }
    }
    if(count($files)) {
        $file_patterns = implode("\n", $patterns);
        $paths = implode("\n", $files);

        $query = sprintf("INSERT INTO `search` (`patterns`, `text`, `paths`, `date`) VALUES ('%s', '%s', '%s', now())", $file_patterns, $search_text, $paths);
        if(!$mysqli->query($query)) {
            printf("Ошибка базы данных %s", $mysqli->error);
        }
        echo implode("<br>", $files);
    }
    else echo "Совпадений не найдено";
}