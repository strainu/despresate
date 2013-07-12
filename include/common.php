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

?>
