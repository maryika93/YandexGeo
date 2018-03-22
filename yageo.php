<?php
session_start();
require __DIR__.'/vendor/autoload.php';

$api = new \Yandex\Geo\Api();

if(isset($_POST['find'])){
    $_SESSION['adr'] = $_POST['adress'];
$api->setQuery($_SESSION['adr']);
}

?>

<h1> Найдите координаты адреса </h1>
<form method="post" action="" enctype="multipart/form-data">
    <label>Ведите адрес:</label>
    <input type="text" placeholder="Адрес" name="adress">
    <input type="submit" name="find" value="Найти"><br/><br/>
</form>
<table border="1", cellpadding="10", width="100%">
    <tr>
        <td align="center"> Адрес </td>
        <td align="center"> Широта </td>
        <td align="center"> Долгота </td>
    </tr>
    <?php
    $api
        ->setLimit(1000)
        ->setLang(\Yandex\Geo\Api::LANG_US)
        ->load();
    $response = $api->getResponse();
    $response->getFoundCount();
    $response->getQuery();
    $response->getLatitude();
    $response->getLongitude();
    $collection = $response->getList();
    foreach ($collection as $item) {
        ?>
        <tr>
            <?php if ($response->getFoundCount()>1){
                ?>
            <td align="center"><?php echo '<a href="card.php?Lat='. $item->getLatitude().'&Lon='.$item->getLongitude().'">'.$item->getAddress().'</a><br/>'?></td>
            <?php
            } else{
                $_SESSION['lat'] = $item->getLatitude();
                $_SESSION['lon'] = $item->getLongitude();
                ?>
            <td align="center"><?php echo $item->getAddress() ?></td>

            <?php
            } ?>
            <td align="center"><?php echo $item->getLatitude() ?></td>
            <td align="center"><?php echo $item->getLongitude() ?></td>
        </tr>
        <?php
    }
    ?>
</table>

<?php if ($response->getFoundCount() == 1){
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(init);
        var myMap,
            myPlacemark;

        function init(){
            myMap = new ymaps.Map("map", {
                center: [<?= $_SESSION['lat'] ?>, <?= $_SESSION['lon'] ?>],
                zoom: 7
            });

            myPlacemark = new ymaps.Placemark([<?= $_SESSION['lat'] ?>, <?= $_SESSION['lon'] ?>], {
                hintContent: '<?= $_SESSION['adr']; ?>',
                balloonContent: 'Хотите посетить?'
            });
            myMap.geoObjects.add(myPlacemark);
        }
    </script>
</head>
<body>
<div id="map" style="width: 600px; height: 400px"></div>
</body>
</html>
    <?php
} ?>