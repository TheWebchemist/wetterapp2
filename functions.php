<?php
/**
 * Created by PhpStorm.
 * User: jensgoetze
 * Date: 27.08.17
 * Time: 20:39
 */

function zuordnung( $val ) {
	/*json data extraction*/
	$item["coordLongitude"]       = (string) ( $val["coord"]["lon"] );
	$item["coordLatitude"]        = (string) ( $val["coord"]["lat"] );
	$item["weatherConditionID"]   = (string) ( $val["weather"][0]["id"] );
	$item["weatherGroup"]         = (string) ( $val["weather"][0]["main"] );
	$item["weatherDescription"]   = (string) ( $val["weather"][0]["description"] );
	$item["weatherIconId"]        = (string) ( $val["weather"][0]["icon"] );
	$item["mainTemperature"]      = (string) ( $val["main"]["temp"] );
	$item["mainPressure"]         = (string) ( $val["main"]["pressure"] );
	$item["mainHumidity"]         = (string) ( $val["main"]["humidity"] );
	$item["mainTempMin"]          = (string) ( $val["main"]["temp_min"] );
	$item["mainTempMax"]          = (string) ( $val["main"]["temp_max"] );
	$item["mainPressureSeaLV"]    = (string) ( $val["main"]["sea_level"] );
	$item["mainPressureGroundLV"] = (string) ( $val["main"]["grnd_level"] );
	$item["windSpeed"]            = (string) ( $val["wind"]["speed"] );
	$item["windDirection"]        = (string) ( getWindDirection( $val["wind"]["deg"] ) );
	$item["windScale"]            = (string) ( getWindScale( $val["wind"]["speed"] ) );
	$item["windDirectionDeg"]     = (string) ( $val["wind"]["deg"] );
	$item["cloudiness"]           = (string) ( $val["clouds"]["all"] );
	$item["RainVolume3h"]         = (string) ( $val["rain"]["3h"] );
	$item["snowVolume3h"]         = (string) ( $val["snow"]["3h"] );
	$item["timestampx"]           = (string) ( $val["dt"] );
	$item["sysCountryCode"]       = (string) ( $val["sys"]["country"] );
	$item["sysSunrise"]           = (string) ( $val["sys"]["sunrise"] );
	$item["sysSunset"]            = (string) ( $val["sys"]["sunset"] );
	$item["CityID"]               = (string) ( $val["id"] );
	$item["CityName"]             = (string) ( $val["name"] );

	return $item;
}

function checkDuplicatet( $item, $pdo ) {
	$pdoQueryTest  = "SELECT timestampx, city, countryCode FROM history WHERE timestampx='" . $item["timestampx"] . "' AND city='" . $item["CityName"] . "' AND countryCode= '" . $item["sysCountryCode"] . "'";
	$pdoResultTest = $pdo->prepare( $pdoQueryTest );
	$pdoResultTest->execute();
	if ( $pdoResultTest->rowCount() > 0 ) {
		return false;
	} else {
		return true;
	}
}

function insertInto( $item ) {
	$insert = "INSERT INTO history (
GeoLocationLongitude,
GeoLocationLatitude,
weatherGroup,
weatherDescription,
weatherIconId,
temperature,
pressure,
humidity,
tempMin,
tempMax,
pressureSeaLV,
pressureGroundLV,
windSpeed,
windDirection,
windScale,
windDirectionDeg,
cloudiness,
rainVol,
snowVol,
timestampx,
countryCode,
sunrise,
sunset,
cityID,
city)
		VALUES(
		'" . $item["coordLongitude"] . "',
		'" . $item["coordLatitude"] . "',
		'" . $item["weatherGroup"] . "',
		'" . $item["weatherDescription"] . "',
		'" . $item["weatherIconId"] . "',
		'" . $item["mainTemperature"] . "',
		'" . $item["mainPressure"] . "',
		'" . $item["mainHumidity"] . "',
		'" . $item["mainTempMin"] . "',
		'" . $item["mainTempMax"] . "',
		'" . $item["mainPressureSeaLV"] . "',
		'" . $item["mainPressureGroundLV"] . "',
		'" . $item["windSpeed"] . "',
		'" . $item["windDirection"] . "',
		'" . $item["windScale"] . "',
		'" . $item["windDirectionDeg"] . "',
		'" . $item["cloudiness"] . "',
		'" . $item["RainVolume3h"] . "',
		'" . $item["snowVolume3h"] . "',
		'" . $item["timestampx"] . "',
		'" . $item["sysCountryCode"] . "',
		'" . $item["sysSunrise"] . "',
		'" . $item["sysSunset"] . "',
		'" . $item["CityID"] . "',
		'" . $item["CityName"] . "')";

	return $insert;
}

function getWindDirection( $windDirectionDeg ) {
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

	return $windDirection;
}

function getWindScale( $windSpeed ) {
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

	return $windScale;
}

