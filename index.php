<?php

require_once "functions.php";

/**
 * Created by PhpStorm.
 * User: jensgoetze
 * Date: 21.08.17
 * Time: 14:30
 */


$apikey = "2f64cae0b27f72ac6fb12d363a0386c0";
$apiurl = "http://api.openweathermap.org/data/2.5/weather?&APPID=";


/*default fallback*/
$city = $_POST["city"];
if ( $city == "" ) {
	$city = "Berlin";
}
$country = $_POST["country_id"];
if ( $country == "" ) {
	$country = "de";
}

/*api querry*/
if ( ( isset( $city ) && $city !== "" ) && ( isset( $country ) && $country !== "" ) ) {
	$url = $apiurl . $apikey . "&q=" . $city . "," . $country . "&units=metric";
	$url = file_get_contents( $url );
	$url = json_decode( $url, TRUE );
}

/*json data extraction*/
$data = zuordnung( $url );


/*templating*/
$html = file_get_contents( "template.html" );
$html = str_replace( "{{GeoLocationLongitude}}", $data["coordLongitude"], $html );
$html = str_replace( "{{GeoLocationLatitude}}", $data["coordLatitude"], $html );
$html = str_replace( "{{weatherGroup}}", $data["weatherGroup"], $html );
$html = str_replace( "{{weatherDescription}}", $data["weatherDescription"], $html );
$html = str_replace( "{{weatherIconId}}", $data["weatherIconId"], $html );
$html = str_replace( "{{temperature}}", $data["mainTemperature"], $html );
$html = str_replace( "{{pressure}}", $data["mainPressure"], $html );
$html = str_replace( "{{humidity}}", $data["mainHumidity"], $html );
$html = str_replace( "{{tempMin}}", $data["mainTempMin"], $html );
$html = str_replace( "{{tempMax}}", $data["mainTempMax"], $html );
$html = str_replace( "{{pressureSeaLV}}", $data["mainPressureSeaLV"], $html );
$html = str_replace( "{{pressureGroundLV}}", $data["mainPressureGroundLV"], $html );
$html = str_replace( "{{windSpeed}}", $data["windSpeed"], $html );
$html = str_replace( "{{windDirection}}", $data["windDirection"], $html );
$html = str_replace( "{{windScale}}", $data["windScale"], $html );
$html = str_replace( "{{windDirectionDeg}}", $data["windDirectionDeg"], $html );
$html = str_replace( "{{cloudiness}}", $data["cloudiness"], $html );
$html = str_replace( "{{rainVol}}", $data["RainVolume3h"], $html );
$html = str_replace( "{{snowVol}}", $data["snowVolume3h"], $html );
$html = str_replace( "{{timestampx}}", date( 'Y-m-d H:i:s', $data["timestampx"] ), $html );
$html = str_replace( "{{countryCode}}", $data["sysCountryCode"], $html );
$html = str_replace( "{{sunrise}}", date( 'H:i', $data["sysSunrise"] ), $html );
$html = str_replace( "{{sunset}}", date( 'H:i', $data["sysSunset"] ), $html );
$html = str_replace( "{{cityID}}", $data["CityID"], $html );
$html = str_replace( "{{city}}", $data["CityName"], $html );
echo $html;

/*database*/

/*connection check*/
try {
	$pdoConnect = new PDO( 'mysql:host=localhost;dbname=weatherdata', 'root', 'root' );
} catch ( Exception $exc ) {
	echo $exc->getTraceAsString();
}

