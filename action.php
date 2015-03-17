<?php

#sleep(1);

require_once('config.php');
require_once('geophp/geoPHP.inc');

$loc = isset($_GET['loc']) ? array_map('floatval', explode(',',$_GET['loc']) ) : array(10,10);
$loc = array_reverse($loc);
$locGeo = geoPHP::load('POINT('.implode(' ',$loc).')','wkt');

$trackFile = $tracksDir.(isset($_GET['track']) ? basename($_GET['track']) : 'live.gpx');

if(!preg_match('/.*\.gpx$/', $trackFile))
	$trackFile .= '.gpx';


if(!is_file($trackFile) or filesize($trackFile)==0)
{
	$loc2 = array($loc[0]+0.00001, $loc[1]+0.00001);
	$track = geoPHP::load( 'LINESTRING('.implode(' ',$loc).', '.implode(' ',$loc2).')' ,'wkt');
	$out = $track->out('gpx');
	file_put_contents($trackFile, $out);
}

if(isset($_GET['addpoint']))
{
	$track = geoPHP::load(file_get_contents($trackFile),'gpx');

	$trackAr =  $track->asArray();
	
	if( $locGeo->equals($track->endPoint()) )
		die('uguali');
	else
		$trackAr[]= $loc;

	$trackOut = array();
	foreach($trackAr as $v)
		$trackOut[]= new Point($v[0],$v[1]);

	$trackOut = new LineString($trackOut);
	
	$out = $trackOut->out('gpx');

	file_put_contents($trackFile, $out);
}
elseif(isset($_GET['delpoint']))
{
	$track = geoPHP::load(file_get_contents($trackFile),'gpx');

	if($track->numGeometries() < 3){
		unlink($trackFile);
	}

	$trackAr = $track->asArray();
	
	array_pop($trackAr);
	
	$trackOut = array();
	foreach($trackAr as $v)
	 	$trackOut[]= new Point($v[0],$v[1]);

	$trackOut = new LineString($trackOut);
	
	$out = $trackOut->out('gpx');

	file_put_contents($trackFile, $out);
}

header('Content-type: text/plain');
readfile($trackFile);

?>
