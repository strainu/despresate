<?php
function capitalize_counties($county_str) {
	$county_str = mb_strtolower($county_str);
	$county_str = ucwords(str_replace("-", "- ", $county_str));
	$county_str = str_replace("- ", "-", $county_str);
	return $county_str;
}
?>
