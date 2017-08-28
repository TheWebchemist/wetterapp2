<?php

/*Projekt creation*/
//http://localhost:8888/Wetterapp2/initialDatabase.php

$host          = "localhost";
$root          = "root";
$root_password = "root";
$databasename  = "weatherdata";
$tablename     = "history";
$tablename2    = "tcron";


$dbh = new PDO( "mysql:host=$host", $root, $root_password );
$dbh->exec( "CREATE DATABASE $databasename;" );

$dbha = new PDO( "mysql:host=$host;dbname=$databasename", $root, $root_password );


$dbha->exec( "CREATE TABLE $tablename (
`id` int(11) NOT NULL AUTO_INCREMENT,
 `GeoLocationLongitude` varchar(50) NOT NULL,
 `GeoLocationLatitude` varchar(50) NOT NULL,
 `weatherGroup` varchar(50) NOT NULL,
 `weatherDescription` varchar(50) NOT NULL,
 `weatherIconId` varchar(50) NOT NULL,
 `temperature` varchar(50) NOT NULL,
 `pressure` varchar(50) NOT NULL,
 `humidity` varchar(50) NOT NULL,
 `tempMin` varchar(50) NOT NULL,
 `tempMax` varchar(50) NOT NULL,
 `pressureSeaLV` varchar(50) NOT NULL,
 `pressureGroundLV` varchar(50) NOT NULL,
 `windSpeed` varchar(50) NOT NULL,
 `windDirection` varchar(50) NOT NULL,
 `windScale` varchar(50) NOT NULL,
 `windDirectionDeg` varchar(50) NOT NULL,
 `cloudiness` varchar(50) NOT NULL,
 `rainVol` varchar(50) NOT NULL,
 `snowVol` varchar(50) NOT NULL,
 `timestampx` varchar(50) NOT NULL,
 `countryCode` varchar(50) NOT NULL,
 `sunrise` varchar(50) NOT NULL,
 `sunset` varchar(50) NOT NULL,
 `cityID` varchar(50) NOT NULL,
 `city` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
)" );


$dbha->exec( "CREATE TABLE $tablename2 (
`index` int( 11 ) NOT NULL AUTO_INCREMENT,
 `timestamp` int( 11 ) NOT NULL,
 PRIMARY KEY( `index` )
)");
	

/*
 * MAMP cronjop instruction
go to mampfolder and open a terminal
wirte "crontab - e"
insert edit mode with "i"
insert: " * 5 * * * * /usr / bin / curl --silent --compressed wget http://localhost:8888/Wetterapp2/index.php?sync=all"
press "esc"
write "ZZ"
check with "crontab -l"
*/
