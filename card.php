<?php
session_start();
if(isset($_GET['Lat'])){
    $lat = $_GET['Lat'];
    $lon = $_GET['Lon'];
    $adr = $_SESSION['adr'];
}

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Карта</title>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(init);
        var myMap,
            myPlacemark;

        function init(){
            myMap = new ymaps.Map("map", {
                center: [<?= $lat ?>, <?= $lon ?>],
                zoom: 7
            });

            myPlacemark = new ymaps.Placemark([<?= $lat ?>, <?= $lon ?>], {
                hintContent: '<?= $adr; ?>',
                balloonContent: 'Хотите посетить?'
            });

            myMap.geoObjects.add(myPlacemark);
        }
    </script>
</head>
<body>
<div id="map" style="width: 600px; height: 400px"></div>
<a href="yageo.php">Вернуться</a>
</body>
</html>