<?php
include_once("common.php");

//database-related functions
function county_data($index) {
    global $MyObject;

    $MyObject->Query("SELECT * FROM `judet` FULL JOIN `siruta` ON `siruta`.`_siruta`=`siruta` WHERE `index`=".$index." LIMIT 1");
    $county_data = $MyObject->getCurrentLine();
    if ($county_data == -1) {
        echo "Problem fetching county data";
        exit(1);
    }
    
    return $county_data;
}

function county_images($county_data)
{
    global $MyObject;

    $MyObject->Query("SELECT * FROM `imagini` WHERE `county`=".$county_data['jud']." ORDER BY RAND() DESC LIMIT 12");
    $images = $MyObject->getTable();
    if ($images == -1) {
        echo "Problem fetching image data";
        exit(1);
    }
    
    return $images;
}

function county_leaders($county_data)
{
    global $MyObject;

    $MyObject->Query("SELECT * FROM `oameni` WHERE `siruta`=".$county_data['siruta']." ORDER BY `an` DESC");
    $leaders = $MyObject->getTable();
    if ($leaders == -1) {
        echo "Problem fetching county president";
        exit(1);
    }
    
    return $leaders;
}

function county_list()
{
    global $MyObject;

    $MyObject->Query("SELECT `denloc`,`jud` FROM `siruta` WHERE sirsup=1 ORDER BY `denloc`");
    $county_list = $MyObject->getTable();
    if ($county_list == -1) {
        echo "Problem fetching county list";
        exit(1);
    }
    for($i = 0; $i < $MyObject->getNrLines(); $i++)
        $county_list[$i]['denloc'] = capitalize_counties($county_list[$i]['denloc']);

    return $county_list;
}

/**
 * county_monuments - retrieve a list of maximum 10 historic monuments from that county
 *
 * @param Array $county_data 
 * @return all monuments from the database
 */
function county_monuments($county_data)
{
    global $MyObject;

    $MyObject->Query("SELECT * FROM `monumente` LEFT JOIN `imagini` ON monumente.imagine = imagini.index WHERE monumente.cod LIKE '".$county_data['prescurtare']."%' ORDER BY RAND() LIMIT 10");
    $monuments = $MyObject->getTable();
    if ($monuments == -1) {
            echo "Problem fetching monument data";
            exit(1);
    }
    
    return $monuments;
}

function county_population($county_data) {
    global $MyObject;

    $MyObject->Query("SELECT * FROM `demografie` WHERE `siruta`=".$county_data['siruta']." ORDER BY `an` DESC");
    $pop = $MyObject->getTable();
    if ($pop == -1) {
        echo "Problem fetching population data";
        exit(1);
    }
    
    return $pop;
}

function county_region($county_data, &$region, &$hist_region) {
    global $MyObject;

    $MyObject->Query("SELECT `nume` FROM `regiuni` WHERE `regiune`=".$county_data['regiune']." OR `regiune`=".$county_data['regiune_istorica']." ORDER BY `regiune`");
    $region = $MyObject->getElement(0, 'nume');
    $hist_region = $MyObject->getElement(1, 'nume');
    if ($region == -1 or $hist_region == -1) {
        echo "Problem fetching region data";
        exit(1);
    }
}

function county_uat_data($county_data) {
    global $MyObject;

    $MyObject->Query("SELECT `_siruta`, `denloc` FROM `siruta` WHERE `sirsup`=".$county_data['siruta']);
    $uat_data = $MyObject->getTable();
    if ($uat_data == -1) {
        echo "Problem fetching UAT data";
        exit(1);
    }
    
    return $uat_data;
}

function process_county_leaders($leaders, 
                                &$cjpres, &$cjpresyear, &$cjpresid, &$cjpresparty,
                                &$cjvice, &$cjviceyear, &$cjmembers, &$cjmyear, 
                                &$prpres, &$prpresyear, &$prpresid, 
                                &$prvice, &$prviceyear, &$prviceid)
{
    $cjpres = "";
    $cjpresyear = 0;
    $cjpresid = 0;
    $cjvice = Array();
    $cjviceyear = 0;
    $cjmembers = Array();
    $cjmyear = 0;
    $prpres = "";
    $prpresyear = 0;
    $prpresid = 0;
    $prvice = "";
    $prviceyear = 0;
    $prviceid = 0;
    
    foreach ($leaders as $leader) {
        switch($leader["functie"]) {
            case 4:
                if ($cjpresyear)
                    continue;//TODO: history
                $cjpres = $leader['nume'];
                $cjpresparty = $leader['partid'];
                $cjpresyear = $leader['an'];
                $cjpresid = $leader['agenda_id'];
            break;
            case 5:
                if ($cjviceyear != 0 && $cjviceyear != $leader['an'])
                    continue;//TODO: history
                array_push($cjvice, Array( "name" => $leader['nume'],  "party" => $leader['partid'], "id" => $leader['agenda_id']));
                $cjviceyear = $leader['an'];
            break;
            case 6:
                if ($cjmyear != 0 && $cjmyear != $leader['an'])
                    continue;//TODO: history
                array_push($cjmembers, Array( "name" => $leader['nume'],  "party" => $leader['partid'], "id" => $leader['agenda_id']));
                $cjmyear = $leader['an'];
            break;
            case 7:
                if ($prpresyear)
                    continue;
                $prpres = $leader['nume'];
                $prpresyear = $leader['an'];
                $prpresid = $leader['agenda_id'];
            break;
            case 8:
                if ($prviceyear)
                    continue;
                $prvice = $leader['nume'];
                $prviceyear = $leader['an'];
                $prviceid = $leader['agenda_id'];
            break;
        }
    }
}

//csv-related functions


function county_generate_stats_csv_header()
{
    return "nume,prescurtare,suprafata,populatie,populatie_an,densitate,regiune_adm,regiune_ist,wikipedia";
}

function county_generate_stats_csv($county_data, $pop, $region, $hist_region)
{
    $county_str = capitalize_counties($county_data['denloc']);
    $density = calculate_density($county_data, $pop);
    $data = $county_str.','.$county_data['prescurtare'].',"';
    $data .= $county_data['suprafata'].'",';
    $data .= $pop[0]['populatie'].','.$pop[0]['an'].',"'.$density.'",';
    $data .= $region.','.$hist_region.',"ro:'.$county_str."\"";

    return $data;
}

function county_generate_leaders_csv($county_data, $leaders, $separator)
{
    process_county_leaders($leaders, 
                            &$cjpres, &$cjpresyear, &$cjpresid, &$cjpresparty,
                            &$cjvice, &$cjviceyear, &$cjmembers, &$cjmyear, 
                            &$prpres, &$prpresyear, &$prpresid, 
                            &$prvice, &$prviceyear, &$prviceid);

    $data = Array();
    array_push($data, 'președinte consiliu județean,'.$cjpres.','.$cjpresyear.$separator);
    foreach ($cjvice as $leader) {
        array_push($data, 'vicepreședinte consiliu județean,'.$leader['name'].','.$cjviceyear.$separator);
    }
    foreach ($cjmembers as $leader) {
        array_push($data, 'membru consiliu județean,'.$leader['name'].','.$cjmyear.$separator);
    }
    if ($prpres)
        array_push($data, 'prefect,'.$prpres.','.$prpresyear.$separator);
    if ($prvice)
        array_push($data, 'subprefect,'.$prvice.','.$prviceyear.$separator);
    
    return $data;
}
?>
