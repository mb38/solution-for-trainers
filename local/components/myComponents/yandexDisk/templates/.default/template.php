<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$disk = new Arhitector\Yandex\Disk('AgAAAAAXUfDsAAa7e-hRs4jMJUPbtBZoz0AQSBQ');

?>


<hr/>
<!--Просмотр файлов на яндекс диске-->
<h2>Файлы на диске</h2>
<?php
$token = 'AgAAAAAXUfDsAAa7e-hRs4jMJUPbtBZoz0AQSBQ';

$path = '/';

$fields = '_embedded.items.name,_embedded.items.type';

$limit = 100;

$ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources?path=' . urlencode($path) . '&fields=' . $fields . '&limit=' . $limit);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$res = curl_exec($ch);
curl_close($ch);

$res = json_decode($res, true);
var_dump($res);
?>

<hr/>
<!-------------------------------------------------->

<!--Загрузка файла на яндекс диск-->
<h2>Загрузка файла</h2>
<?php
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Загрузить') {
    if ($_FILES && $_FILES['uploadFile']['error'] == UPLOAD_ERR_OK) {
        $token = 'AgAAAAAXUfDsAAa7e-hRs4jMJUPbtBZoz0AQSBQ';

        $file = __DIR__ . '/download/' . $_FILES['uploadFile']['name'];
        $path = '/';


        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/upload?path=' . urlencode($path . basename($file)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res, true);
        if (empty($res['error'])) {
            $fp = fopen($file, 'r');

            $ch = curl_init($res['href']);
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_UPLOAD, true);
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
            curl_setopt($ch, CURLOPT_INFILE, $fp);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 201) {
                echo 'Файл успешно загружен.';
            }
        }
    }
}
?>
<form method="post" enctype='multipart/form-data'>
    Выберите файл: <input type='file' name='uploadFile' size='10'/><br/><br/>
    <input type='submit' name="uploadBtn" value='Загрузить'/>
</form>
<hr/>
<!-------------------------------------------------->

<!--Скачать файл с яндекс диска-->
<h2>Скачивание файла</h2>
<?php
if (isset($_POST['downloadBtn']) && $_POST['downloadBtn'] == 'Скачать') {
    $token = 'AgAAAAAXUfDsAAa7e-hRs4jMJUPbtBZoz0AQSBQ';

    $yd_file = $_POST['downloadFile'];

    $path = __DIR__. '/download/';

    $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/download?path=' . urlencode($yd_file));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);

    $res = json_decode($res, true);
    if (empty($res['error'])) {
        $file_name = $path . '/' . basename($yd_file);
        $file = @fopen($file_name, 'w');

        $ch = curl_init($res['href']);
        curl_setopt($ch, CURLOPT_FILE, $file);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        curl_close($ch);
        fclose($file);
    }
}
?>
<form method="post">
    Название файла: <input type='text' name='downloadFile'/><br/><br/>
    <input type='submit' name="downloadBtn" value='Скачать'/>
</form>
<hr/>
<!-------------------------------------------------->

<!--Удалить файл с яндекс диска-->
<h2>Удаление файла</h2>
<?php
if (isset($_POST['deleteBtn']) && $_POST['deleteBtn'] == 'Удалить') {
    $token = 'AgAAAAAXUfDsAAa7e-hRs4jMJUPbtBZoz0AQSBQ';

    $path = '/'. $_POST['deleteFile'];

    $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources?path=' . urlencode($path) . '&permanently=true');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (in_array($http_code, array(202, 204))) {
        echo 'Успешно удалено';
    }
}
?>
<form method="post">
    Название файла: <input type='text' name='deleteFile'/><br/><br/>
    <input type='submit' name="deleteBtn" value='Удалить'/>
</form>
<hr/>
<!-------------------------------------------------->
