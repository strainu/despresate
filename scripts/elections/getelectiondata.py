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
    #print in_file.encode("utf8")
    cr = csv.reader(open(in_file,"rb"))
    elem = [name.decode("utf8") for line in cr for name in line]
    #print elem
    try:
        county_no = int(elem[5])
    except:
        #print "Fake file!"
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
    if county.find(u"JUDETUL") == -1:
	if county.find(u"JUDET") == -1:
            county = u"JUDETUL " + county
        else:
            county = country.replace("JUDET", "JUDETUL")

    #print county + u"; localitate " + elem[11]
    out_file.write(u"\"" + county + u"\",\"" + elem[11] + u"\",\"" + winner + u"\",\"" + party + u"\",\"")
    if year == u"96":
	out_file.write("1996\"\n")
    else:
        out_file.write("20" + year + "\"\n")

    #print winner + " (" + party + ")"

def find_siruta():
    dia_trans = {ord(u"Ș"): u"S", ord(u"ș"): u"s", ord(u"Ț"): u"T", ord(u"ț"): u"t", 
		ord(u"Ş"): u"S", ord(u"ş"): u"s", ord(u"Ţ"): u"T", ord(u"ţ"): u"t", 
		ord(u"Ă"): u"A", ord(u"Â"): u"A", ord(u"Î"): u"I", 
		ord(u"ă"): u"a", ord(u"â"): u"a", ord(u"î"): u"i"}
    data = {}
    _csv = sirutalib.SirutaDatabase()
    #TODO: this is based on the assumption that we don't have 2 communes with the same name in the same county
    for elem in _csv._data:
	index = _csv._data[elem]["name"].translate(dia_trans).replace("MUNICIPIUL ", "").replace("ORAS ", "").replace("ORASUL ", "").replace("-", " ") + \
		_csv.get_county_string(elem).translate(dia_trans).replace("MUNICIPIUL ", "").replace("ORAS ", "").replace("ORASUL ", "").replace("-", " ")
	if not index in data and _csv._data[elem]['type'] <= 5:
	    data[index] = elem
	print index
    cr = csv.reader(open("primari_tmp.csv", "rb"))
    cw = csv.writer(open("primari.csv", "wb"))
    for line in cr:
	index = line[1].replace("MUNICIPIUL ", "").replace("ORAS ", "").replace("ORASUL ", "").replace("-", " ") + line[0]
	if index in data:
            line.append(data[index])
	cw.writerow(line)
    pass

if __name__ == "__main__":
    county_no = 0
    of = open("primari_tmp.csv", "w+")
    for county in county_names:
        county_no += 1
        for year in [u"96", u"00", u"04", u"08"]:
            for village in range(102):
                file_prefix = year + county + str(village + 1)
                #fetch_data(file_prefix, county, year, county_no, village + 1)
                parse_csv(file_prefix, of, year)
    of.close()
    find_siruta()
