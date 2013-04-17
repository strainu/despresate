import sys
import simplejson as json
from path import path

# ogr2ogr -t_srs 'EPSG:4326' -f geojson uat-all-wgs84.geojson ro_uat_primari_2012/ro_uat_primari_2012.shp
# python split.py uat < uat-all-wgs84.geojson


def quantize(value):
    return float('%.4f' % value)


def main():
    uat = json.load(sys.stdin)
    out = path(sys.argv[1])
    propschema = sys.argv[2]
    out.mkdir_p()
    index = []
    for feature in uat['features']:
        if propschema == 'comune':
            # http://politicalcolours.ro/data/download/ro_uat_primari_2012.zip
            siruta = int(feature['properties']['siruta'])
            properties = {
                'siruta': siruta,
                'name': feature['properties']['uat_name'],
                'jud_code': feature['properties']['jud_code'],
                'jud_name': (feature['properties']['jud_name']
                                .lstrip(u"JUDE\u021aUL ")),
            }

        elif propschema == 'judete':
            # http://earth.unibuc.ro/geoserver - judete_ro layer
            siruta = int(feature['properties']['siruta'])
            properties = {
                'siruta': siruta,
                'name': feature['properties']['name'],
            }

        else:
            raise RuntimeError('unknown propschema %r' % propschema)

        coordinates = [[[quantize(value) for value in pair]
                        for pair in ring]
                       for ring in feature['geometry']['coordinates']]
        numbers = {
            'x': [pair[0] for pair in coordinates[0]],
            'y': [pair[1] for pair in coordinates[0]],
        }
        assert feature['geometry']['type'] == 'Polygon'
        doc = {
            'type': "FeatureCollection",
            'bbox': [min(numbers['x']), min(numbers['y']),
                     max(numbers['x']), max(numbers['y'])],
            'features': [
                {'type': "Feature",
                 'geometry': {'type': feature['geometry']['type'],
                              'coordinates': coordinates},
                 'properties': properties},
            ],
        }
        (out / ('%d.geojson' % siruta)).write_bytes(json.dumps(doc))
        index.append(properties)

    (out / 'index.json').write_bytes(json.dumps(index))


if __name__ == '__main__':
    main()
