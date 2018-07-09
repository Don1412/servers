<?php
/**
 * Created by PhpStorm.
 * User: don
 * Date: 08.07.18
 * Time: 16:59
 */

$db_host = "localhost";
$db_username = "test";
$db_password = "test";
$db_name = "test_task";

$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
if($mysqli->connect_errno) {
    printf("Не удалось подключиться %s\n", $mysqli->connect_error);
    exit();
}

if(!empty($_POST)) {

    $http_query = http_build_query($_POST);

    $url = "http://server1/index.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $http_query);
    $returned = json_decode(curl_exec($ch), true);

    curl_close($ch);

    $query = sprintf("INSERT INTO `search` (`%s`, `date`) VALUES ('%s', now())",
        implode("`, `", array_keys($returned)), implode("', '", $returned));
    if(!$mysqli->query($query)) {
        printf("Ошибка базы данных %s", $mysqli->error);
    }
    if(empty($returned['paths'])) {
        echo "Совпадений не найдено";
    }
    else {
        echo $returned['paths'];
    }
}
else if(isset($_GET))
{
    $data = $mysqli->query("SELECT * FROM `search`");

    if($data->num_rows > 0)
    {
        $dataToTable = '';
        while($row = $data->fetch_assoc()) {
            $dataToTable .= '
                <tr>
                  <td>
                    '.$row['id'].'
                  </td>
                  <td>
                    '.$row['patterns'].'
                  </td>
                  <td>
                    '.$row['text'].'
                  </td>
                  <td>
                    '.$row['paths'].'
                  </td>
                  <td>
                    '.$row['date'].'
                  </td>
                </tr>';
        }
        $result = '
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th scope="col">№</th>
              <th scope="col">Расширения</th>
              <th scope="col">Текст</th>
              <th scope="col">Файлы</th>
               <th scope="col">Дата</th>
            </tr>
          </thead>
          <tbody>
            '.$dataToTable .'
          </tbody>
        </table>';
        echo $result;
    }
    else {
        echo "Здесь пока ничего нет";
    }
}