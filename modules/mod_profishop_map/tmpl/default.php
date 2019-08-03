<?php
defined('_JEXEC') or die;
?>
<script>
	function initialize() {

		var myLatlng = new google.maps.LatLng(<?php echo $sLatitude; ?>, <?php echo $sLongitude; ?>);
		var mapOptions = {
			zoom: <?php echo $iZoom; ?>,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.HYBRID,
			mapTypeControl: true,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
				position: google.maps.ControlPosition.TOP_CENTER
			},
		};

		var map = new google.maps.Map(document.getElementById('map-canvas'),
				mapOptions);

		var contentString = '<div id="content" style="width:270px"><h2 class="gm-title"><strong><?php echo $sTitle; ?></strong></h2><address><?php echo $sName; ?><br /><?php echo $sEmail; ?><br /><?php echo 'NIP: ' . $sNip; ?><br /><br /><?php echo $sPostal; ?> <?php echo $sCity; ?><br /><?php echo $sStreet; ?><br /><?php echo $sCountry; ?></address></div>';

		var infowindow = new google.maps.InfoWindow({
			content: contentString,
			position: myLatlng
		});

		infowindow.open(map);
		
		map.setCenter(new google.maps.LatLng('<?php echo $sCenterLatitude; ?>', '<?php echo $sCenterLongitude; ?>'));

		var iconBase = '/templates/profishop/favicon.ico';

		var marker = new google.maps.Marker({
			title: '<?php echo $sTitle; ?>',
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
		script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=initialize&key=<?php echo JFactory::getConfig()->get('api_key'); ?>';
		document.body.appendChild(script);
	}

	window.onload = loadScript;


</script>
<div id="map-canvas" style="width:<?php echo $sWidth; ?>px;height:<?php echo $sHeight; ?>px;" class="<?php echo $sModuleclassSfx; ?>"></div>
