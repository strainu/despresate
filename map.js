(function() {

window.load_leaflet_map = function(container, data) {
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
    var feature = L.geoJson(data);
    feature.addTo(map);
    map.fitBounds([[data.bbox[1], data.bbox[0]],
                   [data.bbox[3], data.bbox[2]]]);
};


})();
