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

def generate_sql():
    cr = csv.reader(open("../../data/pop2002.csv", "rb"))
    #print data
    for line in cr:
        #print line
        if line[3].strip() <> "" and line[2].strip() <> "":
            print "INSERT INTO `demografie`(`siruta`, `an`, `populatie`) VALUES(" + str(line[3]) +", " + str(line[1]) + ", " + str(line[2]) + ") ON DUPLICATE KEY UPDATE an=an;" 
    pass

if __name__ == "__main__":
    generate_sql()
