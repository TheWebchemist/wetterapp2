<?php
/**
 * Created by PhpStorm.
 * User: jensgoetze
 * Date: 21.08.17
 * Time: 14:30
 */



$apikey = "2f64cae0b27f72ac6fb12d363a0386c0";
//$apiurl = "http://api.openweathermap.org/data/2.5/forecast?&APPID=";
$apiurl = "http://api.openweathermap.org/data/2.5/weather?&APPID=";


$_GET["city"] = "Berlin";
$_GET["country"] = "de";


//$username = "testnachricht";
//$username2 = "testnachricht2";
//
//$html = file_get_contents("template.html"); // opens template.html
//$html = str_replace("{{username}}", $username, $html); // replaces placeholder with $username
//$html = str_replace("{{username2}}", $username2, $html); // replaces placeholder with $username
//
//echo $html;

if ((isset($_GET["city"]) && $_GET["city"] !== "") && (isset($_GET["country"]) && $_GET["country"] !== ""))  {
	$url = $apiurl . $apikey . "&q=" . $get["city"] . "," . $get["country"] . "&units=metric";
}

