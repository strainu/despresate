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
            if ($merged[$key] == null)
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

?>
