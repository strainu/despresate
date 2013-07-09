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

def find_siruta():
    dia_trans = {ord(u"Ș"): u"S", ord(u"ș"): u"s", ord(u"Ț"): u"T", ord(u"ț"): u"t", 
		ord(u"Ş"): u"S", ord(u"ş"): u"s", ord(u"Ţ"): u"T", ord(u"ţ"): u"t", 
		ord(u"Ă"): u"A", ord(u"Â"): u"A", ord(u"Î"): u"I", 
		ord(u"ă"): u"a", ord(u"â"): u"a", ord(u"î"): u"i"}
    data = {}
    _csv = sirutalib.SirutaDatabase()
    #TODO: this is based on the assumption that we don't have 2 communes with the same name in the same county
    for elem in _csv._data:
	index = _csv._data[elem]["name"].translate(dia_trans).replace("MUNICIPIUL ", "").replace("ORAS ", "").replace("ORASUL ", "").replace("-", " ").lstrip() + \
		_csv.get_county_string(elem).translate(dia_trans)
        if _csv._data[elem]['type'] <= 6:  # communes
	    if not index in data:
	        data[index] = elem
        if index == "BUCURESTIMUNICIPIUL BUCURESTI": #Bucuresti is always special...
            data[index] = elem
        if _csv._data[elem]['type'] == 40: # counties
	    #print _csv.get_county_string(elem).translate(dia_trans).replace("-", " ")
	    data[_csv.get_county_string(elem).translate(dia_trans).replace("-", " ")] = elem
	#print index
    #old data
    #cr = csv.reader(open("../../data/commune_historic_population_base.csv", "rb"))
    #cw = csv.writer(open("../../data/commune_historic_population.csv", "wb"))
    #2011 data
    cr = csv.reader(open("../../data/pop2011.csv", "rb"))
    cw = csv.writer(open("../../data/pop2011_final.csv", "wb"))
    #print data
    for line in cr:
	if line[0] == "ROMANIA":
            continue
	if line[0][:7] == "JUDETUL" or line[0] == "MUNICIPIUL BUCURESTI":
	    county = line[0]
            index = line[0].replace("-", " ")
	    #print index
        else:
	    index = line[0].replace("MUNICIPIUL ", "").replace("ORAS ", "").replace("ORASUL ", "").replace("-", " ").lstrip() + county
	if index in data:
	    #print index + str(data[index])
            line.append(data[index])
            if line[2] <> "":
                print "INSERT INTO `demografie`(`siruta`, `an`, `populatie`) VALUES(" + str(data[index]) +", " + str(line[1]) + ", " + str(line[2]) + ") ON DUPLICATE KEY UPDATE an=an;" 
	else:
            print "# " + county + str(line) + index
	    line.append(0)
	cw.writerow(line)
    pass

if __name__ == "__main__":
    find_siruta()
