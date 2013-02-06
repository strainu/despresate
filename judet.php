<?php
setlocale(LC_ALL, 'ro_RO');
require('./include/class.SimpleSQL.php');
require('./include/config.php');
require('smarty/libs/Smarty.class.php');

$smarty = new Smarty();

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0 || $_GET['id'] > 52)
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

$MyObject->Query("SELECT * FROM `demografie` WHERE `siruta`=".$county_data['siruta']." ORDER BY `an` DESC");
$pop = $MyObject->getTable();
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

$MyObject->Query("SELECT * FROM `imagini` WHERE `county`=".$siruta_data['jud']." ORDER BY RAND() DESC LIMIT 12");
$images = $MyObject->getTable();
if ($images == -1) {
	echo "Problem fetching image data";
	exit(1);
}

$MyObject->Query("SELECT * FROM `oameni` WHERE `county`=".$county_data['siruta']." ORDER BY `an` DESC");
$leaders = $MyObject->getTable();
if ($leaders == -1) {
	echo "Problem fetching county president";
	exit(1);
}
$cjpresyear = 0;
$cjviceyear = 0;
$cjvice = Array();
foreach ($leaders as $leader) {
	switch($leader["functie"]) {
		case 4:
			if ($cjpresyear)
				continue;//TODO: history
			$cjpres = $leader['nume'];
			$cjpresparty = $leader['partid'];
			$cjpresyear = $leader['an'];
		break;
		case 5:
			if ($cjviceyear != 0 && $cjviceyear != $leader['an'])
				continue;//TODO: history
			array_push($cjvice, Array( "name" => $leader['nume'],  "party" => $leader['partid']));
			$cjvideyear = $leader['an'];
		break;
	}
}

$MyObject->Query("SELECT * FROM `monumente`,`imagini` WHERE (monumente.imagine = imagini.index OR monumente.imagine=NULL) AND monumente.cod LIKE '".$county_data['prescurtare']."%' ORDER BY RAND() LIMIT 10");
//TODO: also show monuments without images
$monuments = $MyObject->getTable();
if ($monuments == -1) {
        echo "Problem fetching monument data";
        exit(1);
}

$MyObject->Query("SELECT `judet`.`index`, `siruta`.`denloc` FROM `judet`,`siruta` WHERE judet.siruta = siruta._siruta ORDER BY `siruta`.`denloc`");
$county_list = $MyObject->getTable();
if ($county_list == -1) {
	echo "Problem fetching county list";
	exit(1);
}
for($i = 0; $i < $MyObject->getNrLines(); $i++)
	$county_list[$i]['denloc'] = ucwords(mb_strtolower($county_list[$i]['denloc']));

$smarty->assign('name', ucwords(mb_strtolower($siruta_data['denloc'])));
$smarty->assign('region', $region);
$smarty->assign('abbr', $county_data['prescurtare']);
$smarty->assign('shortname', ucwords(mb_strtolower(str_replace("JUDEȚUL ", "", $siruta_data['denloc']))));
$smarty->assign('siruta', $county_data['siruta']);
$smarty->assign('uat', $uat_data);
$smarty->assign('population', $pop[0]['populatie']);
$smarty->assign('census', $pop[0]['an']);
$smarty->assign('demography', array_reverse($pop));
$smarty->assign('surface', $county_data['suprafata']);
if($county_data['suprafata'])
	$smarty->assign('density', $pop[0]['populatie'] / $county_data['suprafata']);
else
	$smarty->assign('density', '');

$smarty->assign('cjpres', $cjpres);
$smarty->assign('cjpresparty', $cjpresparty);
$smarty->assign('cjpresyear', $cjpresyear);
if (count($cjvice) > 0)
	$smarty->assign('cjvice', $cjvice);
//$smarty->assign('cjviceparty', $cjviceparty);
//$smarty->assign('cjviceyear', $cjviceyear);
$smarty->assign('cjcouncil', 'Nu dispunem încă de componența consiliului judetean. Dacă aveți o listă cu consilieri vă rugăm să ne contactați.');//TODO
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

$smarty->assign('images', $images);
$smarty->assign('monuments', $monuments);

$smarty->assign('county_list', $county_list);

$smarty->display('tpl/judet.tpl');
?>
