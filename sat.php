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

$MyObject->Query("SELECT `denloc` FROM `siruta` WHERE `siruta`.`_siruta`=".$village_data["sirsup"]." LIMIT 1");
$county = $MyObject->getElement(0, 'denloc');
$county = capitalize_counties($county);
$county = str_ireplace("Județul ", "", $county);
if ($county == -1) {
	echo "Problem fetching county";
	exit(1);
}


$MyObject->Query("SELECT * FROM `imagini` WHERE `siruta`=".$siruta." ORDER BY RAND() DESC LIMIT 12");
$images = $MyObject->getTable();
if ($images == -1) {
	echo "Problem fetching image data";
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
		break;
		case 2:
			if ($viceyear != 0 && $viceyear != $leader['an'])
				continue;//TODO: history
			array_push($vice, Array( "name" => $leader['nume'],  "party" => $leader['partid']));
			$viceyear = $leader['an'];
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

$smarty->assign('name', ucwords(mb_strtolower($village_data['denloc'])));
$smarty->assign('county', $county);//TODO
$smarty->assign('shortname', ucwords(mb_strtolower(str_replace("MUNICIPIUL ", "", str_replace("ORAȘ ", "", $village_data['denloc'])))));
$smarty->assign('siruta', $siruta);
$smarty->assign('surface', $village_data['suprafata']);

$smarty->assign('mayor', $mayor);
$smarty->assign('mayorparty', $mayorparty);
$smarty->assign('mayoryear', $mayoryear);
if (count($vice) > 0)
	$smarty->assign('clvice', $vice);
//$smarty->assign('viceparty', $viceparty);
$smarty->assign('clyear', $viceyear);

$smarty->assign('chaddr', $village_data['adresa']);
$smarty->assign('chsite', $village_data['site']);
$smarty->assign('chemail', $village_data['email']);
$smarty->assign('chtel', $village_data['telefon']);

$smarty->assign('images', $images);
$smarty->assign('monuments', $monuments);

$smarty->display('tpl/sat.tpl');
?>
