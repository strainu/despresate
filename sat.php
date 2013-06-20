<?php
setlocale(LC_ALL, 'ro_RO');
require('./include/class.SimpleSQL.php');
require('./include/config.php');
require('./include/common.php');
require('smarty/libs/Smarty.class.php');

$smarty = new Smarty();

if (!isset($_GET['siruta']) || !is_numeric($_GET['siruta']) || $_GET['siruta'] <= 0 || $_GET['siruta'] > 1000000)
	$siruta = 40;
else
	$siruta = $_GET['siruta'];
	

$MyObject = new SimpleSQL( $dbs, $dbu, $dbp, $db, 0, 0 );
$MyObject->Query("SELECT * FROM `siruta` FULL JOIN `localitate` ON `localitate`.`siruta` = `_siruta` WHERE `_siruta` =".$siruta." LIMIT 1");
$village_data = $MyObject->getCurrentLine();
if ($village_data == -1) {
	echo "We don't have any data for this place yet.";
	exit(1);
}

$MyObject->Query("SELECT `denloc`,`jud` FROM `siruta` WHERE `siruta`.`_siruta`=".$village_data["sirsup"]." LIMIT 1");
$county = $MyObject->getElement(0, 'denloc');
$countyid = $MyObject->getElement(0, 'jud');
$county = capitalize_counties($county);
$county = str_ireplace("Județul ", "", $county);
if ($county == -1) {
	echo "Problem fetching county";
	exit(1);
}

$MyObject->Query("SELECT `_siruta`, `denloc`, `codp` FROM `siruta` WHERE `sirsup`=".$village_data['siruta']);
$uat_data = $MyObject->getTable();
if ($uat_data == -1) {
	echo "Problem fetching UAT data";
	exit(1);
}

$MyObject->Query("SELECT `_siruta`, `denloc` FROM `siruta` WHERE `sirsup`=".$village_data['sirsup']);
$commune_list = $MyObject->getTable();
if ($commune_list == -1) {
	echo "Problem fetching UAT data";
	exit(1);
}

$MyObject->Query("SELECT * FROM `imagini` WHERE `siruta`=".$siruta." ORDER BY RAND() DESC LIMIT 12");
$images = $MyObject->getTable();
if ($images == -1) {
	echo "Problem fetching image data";
	exit(1);
}

$MyObject->Query("SELECT * FROM `demografie` WHERE `siruta`=".$village_data['siruta']." ORDER BY `an` DESC");
$pop = $MyObject->getTable();
if ($pop == -1) {
	echo "Problem fetching population data";
	exit(1);
}

$MyObject->Query("SELECT * FROM `oameni` WHERE `siruta`=".$siruta." ORDER BY `an` DESC");
$leaders = $MyObject->getTable();
if ($leaders == -1) {
	echo "Problem fetching county president";
	exit(1);
}

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

$MyObject->Query("SELECT * FROM `monumente` LEFT JOIN `imagini` ON monumente.imagine = imagini.index WHERE monumente.`siruta`=".$siruta." ORDER BY RAND() LIMIT 10");
$monuments = $MyObject->getTable();
if ($monuments == -1) {
        echo "Problem fetching monument data";
        exit(1);
}

$smarty->assign('name', mb_convert_case($village_data['denloc'], MB_CASE_TITLE));
$smarty->assign('county', $county);
$smarty->assign('countyid', $countyid);
$shortname = mb_convert_case(str_replace("MUNICIPIUL ", "", str_replace("ORAȘ ", "", $village_data['denloc'])), MB_CASE_TITLE);
$smarty->assign('shortname', $shortname);
$smarty->assign('siruta', $siruta);
$smarty->assign('surface', $village_data['suprafata']);

$smarty->assign('mayor', $mayor);
$smarty->assign('mayorparty', $mayorparty);
$smarty->assign('mayoryear', $mayoryear);
$smarty->assign('mayorid', $mayorid);
if (count($vice) > 0)
	$smarty->assign('clvice', $vice);
//$smarty->assign('viceparty', $viceparty);
//TODO: the commented line is correct, but until we get the information we can assume it's the same election year as the mayor
//$smarty->assign('clyear', $viceyear);
$smarty->assign('clyear', $mayoryear);

$smarty->assign('chaddr', $village_data['adresa']);
$smarty->assign('chsite', $village_data['site']);
$smarty->assign('chemail', $village_data['email']);
$smarty->assign('chtel', $village_data['telefon']);

$smarty->assign('population', $pop[0]['populatie']);
$smarty->assign('census', $pop[0]['an']);
$smarty->assign('demography', array_reverse($pop));
if($village_data['suprafata'])
	$smarty->assign('density', $pop[0]['populatie'] / $village_data['suprafata']);
else
	$smarty->assign('density', '');

$smarty->assign('uat', $uat_data);
$smarty->assign('commune_list', $commune_list);

$smarty->assign('images', $images);
$smarty->assign('monuments', $monuments);

if ($village_data['rang'] == "IV")
	$wikipedia = "Comuna ".$shortname.", ".$county;
else
	$wikipedia = $shortname;
$smarty->assign('wikipedia', $wikipedia);

$smarty->display('tpl/sat.tpl');
?>
