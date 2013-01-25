<?php
setlocale(LC_ALL, 'ro_RO');
require('./include/class.SimpleSQL.php');
require('./include/config.php');
require('smarty/libs/Smarty.class.php');

$smarty = new Smarty();

if (!isset($_GET['id']) || $_GET['id'] <= 0 || $_GET['id'] > 52)
	$index = 40;
else
	$index = $_GET['id'];
	
$MyObject = new SimpleSQL( "localhost", "strainu", "qbEE46RWrp", "despresate", 1 );
$MyObject->Query("SELECT * FROM `judet` WHERE `index`=".$index." LIMIT 1");
$county_data = $MyObject->getCurrentLine();
if ($county_data == -1) {
	echo "Problem fetching county data";
	exit(1);
}

$MyObject->Query("SELECT * FROM `siruta` WHERE `_siruta`=".$county_data['siruta']." LIMIT 1");
$siruta_data = $MyObject->getCurrentLine();
if ($siruta_data == -1) {
	echo "Problem fetching siruta data";
	exit(1);
}

$MyObject->Query("SELECT `_siruta`, `denloc` FROM `siruta` WHERE `sirsup`=".$county_data['siruta']);
$uat_data = $MyObject->getTable();
if ($uat_data == -1) {
	echo "Problem fetching UAT data";
	exit(1);
}

$MyObject->Query("SELECT * FROM `demografie` WHERE `siruta`=".$county_data['siruta']." ORDER BY `an` DESC LIMIT 1");
$pop = $MyObject->getCurrentLine();
if ($pop == -1) {
	echo "Problem fetching population data";
	exit(1);
}

$MyObject->Query("SELECT `nume` FROM `regiuni` WHERE `regiune`=".$siruta_data['regiune']." LIMIT 1");
$region = $MyObject->getElement(0, 'nume');
if ($region == -1) {
	echo "Problem fetching region data";
	exit(1);
}

$smarty->assign('name', ucwords(mb_strtolower($siruta_data['denloc'])));
$smarty->assign('region', $region);
$smarty->assign('shortname', $siruta_data['denloc']);//TODO
$smarty->assign('siruta', $county_data['siruta']);
$smarty->assign('uat', $uat_data);
$smarty->assign('population', $pop['populatie']);
$smarty->assign('census', $pop['an']);
$smarty->assign('surface', $county_data['suprafata']);
if($county_data['suprafata'])
	$smarty->assign('density', $pop['populatie'] / $county_data['suprafata']);
else
	$smarty->assign('density', '');

$smarty->assign('cjpres', 'TODO');//TODO
$smarty->assign('cjvice', 'TODO');//TODO
$smarty->assign('cjcouncil', 'TODO');//TODO
$smarty->assign('cjaddr', $county_data['adrcj']);
$smarty->assign('cjsite', $county_data['sitecj']);
$smarty->assign('cjemail', $county_data['emailcj']);
$smarty->assign('cjtel', $county_data['telefoncj']);
$smarty->assign('cjfax', $county_data['faxcj']);

$smarty->assign('prpres', 'TODO');//TODO
$smarty->assign('praddr', $county_data['adrpr']);
$smarty->assign('prsite', $county_data['sitepr']);
$smarty->assign('premail', $county_data['emailpr']);
$smarty->assign('prtel', $county_data['telefonpr']);
$smarty->assign('prfax', $county_data['faxpr']);

$smarty->display('tpl/judet.tpl');
?>
