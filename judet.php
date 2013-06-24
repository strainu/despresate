<?php
setlocale(LC_ALL, 'ro_RO');
require('./include/class.SimpleSQL.php');
require('./include/config.php');
require('./include/common.php');
require('smarty/libs/Smarty.class.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0 || $_GET['id'] > 52)
	$index = 40;
else
	$index = $_GET['id'];
    
$smarty = new Smarty();
	
$MyObject = new SimpleSQL( $dbs, $dbu, $dbp, $db, 0, 0 );
$MyObject->Query("SELECT * FROM `judet` FULL JOIN `siruta` ON `siruta`.`_siruta`=`siruta` WHERE `index`=".$index." LIMIT 1");
$county_data = $MyObject->getCurrentLine();
if ($county_data == -1) {
	echo "Problem fetching county data";
	exit(1);
}

$MyObject->Query("SELECT `_siruta`, `denloc` FROM `siruta` WHERE `sirsup`=".$county_data['siruta']);
$uat_data = $MyObject->getTable();
if ($uat_data == -1) {
	echo "Problem fetching UAT data";
	exit(1);
}

$MyObject->Query("SELECT * FROM `demografie` WHERE `siruta`=".$county_data['siruta']." ORDER BY `an` DESC");
$pop = $MyObject->getTable();
if ($pop == -1) {
	echo "Problem fetching population data";
	exit(1);
}

$MyObject->Query("SELECT `nume` FROM `regiuni` WHERE `regiune`=".$county_data['regiune']." OR `regiune`=".$county_data['regiune_istorica']." ORDER BY `regiune`");
$region = $MyObject->getElement(0, 'nume');
$hist_region = $MyObject->getElement(1, 'nume');
if ($region == -1 or $hist_region == -1) {
	echo "Problem fetching region data";
	exit(1);
}

$MyObject->Query("SELECT * FROM `imagini` WHERE `county`=".$county_data['jud']." ORDER BY RAND() DESC LIMIT 12");
$images = $MyObject->getTable();
if ($images == -1) {
	echo "Problem fetching image data";
	exit(1);
}

$MyObject->Query("SELECT * FROM `oameni` WHERE `siruta`=".$county_data['siruta']." ORDER BY `an` DESC");
$leaders = $MyObject->getTable();
if ($leaders == -1) {
	echo "Problem fetching county president";
	exit(1);
}
$cjpresyear = 0;
$cjviceyear = 0;
$cjpresid = 0;
$cjvice = Array();
$cjmembers = Array();
$cjmyear = 0;
$prpresyear = 0;
$prpresid = 0;
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

$MyObject->Query("SELECT * FROM `monumente` LEFT JOIN `imagini` ON monumente.imagine = imagini.index WHERE monumente.cod LIKE '".$county_data['prescurtare']."%' ORDER BY RAND() LIMIT 10");
$monuments = $MyObject->getTable();
if ($monuments == -1) {
        echo "Problem fetching monument data";
        exit(1);
}

$MyObject->Query("SELECT `denloc`,`jud` FROM `siruta` WHERE sirsup=1 ORDER BY `denloc`");
$county_list = $MyObject->getTable();
if ($county_list == -1) {
	echo "Problem fetching county list";
	exit(1);
}
for($i = 0; $i < $MyObject->getNrLines(); $i++)
	$county_list[$i]['denloc'] = capitalize_counties($county_list[$i]['denloc']);

$county_str = capitalize_counties($county_data['denloc']);


if($county_data['suprafata'])
    $density = $pop[0]['populatie'] / $county_data['suprafata'];
else
    $density = '';
$short_name = str_ireplace("Județul ", "", $county_str);


$type = filter_input(INPUT_GET, 't', FILTER_SANITIZE_STRING);
$format = filter_input(INPUT_GET, 'f', FILTER_SANITIZE_STRING);

switch($format)
{
    case 'csv':
        header('Content-type: text/csv');
        header('Content-disposition: attachment;filename='.$short_name.'-'.$type.'.csv');
        switch($type)
        {
            case 'stats':
                echo "siruta,nume,prescurtare,suprafata,populatie,populatie_an,densitate,regiune_adm,regiune_ist,wikipedia\n";
                echo $county_data['siruta'].','.$county_str.','.$county_data['prescurtare'].',"';
                echo $county_data['suprafata'].'",';
                echo $pop[0]['populatie'].','.$pop[0]['an'].',"'.$density.'",';
                echo $region.','.$hist_region.',"ro:'.$county_str."\"\n";
            break;
            case 'leaders':
                echo "siruta,poziție,nume,an\n";
                echo $county_data['siruta'].',prefect,'.$prpres.','.$prpresyear."\n";
                echo $county_data['siruta'].',subprefect,'.$prvice.','.$prviceyear."\n";
                echo $county_data['siruta'].',președinte consiliu județean,'.$cjpres.','.$cjpresyear."\n";
                foreach ($cjvice as $leader) {
                    echo $county_data['siruta'].',vicepreședinte consiliu județean,'.$leader['name'].','.$cjviceyear."\n";
                }
                foreach ($cjmembers as $leader) {
                    echo $county_data['siruta'].',membru consiliu județean,'.$leader['name'].','.$cjmyear."\n";
                }
            break;
            case 'all':
            default:
                echo "siruta,prescurtare,suprafata,populatie,populatie_an,densitate,regiune_adm,regiune_ist,wikipedia\n";
                echo $county_data['siruta'].','.$county_data['prescurtare'].',"'.$county_data['suprafata'].'",';
                echo $pop[0]['populatie'].','.$pop[0]['an'].',"'.$density.'",';
                echo $region.','.$hist_region.',"ro:'.$county_str."\"\n";
            break;
        };
    break;
    case 'json':
        header('Content-type: application/json');
        header('Content-disposition: attachment;filename='.$short_name.'-'.$type.'.json');
    break;
    case 'html':
    default:
        $smarty->assign('name', $county_str);
        $smarty->assign('index', $index);
        $smarty->assign('region', $region);
        $smarty->assign('hist_region', $hist_region);
        $smarty->assign('abbr', $county_data['prescurtare']);
        $smarty->assign('shortname', $short_name);
        $smarty->assign('siruta', $county_data['siruta']);
        $smarty->assign('uat', $uat_data);
        $smarty->assign('population', $pop[0]['populatie']);
        $smarty->assign('census', $pop[0]['an']);
        $smarty->assign('demography', array_reverse($pop));
        $smarty->assign('surface', $county_data['suprafata']);
        $smarty->assign('density', $density);

        $smarty->assign('cjpres', $cjpres);
        $smarty->assign('cjpresparty', $cjpresparty);
        $smarty->assign('cjpresyear', $cjpresyear);
        $smarty->assign('cjpresid', $cjpresid);
        if (count($cjvice) > 0)
            $smarty->assign('cjvice', $cjvice);
        $smarty->assign('cjcouncil', $cjmembers);
        $smarty->assign('cjaddr', $county_data['adrcj']);
        $smarty->assign('cjsite', $county_data['sitecj']);
        $smarty->assign('cjemail', $county_data['emailcj']);
        $smarty->assign('cjtel', $county_data['telefoncj']);
        $smarty->assign('cjfax', $county_data['faxcj']);

        $smarty->assign('prpres', $prpres);
        $smarty->assign('prpresid', $prpresid);
        $smarty->assign('pryear', $prpresyear);
        $smarty->assign('prvice', $prvice);
        $smarty->assign('prviceid', $prviceid);
        $smarty->assign('prviceyear', $prviceyear);
        $smarty->assign('praddr', $county_data['adrpr']);
        $smarty->assign('prsite', $county_data['sitepr']);
        $smarty->assign('premail', $county_data['emailpr']);
        $smarty->assign('prtel', $county_data['telefonpr']);
        $smarty->assign('prfax', $county_data['faxpr']);

        $smarty->assign('images', $images);
        $smarty->assign('monuments', $monuments);

        $smarty->assign('county_list', $county_list);

        $smarty->display('tpl/judet.tpl');
    break;
}
?>
