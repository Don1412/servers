<?php
/**
 * Created by PhpStorm.
 * User: don
 * Date: 07.07.18
 * Time: 23:09
 */

if(!empty($_POST))
{

    $patterns = $_POST['checkboxes'];
    $search_text = $_POST['search'];
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
    $file_patterns = implode("\n", $patterns);
    $paths = implode("\n", $files);
    $result = array(
        'patterns' => $file_patterns,
        'text' => $search_text,
        'paths' => $paths
    );
    echo json_encode($result);
}