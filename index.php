<?php
/**
 * Created by PhpStorm.
 * User: jensgoetze
 * Date: 21.08.17
 * Time: 14:30
 */


$apikey = "2f64cae0b27f72ac6fb12d363a0386c0";

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
$coordLongitude       = (string) ( $url["coord"]["lon"] );
$coordLatitude        = (string) ( $url["coord"]["lat"] );
$weatherConditionID   = (string) ( $url["weather"][0]["id"] );
$weatherGroup         = (string) ( $url["weather"][0]["main"] );
$weatherDescription   = (string) ( $url["weather"][0]["description"] );
$weatherIconId        = (string) ( $url["weather"][0]["icon"] );
$mainTemperature      = (string) ( $url["main"]["temp"] );
$mainPressure         = (string) ( $url["main"]["pressure"] );
$mainHumidity         = (string) ( $url["main"]["humidity"] );
$mainTempMin          = (string) ( $url["main"]["temp_min"] );
$mainTempMax          = (string) ( $url["main"]["temp_max"] );
$mainPressureSeaLV    = (string) ( $url["main"]["sea_level"] );
$mainPressureGroundLV = (string) ( $url["main"]["grnd_level"] );
$windSpeed            = (string) ( $url["wind"]["speed"] );
$windDirectionDeg     = (string) ( $url["wind"]["deg"] );
$cloudiness           = (string) ( $url["clouds"]["all"] );
$RainVolume3h         = (string) ( $url["rain"]["3h"] );
$snowVolume3h         = (string) ( $url["snow"]["3h"] );
$timestampx           = (string) ( $url["dt"] );
$sysCountryCode       = (string) ( $url["sys"]["country"] );
$sysSunrise           = (string) ( $url["sys"]["sunrise"] );
$sysSunset            = (string) ( $url["sys"]["sunset"] );
$CityID               = (string) ( $url["id"] );
$CityName             = (string) ( $url["name"] );

/*wind force*/
$windClassification = intval( $windSpeed );
if ( $windClassification >= 32.7 ) {
	$windScale = "Hurricane force";
} else {
	if ( ( $windClassification < 32.7 ) && ( $windClassification >= 28.5 ) ) {
		$windScale = "Violent storm";
	} else {
		if ( ( $windClassification < 28.5 ) && ( $windClassification >= 24.5 ) ) {
			$windScale = "Storm, whole gale";
		} else {
			if ( ( $windClassification < 24.5 ) && ( $windClassification >= 20.8 ) ) {
				$windScale = "Strong, severe gale";
			} else {
				if ( ( $windClassification < 20.8 ) && ( $windClassification >= 17.2 ) ) {
					$windScale = "Gale, fresh gale";
				} else {
					if ( ( $windClassification < 17.2 ) && ( $windClassification >= 13.9 ) ) {
						$windScale = "sHigh wind, moderate gale, near gale";
					} else {
						if ( ( $windClassification < 13.9 ) && ( $windClassification >= 10.8 ) ) {
							$windScale = "Strong breeze";
						} else {
							if ( ( $windClassification < 10.8 ) && ( $windClassification >= 8.0 ) ) {
								$windScale = "Fresh breeze";
							} else {
								if ( ( $windClassification < 8.0 ) && ( $windClassification >= 5.5 ) ) {
									$windScale = "Moderate breeze";
								}
								if ( ( $windClassification < 5.5 ) && ( $windClassification >= 3.4 ) ) {
									$windScale = "Gentle breeze";
								}
								if ( ( $windClassification < 3.4 ) && ( $windClassification >= 1.6 ) ) {
									$windScale = "Light breeze";
								}
								if ( ( $windClassification < 1.6 ) && ( $windClassification >= 0.3 ) ) {
									$windScale = "Light air";
								} else {
									$windScale = "Calm";
								}
							}
						}
					}
				}
			}
		}
	}
}

/*wind direction*/
$windDeg = intval( $windDirectionDeg );
if ( ( $windDeg < 360 ) && ( $windDeg >= 337.5 ) ) {
	$windDirection = "North";
} else {
	if ( ( $windDeg < 337.5 ) && ( $windDeg >= 292.5 ) ) {
		$windDirection = "North West";
	} else {
		if ( ( $windDeg < 292.5 ) && ( $windDeg >= 247.5 ) ) {
			$windDirection = "West";
		} else {
			if ( ( $windDeg < 247.5 ) && ( $windDeg >= 202.5 ) ) {
				$windDirection = "South West";
			} else {
				if ( ( $windDeg < 202.5 ) && ( $windDeg >= 157.5 ) ) {
					$windDirection = "South";
				} else {
					if ( ( $windDeg < 157.5 ) && ( $windDeg >= 112.5 ) ) {
						$windDirection = "South East";
					} else {
						if ( ( $windDeg < 112.5 ) && ( $windDeg >= 67.5 ) ) {
							$windDirection = "East";
						} else {
							if ( ( $windDeg < 67.5 ) && ( $windDeg >= 22.5 ) ) {
								$windDirection = "North East";
							} else {
								if ( ( $windDeg < 22.5 ) && ( $windDeg >= 0 ) ) {
									$windDirection = "North";
								} else {
									$windDirection = "";
								}
							}
						}
					}
				}
			}
		}
	}
}