/*entry check*/
if ( isset( $_POST["city"] ) && isset( $_POST["country_id"] ) ) {

	if ( !checkDuplicatet( $data, $pdoConnect ) ) {
//		echo "<h5 class='container'><br />Data for " . $data['CityName'] . ", " . $data['sysCountryCode'] . " could not be updated</h5>";

	} else {

		/*writing database*/
		$pdoQueryWrite  = insertInto( $data );
		$pdoResultWrite = $pdoConnect->prepare( $pdoQueryWrite );
		$pdoResultWrite->execute();
//		if ( $pdoResultWrite ) {
//			echo "<h5 class='container'><br />Data for " . $data['CityName'] . ", " . $data['sysCountryCode'] . "  was updated</h5>";
//		} else {
//			echo "<h5 class='container'><br />Data for " . $data['CityName'] . ", " . $data['sysCountryCode'] . " could not be updated</h5>";
//		}
	}

	/*showing all saved results*/
	$pdoQueryHistory  = "SELECT * FROM history WHERE city ='" . $data["CityName"] . "' AND countryCode = '" . $data["sysCountryCode"] . "' ORDER BY timestampx DESC ";
	$pdoResultHistory = $pdoConnect->prepare( $pdoQueryHistory );
	$pdoResultHistory->execute();
	$pdoResultHistoryAnzahl = $pdoResultHistory->fetchAll( PDO::FETCH_ASSOC );


	for ( $i = 0; $i < count( $pdoResultHistoryAnzahl ); $i ++ ) {
		$pdoResultHistoryAnzahl[$i]["timestampx"] = date( 'Y-m-d H:i:s', $pdoResultHistoryAnzahl[$i]["timestampx"] );
		$pdoResultHistoryAnzahl[$i]["sunrise"]    = date( 'H:i', $pdoResultHistoryAnzahl[$i]["sunrise"] );
		$pdoResultHistoryAnzahl[$i]["sunset"]     = date( 'H:i', $pdoResultHistoryAnzahl[$i]["sunset"] );
	};


	echo "<script type='text/javascript'>var weatherData = " . json_encode( $pdoResultHistoryAnzahl ) . "</script>";

}


/*autosave all citys*/

if ( isset( $_GET["sync"] ) && $_GET["sync"] === "all" ) {
	$time  = time();
	$query = "INSERT INTO tCron (timestamp) VALUES ('$time')";
	$stmt  = $pdoConnect->prepare( $query );
	$stmt->execute();
}

echo "<h2 class='container'>  This Citys have autohistory: </h2>";
$pdoQueryGetCitys  = "SELECT DISTINCT city,countryCode FROM history	 GROUP BY city ORDER BY city ASC ";
$pdoResultGetCitys = $pdoConnect->prepare( $pdoQueryGetCitys );
$pdoResultGetCitys->execute();
$pdoResultGetCitysAnzahl = $pdoResultGetCitys->fetchAll( PDO::FETCH_ASSOC );


for ( $i = 0; $i < count( $pdoResultGetCitysAnzahl ); $i ++ ) {
	$city    = ( $pdoResultGetCitysAnzahl[$i]["city"] );
	$country = ( $pdoResultGetCitysAnzahl[$i]["countryCode"] );
	if ( $city !== "" ) {

		$url = $apiurl . $apikey . "&q=" . $city . "," . $country . "&units=metric";
		$url = file_get_contents( $url );
		$url = json_decode( $url, TRUE );


		$data = zuordnung( $url );

		if ( !checkDuplicatet( $data, $pdoConnect ) ) {
			echo "<h5 class='container'><br />Data for <strong>" . $data['CityName'] . ", " . $data['sysCountryCode'] . "</strong> could not be updated , timestamp already exists</h5>";

		} else {

			/*writing database*/
			$pdoQueryWrite  = insertInto( $data );
			$pdoResultWrite = $pdoConnect->prepare( $pdoQueryWrite );
			$pdoResultWrite->execute();

			if ( $pdoResultWrite ) {
				echo "<h5 class='container'><br />Data for <strong>" . $data['CityName'] . ", " . $data['sysCountryCode'] . "</strong>  was updated</h5>";
			} else {
				echo "<h5 class='container'><br />Data for <strong>" . $data['CityName'] . ", " . $data['sysCountryCode'] . "</strong> could not be updated, timestamp already exists</h5>";
			}
		}
	}
};
