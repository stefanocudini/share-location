<?php

require_once('config.php');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<title></title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="stylesheet" href="http://leaflet.cloudmade.com/dist/leaflet.css" />
<link href="leaflet-gps/leaflet-gps.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="loader" style="display:none">save position...</div>
<form id="trackform" action="action.php" method="get">
	<input type="hidden" name="loc" value="" />
	<!--select id="track">
	<?
		#foreach(glob('./gpxs/*.gpx') as $f)
		#	echo '<option value="'.$f.'">'.basename($f).'</option>'."\n";
	?>
	</select-->
	<input type="submit" name="reload" value="Reload" style="width:5%" />	
	<input type="text" name="track" value="live.gpx" style="width:94%" />
	<input type="submit" name="addpoint" value="Add Position" style="width:49%" />
	<input type="submit" name="delpoint" value="Remove Position" style="width:49%" />
	<!-- <input type="submit" name="addtrack" value="Add new Track" /> -->
	<!-- <input type="submit" name="deltrack" value="Remove Track" /> -->
</form>
<div id="map_wrap">
	<div id="map"></div>
</div>
<script>

var tracksDir = "<?php echo $tracksDir; ?>",
	gpxDefault = "<?php echo $gpxDefault; ?>";

</script>
<script src="http://leaflet.cloudmade.com/dist/leaflet.js"></script>
<script src="//code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="leaflet-gps/leaflet-gps.js"></script>
<script src="leaflet-gps/leaflet-gps.js"></script>
<script src="leaflet-gpx/gpx.js"></script>
<script src="share-location.devel.js"></script>
</body>
</html>
