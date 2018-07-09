<?php
/**
 * Created by PhpStorm.
 * User: don
 * Date: 07.07.18
 * Time: 23:09
 */

if(!empty($_POST))
{
    function listDirectories($dir, $directories) {
        if(is_dir($dir)) {
            if($dh = opendir($dir)){
                while($directory = readdir($dh)){
                    if($directory != '.' && $directory != '..'){
                        if(is_dir($dir . '/' . $directory)){
                            $directories[] = $dir . '/' . $directory;
                            $directories = listDirectories($dir . '/' . $directory, $directories);
                        }
                    }
                }
            }
            closedir($dh);
        }
        return $directories;
    }

    $patterns = $_POST['checkboxes'];
    $search_text = $_POST['search'];
    $directories = [];
    $find_files = [];

    $directories = listDirectories('.', $directories);

    foreach ($patterns as $pattern) {
        foreach ($directories as $directory) {
            foreach (glob($directory . "/*." . $pattern) as $file) {
                $text = file_get_contents($file);
                if(strstr($text, $search_text) != false) {
                    $find_files[] = $file;
                }
            }
        }
    }
    $file_patterns = implode("\n", $patterns);
    $paths = implode("\n", $find_files);
    $result = array(
        'patterns' => $file_patterns,
        'text' => $search_text,
        'paths' => $paths
    );
    echo json_encode($result);
}