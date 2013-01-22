#!/usr/bin/python

county_names = [
    #u"Alba",
    #u"Arad",
    u"Argeș",
    u"Bacău",
    u"Bihor",
    u"Bistrița-Năsăud",
    u"Botoșani",
    u"Brașov",
    u"Brăila",
    u"Buzău",
    u"Caraș-Severin",
    u"Călărași",
    u"Cluj",
    u"Constanța",
    u"Covasna",
    u"Dâmbovița",
    u"Dolj",
    u"Galați",
    u"Giurgiu",
    u"Gorj",
    u"Harghita",
    u"Hunedoara",
    u"Ialomița",
    u"Iași",
    u"Ilfov",
    u"Maramureș",
    u"Mehedinți",
    u"Mureș",
    u"Neamț",
    u"Olt",
    u"Prahova",
    u"Satu Mare",
    u"Sălaj",
    u"Sibiu",
    u"Suceava",
    u"Teleorman",
    u"Timiș",
    u"Tulcea",
    u"Vaslui",
    u"Vâlcea",
    u"Vrancea",
]

import os

if __name__ == "__main__":
    county_no = 0
    for county in county_names:
        county_no += 1
        for year in [u"96"]:
            for village in range(100):
                out_file = year + county + str(village) + u".html"
                wget_comm = u"wget -O " + out_file + u" \"http://www.roaep.ro/alegeri/judete/" + year + u"loc/" + year + u"locp_xls.php?judet=" + county + u"&jud=" + str(county_no) + u"&aleg=1&den_aleg=Primar&circ=" + str(village) + u"\""
                print wget_comm
                os.system(wget_comm.encode("utf8"))
                convert_comm = u"python html2csv.py " + out_file
                print convert_comm
                os.system(convert_comm.encode("utf8"))