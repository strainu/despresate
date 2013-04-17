import simplejson as json
from path import path


HERE = path(__file__).abspath().parent


def main():
    with (HERE / 'uat_all.geojson').open('rb') as f:
        uat = json.load(f)

    out = HERE / 'uat'
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
