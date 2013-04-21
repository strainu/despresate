<?php
setlocale(LC_ALL, 'ro_RO');
require('./include/class.SimpleSQL.php');
require('smarty/libs/Smarty.class.php');
require('./include/common.php');
require('./include/config.php');
$smarty = new Smarty();

$MyObject = new SimpleSQL( $dbs, $dbu, $dbp, $db, 0, 0 );
$MyObject->Query("SELECT `denloc`,`jud` FROM `siruta` WHERE sirsup=1 ORDER BY `denloc`");
$county_list = $MyObject->getTable();
if ($county_list == -1) {
	echo "Problem fetching county list";
	exit(1);
}
for($i = 0; $i < $MyObject->getNrLines(); $i++)
	$county_list[$i]['denloc'] = capitalize_counties($county_list[$i]['denloc']);

$smarty->assign('name', "Date");
$smarty->assign('county_list', $county_list);
$smarty->display('tpl/data.tpl');
?>
