#!/usr/bin/python

county_names = [
    u"Alba",
    u"Arad",
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
    u"Satu-Mare",
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
import csv

def fetch_data(file_prefix, county, year, county_no, village):
    out_file = file_prefix + u".html"
    wget_comm = u"wget -O " + out_file + u" \"http://www.roaep.ro/alegeri/judete/" + year + u"loc/" + year + u"locp_xls.php?judet=" + county + u"&jud=" + str(county_no) + u"&aleg=1&den_aleg=Primar&circ=" + str(village) + u"\""
    print wget_comm
    os.system(wget_comm.encode("utf8"))
    convert_comm = u"python html2csv.py " + out_file + "; rm -rf " + out_file 
    print convert_comm
    os.system(convert_comm.encode("utf8"))

def parse_csv(file_prefix, out_file, year):
    in_file = file_prefix + u".csv"
    print in_file
    cr = csv.reader(open(in_file,"rb"))
    elem = [name for line in cr for name in line]
    #print elem
    try:
        county_no = int(elem[5])
        print u"Judet " + elem[7] + "; localitate " + elem[11]
    except:
        print "Fake file!"
        return
    if u'Tur 2' in elem:
        elem2 = elem[elem.index(u'Tur 2') + 1:]
        #print elem2
    else:
	elem2 = elem
    	#print elem2
    if elem2.index(u'%') + 2 > len(elem2):
        elem2 = elem #go back to round 1
    winner = elem2[elem2.index(u'%') + 1]
    party = elem2[elem2.index(u'%') + 2]

    county = elem[7].strip().upper()
    if county.find("JUDETUL") == -1:
	county = "JUDETUL " + county

    out_file.write(u"\"" + county + u"\",\"" + elem[11] + u"\",\"" + winner + u"\",\"" + party + u"\",\"")
    if year == u"96":
	out_file.write("1996\"\n")
    else:
        out_file.write("20" + year + "\"\n")

    print winner + " (" + party + ")"

if __name__ == "__main__":
    county_no = 0
    of = open("primari.csv", "w+")
    for county in county_names:
        county_no += 1
        for year in [u"96", u"00", u"04", u"08"]:
            for village in range(102):
                file_prefix = year + county + str(village + 1)
                #fetch_data(file_prefix, county, year, county_no, village + 1)
                parse_csv(file_prefix, of, year)
    of.close()
