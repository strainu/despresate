import sys
import simplejson as json
from path import path

# ogr2ogr -t_srs 'EPSG:900913' -f sqlite ro_uat_primari_2012.spatialite ro_uat_primari_2012/ro_uat_primari_2012.shp


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
        doc = {
            'type': "FeatureCollection",
            'features': [
                {'type': "Feature",
                 'geometry': feature['geometry'],
                 'properties': properties},
            ],
        }
        (out / ('%d.geojson' % siruta)).write_bytes(json.dumps(doc, indent=2))
        index.append(properties)

    (out / 'index.json').write_bytes(json.dumps(index, indent=2))


if __name__ == '__main__':
    main()
