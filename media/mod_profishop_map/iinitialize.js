function initialize() {

	var myLatlng = new google.maps.LatLng(50.003686, 18.464210);
	var mapOptions = {
		zoom: 15,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.HYBRID
	};

	var map = new google.maps.Map(document.getElementById('map-canvas'),
			mapOptions);

	var contentString = '<div id="content"><div class="gm-title"><strong>Farmacom</strong></div><div id="content">44-300 Wodzisław Śl.<br />ul. Jana 16</div></div>';

	var infowindow = new google.maps.InfoWindow({
		content: contentString,
		position: myLatlng
	});

	infowindow.close(map);

	var iconBase = '/diamant/templates/profishop/favicon.ico';

	var marker = new google.maps.Marker({
		title: 'Diamant',
		position: myLatlng,
		map: map,
		icon: iconBase
	});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map, marker);
	});

}

function loadScript() {
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
			'callback=initialize';
	document.body.appendChild(script);
}

window.onload = loadScript;

