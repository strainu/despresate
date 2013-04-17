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
    out.mkdir_p()
    index = []
    for feature in uat['features']:
        properties = feature['properties']
        siruta = int(properties['siruta'])
        properties = {
            'siruta': siruta,
            'name': properties['uat_name'],
            'jud_code': properties['jud_code'],
            'jud_name': properties['jud_name'].lstrip(u"JUDE\u021aUL "),
        }
        geometry = {
            'type': feature['geometry']['type'],
            'coordinates': [[[quantize(value) for value in pair]
                             for pair in ring]
                            for ring in feature['geometry']['coordinates']],
        }
        doc = {
            'type': "FeatureCollection",
            'features': [
                {'type': "Feature",
                 'geometry': geometry,
                 'properties': properties},
            ],
        }
        (out / ('%d.geojson' % siruta)).write_bytes(json.dumps(doc))
        index.append(properties)

    (out / 'index.json').write_bytes(json.dumps(index))


if __name__ == '__main__':
    main()
