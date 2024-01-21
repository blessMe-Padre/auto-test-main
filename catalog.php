<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?
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
    ?>

    <form class="pt-10 pb-10 grid grid-cols-1 md:grid-cols-4 gap-4 uppercase" action="/" method="get">
        <div class="mb-4">
            <?php
            $arr = aj_get("select marka_name from main group by marka_id order by marka_name ASC");
            $json = json_encode($arr);

            echo '<label class="block text-white text-sm font-medium mb-2" for="marka_name">Выберите марку</label>';
            echo '<select name="marka_name" class="block appearance-none w-full border  hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="markaAuc">';

            echo '<option value="any">Любая</option>';
            foreach ($arr as $v) {
                echo '<option value="' . $v['MARKA_NAME'] . '">' . $v['MARKA_NAME'] . "</option>";
            }

            echo '</select>';
            ?>
        </div>

        <div class="mb-4">
            <?php
            echo '<label class="block text-white text-sm font-medium mb-2" for="model_name">Выберите модель</label>';
            echo '<select name="model_name" class="block appearance-none w-full border  hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="modelAuc">';
            echo '<option value="">Любая</option>';
            echo '</select>';
            ?>
        </div>

        <div class="mb-4">
            <label class="block text-white text-sm font-medium mb-2" for="make">
                Выберите кузов
            </label>
            <select id="kuzovAuction" name="make"
                class="select input block appearance-none w-full  border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                <option value='any'>Любой</option>
            </select>
        </div>

    </form>

    <p id="responseP">
        <?php
        $arr = aj_get("select id, model_id, model_name, color, mileage, eng_v, kpp, avg_price, year, images from main group by model_id order by model_name limit 1");
        ?>
    <pre>
        <?php
        $arr2 = aj_get("select * from main where id='4PStCyfKl5WVdu7'");
        print_r($arr2);
        ?>
        </pre>
    </p>

    <script src="main.js"></script>
</body>

</html>