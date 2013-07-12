<?php
setlocale(LC_ALL, 'ro_RO');
require('./include/class.SimpleSQL.php');
require('./include/config.php');
require('./include/sat_functions.php');
require('smarty/libs/Smarty.class.php');

$smarty = new Smarty();

if (!isset($_GET['siruta']) || !is_numeric($_GET['siruta']) || $_GET['siruta'] <= 0 || $_GET['siruta'] >= 1000000)
    $siruta = 40;
else
    $siruta = $_GET['siruta'];
    
// Bucharest is special, it needs special treatment
// 40 is the county code for Bucharest
if ($siruta == 179132)
{
    Header('Location:judet.php?id=40');//redirect to the county page
}

$MyObject = new SimpleSQL( $dbs, $dbu, $dbp, $db, 0, 0 );


$village_data = village_data($siruta);
village_county_data($village_data, $county, $shortcounty, $countyid);
$uat_data = village_uat_data($siruta);
$commune_list = village_other_villages($village_data);
$images = village_images($siruta);
$pop = village_population($siruta);
$leaders = village_leaders($siruta);
$monuments = village_monuments($siruta);
$village_type = village_type($village_data);
$density = calculate_density($village_data, $pop);

parse_village_leaders($leaders, 
                    $mayor, $mayorparty, $mayoryear, $mayorid,
                    $vice, $viceyear, $viceid);

$smarty->assign('name', mb_convert_case($village_data['denloc'], MB_CASE_TITLE));
$smarty->assign('type', $village_type);
$smarty->assign('county', $county);
$smarty->assign('shortcounty', $shortcounty);
$smarty->assign('countyid', $countyid);
$shortname = mb_convert_case(str_replace("MUNICIPIUL ", "", str_replace("BUCUREȘTI ", "", str_replace("ORAȘ ", "", $village_data['denloc']))), MB_CASE_TITLE);
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
$smarty->assign('density', $density);

$smarty->assign('uat', $uat_data);
$smarty->assign('commune_list', $commune_list);

$smarty->assign('images', $images);
$smarty->assign('monuments', $monuments);

$wikipedia = village_wikipedia($village_data['rang'], $shortname, $shortcounty);
$smarty->assign('wikipedia', $wikipedia);

$smarty->display('tpl/sat.tpl');
?>