/*templating*/
$html = file_get_contents( "template.html" );
$html = str_replace( "{{GeoLocationLongitude}}", $coordLongitude, $html );
$html = str_replace( "{{GeoLocationLatitude}}", $coordLatitude, $html );
$html = str_replace( "{{weatherGroup}}", $weatherGroup, $html );
$html = str_replace( "{{weatherDescription}}", $weatherDescription, $html );
$html = str_replace( "{{weatherIconId}}", $weatherIconId, $html );
$html = str_replace( "{{temperature}}", $mainTemperature, $html );
$html = str_replace( "{{pressure}}", $mainPressure, $html );
$html = str_replace( "{{humidity}}", $mainHumidity, $html );
$html = str_replace( "{{tempMin}}", $mainTempMin, $html );
$html = str_replace( "{{tempMax}}", $mainTempMax, $html );
$html = str_replace( "{{pressureSeaLV}}", $mainPressureSeaLV, $html );
$html = str_replace( "{{pressureGroundLV}}", $mainPressureGroundLV, $html );
$html = str_replace( "{{windSpeed}}", $windSpeed, $html );
$html = str_replace( "{{windDirection}}", $windDirection, $html );
$html = str_replace( "{{windScale}}", $windScale, $html );
$html = str_replace( "{{windDirectionDeg}}", $windDirectionDeg, $html );
$html = str_replace( "{{cloudiness}}", $cloudiness, $html );
$html = str_replace( "{{rainVol}}", $RainVolume3h, $html );
$html = str_replace( "{{snowVol}}", $snowVolume3h, $html );
$html = str_replace( "{{timestampx}}", date( 'Y-m-d H:i:s', $timestampx ), $html );
$html = str_replace( "{{countryCode}}", $sysCountryCode, $html );
$html = str_replace( "{{sunrise}}", date( 'H:i', $sysSunrise ), $html );
$html = str_replace( "{{sunset}}", date( 'H:i', $sysSunset ), $html );
$html = str_replace( "{{cityID}}", $CityID, $html );
$html = str_replace( "{{city}}", $CityName, $html );
echo $html;

/*database*/

/*entry check*/
if ( isset( $_POST["city"] ) && isset( $_POST["country_id"] ) ) {

	/*connection check*/
	try {
		$pdoConnect = new PDO( 'mysql:host=localhost;dbname=weatherdata', 'root', 'root' );
	} catch ( Exception $exc ) {
		echo $exc->getTraceAsString();
	}

	/*duplicate check*/
	$pdoQueryTest  = "SELECT timestampx, city, countryCode FROM history WHERE timestampx='$timestampx' AND city='$CityName' AND countryCode= '$sysCountryCode'";
	$pdoResultTest = $pdoConnect->prepare( $pdoQueryTest );
	$pdoResultTest->execute();
	$pdoQueryTestAnzahl = $pdoResultTest->fetchAll();
	if ( !empty( $pdoQueryTestAnzahl ) ) {
		echo( "Sorry Entry is Duplicated" );
	} else {

		/*writing database*/
		$pdoQueryWrite  = "INSERT INTO history (GeoLocationLongitude,GeoLocationLatitude,weatherGroup,weatherDescription,weatherIconId,temperature,pressure,humidity,tempMin,tempMax,pressureSeaLV,pressureGroundLV,windSpeed,windDirection,windScale,windDirectionDeg,cloudiness,rainVol,snowVol,timestampx,countryCode,sunrise,sunset,cityID,city)
		VALUES('$coordLongitude','$coordLatitude','$weatherGroup','$weatherDescription','$weatherIconId','$mainTemperature','$mainPressure','$mainHumidity','$mainTempMin','$mainTempMax','$mainPressureSeaLV','$mainPressureGroundLV','$windSpeed','$windDirection','$windScale','$windDirectionDeg','$cloudiness','$RainVolume3h','$snowVolume3h','$timestampx','$sysCountryCode','$sysSunrise','$sysSunset','$CityID','$CityName')";
		$pdoResultWrite = $pdoConnect->prepare( $pdoQueryWrite );
		$pdoResultWrite->execute();
		if ( $pdoResultWrite ) {
			echo 'Data Insert';
		} else {
			echo 'Data Not Insert';
		}
	}


	$pdoQueryHistory = "SELECT * FROM history WHERE city='$CityName' AND countryCode= '$sysCountryCode'";
	$pdoResultHistory = $pdoConnect->prepare( $pdoQueryHistory );
	$pdoResultHistory->execute();
	foreach ($pdoResultHistory as$row){
		var_dump($row);
	}


//	$pdoResultHistoryAnzahl = $pdoResultHistory->fetchAll();
//	print_r($pdoResultHistoryAnzahl);



}