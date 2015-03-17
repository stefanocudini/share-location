

var trackform$ = $('#trackform'),
	trackInput$ = trackform$.find('input[name=track]'),
	loader$ = $('#loader');

var map = new L.Map('map', {zoom: 15, center: new L.latLng([42,13]) }),
	lastPath = new L.GeoJSON();

map.addLayer(new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'));	//base layer

map.addLayer(lastPath);		//last path layer

function drawLastPath()
{
	if(gpxLayer.getLayers().length>0)	//draw last segment
	{
		lastPath.clearLayers();
		L.polyline([
			gpxLayer.getLayers().pop().getLayers().pop().getLatLng(),
			gpsControl.getLocation()	
			], {color: 'red'}).addTo(lastPath);
	}
}

var gpxLayer = new L.GPX(tracksDir+gpxDefault, {
	async: true,
	marker_options: {
		startIconUrl: 'leaflet-gpx/pin-icon-start.png',
		endIconUrl: 'leaflet-gpx/pin-icon-end.png',
		shadowUrl: 'leaflet-gpx/pin-shadow.png'
	}
});

gpxLayer
	.on("loaded", function(e) {
		//map.fitBounds(e.target.getBounds());
		drawLastPath();
	})

map.addLayer(gpxLayer);

var gpsControl = new L.Control.Gps({
	autoTracking:true,
	autoActive:true,
	marker: L.marker([0,0], {
		icon: L.icon({
			iconUrl:'pin-icon-gps.png',
			iconSize: [39, 60],
			shadowSize: [50, 50],
			iconAnchor: [18, 55],
			shadowAnchor: [18, 57]
		})
	})
});

gpsControl
.on('gpslocated', function(e) {
	trackform$.find('input[name=loc]').val(e.latlng.lat+','+e.latlng.lng);
	drawLastPath();
})
.on('gpsdisabled', function(e) {
	lastPath.clearLayers();
})
.addTo(map);

(function() {
	var control = new L.Control({position:'topleft'});
	control._zoomGpx = function(gpxline) {
			map.fitBounds(gpxline.getBounds());
		};
	control.onAdd = function(map) {
			var azoom = L.DomUtil.create('a','gpxzoom');
			azoom.title = "Zoom to track";
			L.DomEvent
				.disableClickPropagation(azoom)
				.addListener(azoom, 'click', function() {
					control._zoomGpx( gpxLayer );
				},azoom);
			return azoom;
		};
	return control;
}())
.addTo(map);

////FORM EVENTS

trackform$
.on('keypress', function(e) {	//disabilita submit tramite invio!
	if(e.which==13)
		e.preventDefault();
})
.on('click',':submit', function(e) {
	e.preventDefault();

	var params = trackform$.serializeArray(),
		actionName = $(e.target).attr('name');

	params.push({name: actionName, value: ''});

	console.log($.param(params));
	
	loader$.text($(e.target).val()+'...').slideDown();

	if(actionName=='reload'){
		gpxLayer.clearLayers();
		gpxLayer._parse(tracksDir+trackInput$.val());
		loader$.slideUp();	
	}

	$.ajax({
		type: 'GET',
		data: $.param(params),
		url: 'action.php',
		success: function(gpx) {
			gpxLayer.clearLayers();
			gpxLayer._parse(tracksDir+trackInput$.val());
			//TODO usare gpxLayer.reload() quando applicato nella repo ufficiale
			loader$.slideUp();
		}
	});	
});

