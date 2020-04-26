<?php

$datetime = date("Y-m-d H:i:s");
echo "Server time zone: " . $datetime;
$utc = new DateTime($datetime, new DateTimeZone('UTC'));
$utc->setTimezone(new DateTimeZone('America/Sao_Paulo'));
echo "<br>America Brazil: " . $utc->format('Y-m-d H:i:s');

$utc->setTimezone(new DateTimeZone('Europe/Copenhagen'));
echo "<br>Copenhagen: " . $utc->format('Y-m-d H:i:s');
echo "<br>Copenhagen Time: " . $utc->format('H:i:s');
echo "<br>Copenhagen Date: " . $utc->format('Y-m-d');
echo "<br>Copenhagen Date: " . $utc->format('D');

?>
