<?php

function aj_get($sql)
{
    error_reporting(0);

    ##----- CONFIG ---------
    $code = 'APTnghDfD64KJ';       ## REQUIRED
    $server = '78.46.90.228'; ## optional :: $server='144.76.203.145';
    $go = 'api';              ## optional :: $go='gzip'; // gzip work faster

    ## SET IP,URL
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] == '' ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_CF_CONNECTING_IP'];
    $url = 'http://' . $server . '/' . $go . '/?ip=' . $ip . '&json&code=' . $code . '&sql=' . urlencode(preg_replace("/%25/", "%", $sql));

    ## DEBUG
    // echo "<hr><a style='font-size:12px' href='$url'>".$url."</a><hr>";

    ## API REQUEST
    $s = file_get_contents($url);
    //echo $s;

    ## GZIP DECODE
    if ($go == 'gzip') {
        $s = $server == '144.76.203.145' ? gzinflate(substr($s, 10, -8)) :
            gzuncompress(preg_replace("/^\\x1f\\x8b\\x08\\x00\\x00\\x00\\x00\\x00/", "", $s));
    }

    $arr = json_decode($s, true);  //die(var_export($arr));
    // echo gettype($arr);
    // print_r($arr);
    return $arr;
}

// Проверяем, что метод запроса - POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем содержимое тела запроса
    $json = file_get_contents('php://input');

    // Декодируем JSON в ассоциативный массив
    $data = json_decode($json, true);

    // Проверяем, существует ли ключ 'marka' в массиве
    if (isset($data['marka'])) {
        // Получаем значение, переданное из JavaScript
        $selectedValue = $data['marka'];

        $arr_model = aj_get("select model_name from main where marka_name='" . $selectedValue . "' group by model_name order by model_name");
        // Отправляем ответ обратно в JavaScript
        echo json_encode(["status" => "success", "message" => "Значение получено", "data" =>  $arr_model]);
    } else {
        // Отправляем ошибку, если ключ 'marka' не найден
        echo json_encode(["status" => "error", "message" => "ключ 'marka' не найден"]);
    }
} else {
    // Отправляем ошибку, если метод запроса не POST
    echo json_encode(["status" => "error", "message" => "Неверный метод запроса"]);
}
?>