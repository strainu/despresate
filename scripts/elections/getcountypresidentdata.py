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
import sirutalib

def fetch_data(file_prefix, county, year, county_no):
    out_file = file_prefix + u".html"
    wget_comm = u"wget -O " + out_file + u" \"http://www.roaep.ro/alegeri/judete/" + year + u"loc/" + year + u"loc_pcj_jud.php?judet=" + county + u"&jud=" + str(county_no) + u"&aleg=0&den_aleg=PCJ\""
    print wget_comm
    os.system(wget_comm.encode("utf8"))
    convert_comm = u"python html2csv.py " + out_file + "; rm -rf " + out_file 
    print convert_comm
    os.system(convert_comm.encode("utf8"))

def parse_csv(file_prefix, out_file, year):
    dia_trans = {ord(u"Ș"): u"S", ord(u"Ț"): u"T", 
				ord(u"Ş"): u"S", ord(u"Ţ"): u"T", 
				ord(u"Ă"): u"A", ord(u"Â"): u"A", 
				ord(u"Î"): u"I"}
    in_file = file_prefix + u".csv"
    #print in_file.encode("utf8")
    cr = csv.reader(open(in_file,"rb"))
    elem = [name.decode("utf8") for line in cr for name in line]
    #print elem
    try:
        county_no = int(elem[5])
    except:
        print "Fake file!"
        return
    winner = elem[elem.index(u'%') + 1]
    party = elem[elem.index(u'%') + 2]

    county = elem[7].strip().upper()
    if county.find(u"JUDETUL") == -1:
        if county.find(u"JUDET") == -1:
           county = u"JUDETUL " + county
        else:
            county = country.replace("JUDET", "JUDETUL")
        
    _csv = sirutalib.SirutaDatabase()
    for countyno in _csv._counties:
        if _csv._counties[countyno].translate(dia_trans) == county:
			county_no = countyno
			break
        else:
            #print _csv._counties[countyno].translate(dia_trans)
            pass

    #print str(county_no) + u" " + winner + u" " + party
    out_file.write(u"" + str(county_no) + u",\"" + winner + u"\",\"" + party + u"\",")
    if year == u"96":
	out_file.write("1996\n")
    else:
        out_file.write("20" + year + "\n")

if __name__ == "__main__":
    county_no = 0
    of = open("pcj.csv", "w+")
    for county in county_names:
        county_no += 1
        for year in [u"08"]:
                file_prefix = year + county + u"_pcj"
                #fetch_data(file_prefix, county, year, county_no)
                parse_csv(file_prefix, of, year)
    of.close()
