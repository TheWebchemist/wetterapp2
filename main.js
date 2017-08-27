$(window).load(function () {
	fillData(weatherData);
});


var fillData = function (data) {
	for (var i = 0; i < data.length; i++) {
		// console.log(data[i]);
		$("#history .container").append("" +
			"<div class='row'>" +
			"<div class='col-xs-1 small text-center timestampx'>" + data[i].timestampx + "</div>" +
			"<div class='col-xs-1 small text-center weatherGroup'>" + data[i].weatherGroup + "</div>" +
			"<div class='col-xs-1 small text-center temperature'>" + data[i].temperature + "</div>" +
			"<div class='col-xs-1 small text-center weatherDescription'>" + data[i].weatherDescription + "</div>" +
			"<div class='col-xs-1 small text-center windScale'>" + data[i].windScale + "</div>" +
			"<div class='col-xs-1 small text-center windSpeed'>" + data[i].windSpeed + "</div>" +
			"<div class='col-xs-1 small text-center windDirection'>" + data[i].windDirection + "</div>" +
			"<div class='col-xs-1 small text-center cloudiness'>" + data[i].cloudiness + "</div>" +
			"<div class='col-xs-1 small text-center pressure'>" + data[i].pressure + "</div>" +
			"<div class='col-xs-1 small text-center humidity'>" + data[i].humidity + "</div>" +
			"<div class='col-xs-1 small text-center sunrise'>" + data[i].sunrise + "</div>" +
			"<div class='col-xs-1 small text-center sunset'>" + data[i].sunset + "</div>" +
			"</div>");
	}

};
