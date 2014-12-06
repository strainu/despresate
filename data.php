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

$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
$format = filter_input(INPUT_GET, 'f', FILTER_SANITIZE_STRING);
$county = filter_input(INPUT_GET, 'county', FILTER_SANITIZE_STRING); 
$commune = filter_input(INPUT_GET, 'commune', FILTER_SANITIZE_STRING); 
switch ($format)
{
	case 'html':
		if ($county == "all")
			Header('Location: /');
		if ($commune == "none" || $commune == "all" || $commune == "villages")
			Header('Location: judet.php?id='.$county);
		else
			Header('Location: sat.php?siruta='.$commune);
	break;
	case 'csv':
	case 'json':
	case 'xml':
		if($county == "all")
		{
			//TODO
		}
		else
			if ($commune == "none" || $commune == "all" || $commune == "villages")
				Header('Location: judet.php?id='.$county.'&f='.$format.'&t='.$type.'&commune='.$commune);
			else
				Header('Location: sat.php?siruta='.$commune.'&f='.$format.'&t='.$type);
	break;
	default:
		$smarty->assign('name', "Date statistice");
		$smarty->assign('county_list', $county_list);
		$smarty->display('tpl/data.tpl');
	break;
}
?>
