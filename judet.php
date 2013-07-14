<?php
setlocale(LC_ALL, 'ro_RO');
require('./include/class.SimpleSQL.php');
require('./include/config.php');
require('./include/judet_functions.php');
require('./include/sat_functions.php');
require('smarty/libs/Smarty.class.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0 || $_GET['id'] > 52)
    $index = 40;
else
    $index = $_GET['id'];
    
$smarty = new Smarty();

$MyObject = new SimpleSQL( $dbs, $dbu, $dbp, $db, 0, 0 );

//call the function that retrieve data from the database
$county_data = county_data($index);
$uat_data = county_uat_data($county_data);
county_region($county_data, $region, $hist_region);
$images = county_images($county_data);
$leaders = county_leaders($county_data);
$county_list = county_list();
$monuments = county_monuments($county_data);

$county_str = capitalize_counties($county_data['denloc']);
// Bucharest is special, it needs special treatment
// 403 is the SIRUTA code for Bucharest as a county
// 179132 is the SIRUTA code for Bucharest as a city
if ($index == 40) 
{
    //uat_data now contains the "other Bucharest" (i.e. the entry for Bucharest as a city)
    $siruta = $uat_data[0]['_siruta'];
    $village_data = village_data($siruta);
    $uat_data = village_uat_data($siruta);
    $pop = village_population($siruta);
    $short_name = str_ireplace("Municipiul ", "", $county_str);
}
else
{
    $pop = county_population($county_data);
    $short_name = str_ireplace("Județul ", "", $county_str);
}
$density = calculate_density($county_data, $pop);

$type = filter_input(INPUT_GET, 't', FILTER_SANITIZE_STRING);
$format = filter_input(INPUT_GET, 'f', FILTER_SANITIZE_STRING);
$commune = filter_input(INPUT_GET, 'commune', FILTER_SANITIZE_STRING);

switch($format)
{
    case 'csv':
        header('Content-type: text/csv; charset=utf-8');
        header('Content-disposition: attachment;filename='.$short_name.'-'.$type.'.csv');
        switch($type)
        {
            case 'stats':
                echo "siruta,".county_generate_stats_csv_header()."\n";
                if ($commune == "none" || $commune == "all")
                    echo $county_data['siruta'].",".
                        county_generate_stats_csv($county_data, $pop, $region, $hist_region)."\n";
                if ($commune == "all" || $commune == "villages")
                {
                    $stats = village_generate_all_stats_csv($county_data['siruta'], "\n");
                    foreach ($stats as $village)
                        echo $village;
                }
            break;
            case 'leaders':
                echo "siruta,".common_generate_leaders_csv_header()."\n";
                if ($commune == "none" || $commune == "all")
                {
                    $leaders = county_generate_leaders_csv($county_data, $leaders,"\n");
                    foreach ($leaders as $leader)
                        echo $county_data['siruta'].",".$leader;
                }
                if ($commune == "all" || $commune == "villages")
                {
                    $leaders = village_generate_all_leaders_csv($county_data['siruta'],"\n");
                    foreach ($leaders as $siruta => $leader)
                        echo $siruta.",".$leader;
                }
            break;
            case 'all':
            default:
                $sep = ",";
                $stats_header = county_generate_stats_csv_header();
                $ldrs_header = common_generate_leaders_csv_header();
                $county_str = "";
                $village_str = "";
                $ldrs_size = 0;
                if ($commune == "none" || $commune == "all")
                {
                    $stats = county_generate_stats_csv($county_data, $pop, $region, $hist_region);
                    $ldrs = county_generate_leaders_csv($county_data, $leaders, "");
                    $ldrs_size = count($ldrs);
                    $county_str = $county_data['siruta'].$sep.$stats.$sep;
                    foreach ($ldrs as $leader)
                        $county_str .= $leader;
                    $county_str .= "\n";
                }
                if ($commune == "all" || $commune == "villages")
                {
                    $stats = village_generate_all_stats_csv($county_data['siruta'], $sep);
                    $leaders = village_generate_all_leaders_csv($county_data['siruta'], "");
                    for ($i = 0; $i < count($stats); $i++)
                    {
                        $siruta = substr($stats[$i],0,strpos($stats[$i], $sep));
                        $village_str .= $stats[$i];
                        $village_str .= $leaders[$siruta];
                        rtrim($village_str, $sep);
                        $village_str .= "\n";
                        $ldrs_no = count(explode($sep, $leaders[$siruta])) / count(explode($sep, $ldrs_header));
                        if ($ldrs_no > $ldrs_size)
                            $ldrs_size = floor($ldrs_no);
                    }
                }
                /*print header*/
                echo "siruta,".$stats_header.$sep;
                for ($i = 0; $i < $ldrs_size; $i++)
                    echo $ldrs_header.$sep;
                echo "\n";
                /*print 1 line for county*/
                echo $county_str;
                /*print several lines for villages*/
                echo $village_str;
            break;
        };
    break;
    case 'json':
        header('Content-type: application/json; charset=utf-8');
        header('Content-disposition: attachment;filename='.$short_name.'-'.$type.'.json');
        
        $output = array();
        switch($type)
        {
            case 'stats':
                if ($commune == "none" || $commune == "all")
                {
                    $output['județ'] = county_generate_stats_array($county_data, $pop, $region, $hist_region);
                }
                if ($commune == "all" || $commune == "villages")
                {
                    $output['localități'] = village_generate_all_stats_array($county_data['siruta']);
                }
            break;
            case 'leaders':
                if ($commune == "none" || $commune == "all")
                {
                    $output['județ'] = county_generate_leaders_array($county_data, $leaders);
                }
                if ($commune == "all" || $commune == "villages")
                {
                    $output['localități'] = village_generate_all_leaders_array($county_data['siruta']);
                }
            break;
            case 'all':
                if ($commune == "none" || $commune == "all")
                {
                    $output['județ'] = array_merge_recursive_numeric(
                                        county_generate_stats_array($county_data, $pop, $region, $hist_region),
                                        county_generate_leaders_array($county_data, $leaders)
                                        );
                }
                if ($commune == "all" || $commune == "villages")
                {
                    $output['localități'] = array_merge_recursive_numeric(
                                            village_generate_all_stats_array($county_data['siruta']),
                                            village_generate_all_leaders_array($county_data['siruta'])
                                            );
                }
            default:
            break;
        };
        echo json_encode($output);
    break;
    case 'xml':
        header('Content-type: application/xml; charset=utf-8');
        header('Content-disposition: attachment;filename='.$short_name.'-'.$type.'.xml');
        
        $output = array();
        switch($type)
        {
            case 'stats':
                if ($commune == "none" || $commune == "all")
                {
                    $output['judet'] = county_generate_stats_array($county_data, $pop, $region, $hist_region, false);
                }
                if ($commune == "all" || $commune == "villages")
                {
                    $output['localitati'] = village_generate_all_stats_array($county_data['siruta'], false);
                }
            break;
            case 'leaders':
                if ($commune == "none" || $commune == "all")
                {
                    $output['judet'] = county_generate_leaders_array($county_data, $leaders, false);
                }
                if ($commune == "all" || $commune == "villages")
                {
                    $output['localitati'] = village_generate_all_leaders_array($county_data['siruta'], false);
                }
            break;
            case 'all':
                if ($commune == "none" || $commune == "all")
                {
                    $output['judet'] = array_merge_recursive_numeric(
                                        county_generate_stats_array($county_data, $pop, $region, $hist_region, false),
                                        county_generate_leaders_array($county_data, $leaders, false)
                                        );
                }
                if ($commune == "all" || $commune == "villages")
                {
                    $output['localitati'] = array_merge_recursive_numeric(
                                            village_generate_all_stats_array($county_data['siruta'], false),
                                            village_generate_all_leaders_array($county_data['siruta'], false)
                                            );
                }
            default:
            break;
        };
        $xml = new SimpleXMLElement('<?xml version="1.1" encoding="UTF-8"?><judet/>');
        array_to_xml_recursive($output, $xml);
        echo $xml->asXML();
    break;
    case 'html':
    default:

        process_county_leaders($leaders, 
                            &$cjpres, &$cjpresyear, &$cjpresid, &$cjpresparty,
                            &$cjvice, &$cjviceyear, &$cjmembers, &$cjmyear, 
                            &$prpres, &$prpresyear, &$prpresid, 
                            &$prvice, &$prviceyear, &$prviceid);
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
        if ($index == 40) 
        {
            $smarty->display('tpl/bucuresti.tpl');
        }
        else
        {
            $smarty->display('tpl/judet.tpl');
        }
    break;
}
?>
