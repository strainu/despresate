<?php
function village_county_data($village_data, &$county, &$countyid)
{
    global $MyObject;
    
    $MyObject->Query("SELECT `denloc`,`jud` FROM `siruta` WHERE `siruta`.`_siruta`=".$village_data["sirsup"]." LIMIT 1");
    $county = $MyObject->getElement(0, 'denloc');
    $countyid = $MyObject->getElement(0, 'jud');
    $county = capitalize_counties($county);
    $county = str_ireplace("Județul ", "", $county);
    if ($county == -1) {
        echo "Problem fetching county";
        exit(1);
    }
}

function village_data($siruta)
{
    global $MyObject;
    
    $MyObject->Query("SELECT * FROM `siruta` FULL JOIN `localitate` ON `localitate`.`siruta` = `_siruta` WHERE `_siruta` =".$siruta." LIMIT 1");
    $village_data = $MyObject->getCurrentLine();
    if ($village_data == -1) {
        echo "We don't have any data for this place yet.";
        exit(1);
    }
    
    return $village_data;
}

function village_images($siruta)
{
    global $MyObject;

    $MyObject->Query("SELECT * FROM `imagini` WHERE `siruta`=".$siruta." ORDER BY RAND() DESC LIMIT 12");
    $images = $MyObject->getTable();
    if ($images == -1) {
        echo "Problem fetching image data";
        exit(1);
    }
    
    return $images;
}

function village_leaders($siruta)
{
    global $MyObject;

    $MyObject->Query("SELECT * FROM `oameni` WHERE `siruta`=".$siruta." ORDER BY `an` DESC");
    $leaders = $MyObject->getTable();
    if ($leaders == -1) {
        echo "Problem fetching county president";
        exit(1);
    }
    
    return $leaders;
}

function village_monuments($siruta)
{
    global $MyObject;

    $MyObject->Query("SELECT * FROM `monumente` LEFT JOIN `imagini` ON monumente.imagine = imagini.index WHERE monumente.`siruta`=".$siruta." ORDER BY RAND() LIMIT 10");
    
    $monuments = $MyObject->getTable();
    if ($monuments == -1) {
            echo "Problem fetching monument data";
            exit(1);
    }
    
    return $monuments;
}

function village_other_villages($village_data)
{
    global $MyObject;
    
    $MyObject->Query("SELECT `_siruta`, `denloc` FROM `siruta` WHERE `sirsup`=".$village_data['sirsup']);
    $commune_list = $MyObject->getTable();
    if ($commune_list == -1) {
        echo "Problem fetching UAT data";
        exit(1);
    }
    
    return $commune_list;
}

function village_population($siruta)
{
    global $MyObject;

    $MyObject->Query("SELECT * FROM `demografie` WHERE `siruta`=".$siruta." ORDER BY `an` DESC");
    $pop = $MyObject->getTable();
    if ($pop == -1) {
        echo "Problem fetching population data";
        exit(1);
    }
    
    return $pop;
}

function village_type($village_data)
{
    /*static $type_name = Array(
            1 => Array("article" => "un", "term" => "municipiu reședință de județ"),
            2 => Array("article" => "un", "term" => "oraș ce aparține de județ"),
            3 => Array("article" => "o", "term" => "comună"),
            4 => Array("article" => "un", "term" => "municipiu, altul decât reședința de județ"),
            5 => Array("article" => "un", "term" => "oraș reședință de județ"),
            6 => Array("article" => "un", "term" => "sector al municipiului București"),
            9 => Array("article" => "o", "term" => "localitate componentă, reședință de municipiu"),
            10 => Array("article" => "o", "term" => "localitate componentă a unui municipiu alta decât reședință de municipiu"),
            11 => Array("article" => "un", "term" => "sat ce aparține de municipiu"),
            17 => Array("article" => "o", "term" => "localitate componentă, reședință a orașului"),
            18 => Array("article" => "o", "term" => "localitate componentă a unui oraș, alta decât reședință de oraș"),
            19 => Array("article" => "un", "term" => "sat care aparține unui oraș"),
            22 => Array("article" => "un", "term" => "sat reședință de comună"),
            23 => Array("article" => "un", "term" => "sat ce aparține de comună, altul decât reședință de comună "),
            40 => Array("article" => "un", "term" => "județ")
        );*/
    static $type_name = Array(
            1 => Array("article" => "un", 
                        "term" => "municipiu", 
                        "articulated" =>"municipiul"),
            2 => Array("article" => "un", 
                        "term" => "oraș", 
                        "articulated" =>"orașul"),
            3 => Array("article" => "o", 
                        "term" => "comună", 
                        "articulated" =>"comuna"),
            4 => Array("article" => "un", 
                        "term" => "municipiu", 
                        "articulated" =>"municipiul"),
            5 => Array("article" => "un", 
                        "term" => "oraș", 
                        "articulated" =>"orașul"),
            6 => Array("article" => "un", 
                        "term" => "sector al municipiului București", 
                        "articulated" =>"sectorul"),
            9 => Array("article" => "o", 
                        "term" => "localitate componentă", 
                        "articulated" =>"localitatea componentă"),
            10 => Array("article" => "o", 
                        "term" => "localitate componentă", 
                        "articulated" =>"localitatea componentă"),
            11 => Array("article" => "un", 
                        "term" => "sat", 
                        "articulated" =>"satul"),
            17 => Array("article" => "o", 
                        "term" => "localitate componentă", 
                        "articulated" =>"localitatea componentă"),
            18 => Array("article" => "o", 
                        "term" => "localitate componentă", 
                        "articulated" =>"localitatea componentă"),
            19 => Array("article" => "un", 
                        "term" => "sat", 
                        "articulated" =>"satul"),
            22 => Array("article" => "un", 
                        "term" => "sat", 
                        "articulated" =>"satul"),
            23 => Array("article" => "un", 
                        "term" => "sat", 
                        "articulated" =>"satul"),
            40 => Array("article" => "un", 
                        "term" => "județ", 
                        "articulated" =>"județul")
        );
        
    return $type_name[$village_data['tip']];
}

function village_uat_data($siruta)
{
    global $MyObject;
    
    $MyObject->Query("SELECT `_siruta`, `denloc`, `codp` FROM `siruta` WHERE `sirsup`=".$siruta);
    $uat_data = $MyObject->getTable();
    if ($uat_data == -1) {
        echo "Problem fetching UAT data";
        exit(1);
    }
    
    return $uat_data;
}


function parse_village_leaders($leaders, 
                                &$mayor, &$mayorparty, &$mayoryear, &$mayorid,
                                &$vice, &$viceyear, &$viceid)
{
    $mayoryear = 0;
    $viceyear = 0;
    $vice = Array();
    foreach ($leaders as $leader) {
        switch($leader["functie"]) {
            case 1:
                if ($mayoryear)
                    continue;//TODO: history
                $mayor = $leader['nume'];
                $mayorparty = $leader['partid'];
                $mayoryear = $leader['an'];
                $mayorid = $leader['agenda_id'];
            break;
            case 2:
                if ($viceyear != 0 && $viceyear != $leader['an'])
                    continue;//TODO: history
                array_push($vice, Array( "name" => $leader['nume'],  "party" => $leader['partid']));
                $viceyear = $leader['an'];
                $viceid = $leader['agenda_id'];
            break;
        }
    }
    //TODO: local council
}
?>
