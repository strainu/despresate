<?php
require('./include/class.SimpleSQL.php');
require('./include/config.php');
require('smarty/libs/Smarty.class.php');

$smarty = new Smarty();

if (!isset($_GET['id']) || $_GET['id'] <= 0 || $_GET['id'] > 52)
	$index = 40;
else
	$index = $_GET['id'];
	
$MyObject = new SimpleSQL( $dbs, $dbu, $dbp, $db, 0 );
$MyObject->Query("SELECT * FROM `judet` WHERE `index`=".$index);
$array = $MyObject->getCurrentLine();

$smarty->assign('name', $array['prescurtare']);
$smarty->assign('population', 100000);
$smarty->assign('census', 2011);
$smarty->assign('surface', $array['suprafata']);
if($array['suprafata'])
	$smarty->assign('density', 100000 / $array['suprafata']);
else
	$smarty->assign('density', '');

$smarty->assign('cjpres', 'TODO');
$smarty->assign('cjaddr', $array['adrcj']);
$smarty->assign('cjsite', $array['sitecj']);
$smarty->assign('cjemail', $array['emailcj']);
$smarty->assign('cjtel', $array['telefoncj']);
$smarty->assign('cjfax', $array['faxcj']);

$smarty->assign('prpres', 'TODO');
$smarty->assign('praddr', $array['adrpr']);
$smarty->assign('prsite', $array['sitepr']);
$smarty->assign('premail', $array['emailpr']);
$smarty->assign('prtel', $array['telefonpr']);
$smarty->assign('prfax', $array['faxpr']);

$smarty->display('tpl/judet.tpl');
?>
