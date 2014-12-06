<?php
function capitalize_counties($county_str) {
	$county_str = mb_strtolower($county_str);
	$county_str = ucwords(str_replace("-", "- ", $county_str));
	$county_str = str_replace("- ", "-", $county_str);
	return $county_str;
}

function calculate_density($data, $pop)
{
    if($data['suprafata'])
        $density = $pop[0]['populatie'] / $data['suprafata'];
    else
        $density = '';
        
    return number_format($density, 2, '.', '');
}

function common_generate_leaders_csv_header()
{
    return "poziție,nume,an";
}

function array_merge_recursive_numeric(array $array1, $array2 = null)
{
    $merged = $array1;
    
    if (is_array($array2))
        foreach ($array2 as $key => $val)
        {
            //print_r($array1[$key]);
            //print_r($array2[$key]);
            if (!array_key_exists($key, $merged))
            {
                $merged[$key] = $array2[$key];
                continue;
            }
            if (is_array($array2[$key]))
            {
                if (is_array($merged[$key]))
                    $merged[$key] = array_merge_recursive_numeric($merged[$key], $array2[$key]);
                else
                    $merged[$key] = array_merge_recursive_numeric(array($merged[$key]), $array2[$key]);
            }
            else
            {
                if (is_array($merged[$key]))
                   $merged[$key] = array_merge_recursive_numeric($merged[$key], array($array2[$key]));
                else
                    $merged[$key] = array($merged[$key], $array2[$key]);
            }
            //print_r($merged[$key]);
       }
    return $merged;
}

function array_to_xml_recursive($array, $xml)
{
    foreach ($array as $key => $val)
    {
        if (is_numeric($key))
            if ($xml->getName() == "localitati")
                $key = "localitate";
            else
                $key = "persoana";
        //get rid of whitespace in tag names
        $key = preg_replace('/\s+/', '_', $key);
        //echo $key." ".$val."\n";
        if (is_array($val))
        {
            $xml2 = $xml->addChild($key);
            array_to_xml_recursive($val, $xml2);
        }
        else
            $xml->addChild($key, $val);
    }
}

function siruta_nationality($siruta)
{
    global $MyObject;

    $MyObject->Query("SELECT `name`,`populatie` FROM `demografie_nationalitate` LEFT JOIN `nationalitati` ON `demografie_nationalitate`.`nationalitate`=`nationalitati`.`id` WHERE `demografie_nationalitate`.`uta` = ".$siruta." ORDER BY `populatie` DESC");
    $pop = $MyObject->getTable();
    if ($pop == -1) {
        echo "Problem fetching nationalities data";
        exit(1);
    }
    
    return $pop;
}

function siruta_religion($siruta)
{
    global $MyObject;

    $MyObject->Query("SELECT `name`,`populatie` FROM `demografie_religie` LEFT JOIN `religii` ON `demografie_religie`.`religie`=`religii`.`id` WHERE `demografie_religie`.`uta` = ".$siruta." ORDER BY `populatie` DESC");
    $pop = $MyObject->getTable();
    if ($pop == -1) {
        echo "Problem fetching nationalities data";
        exit(1);
    }
    
    return $pop;
}

?>
