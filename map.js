(function() {

window.load_leaflet_map = function(container, data, points) {
    var tile_url = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var attribution = '&copy; editorii <a href="http://openstreetma'
                    + 'p.org">OSM</a>, <a hr'
                    + 'ef="http://creativecommons.org/licenses/by-s'
                    + 'a/2.0/">CCBYSA</a>';

    var map = L.map(container);
    var layer = L.tileLayer(tile_url, {
        attribution: attribution,
        maxZoom: 18
    });
    layer.addTo(map);
	
	//--> Adaugat 20.06.2013
	if (typeof points != 'undefined')
	{
		var markers = new Array();
		
		var myIcon = new L.Icon.Default({
			iconSize: [15, 25],
			iconAnchor: [12, 25],
			popupAnchor: [-2, -25],
			shadowSize: [38, 25],
			shadowAnchor: [12, 25]
		});
		
		$.each(points, function(nr) {
			var marker = L.marker([points[nr].y, points[nr].x], {riseOnHover: true, icon: myIcon});
			marker
				.bindPopup(points[nr].denloc, {closeButton: false})
				.on('mouseover', function() { marker.openPopup(); } )
				.on('mouseout', function() { marker.closePopup(); } );
			
			markers.push(marker);
		});
		
		if (markers.length > 0)
		{
			L.featureGroup(markers).addTo(map);
		}
	}
	//--> terminat
	
    var feature = L.geoJson(data);
    feature.addTo(map);
    map.fitBounds([[data.bbox[1], data.bbox[0]],
                   [data.bbox[3], data.bbox[2]]]);
};

})();
