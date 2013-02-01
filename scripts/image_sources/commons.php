<?php
//$url = $_GET["url"];
//$siruta = $_GET["siruta"];
$url = $argv[1];
$siruta = $argv[2];
//$url = "http://commons.wikimedia.org/wiki/File:Romania_location_map.svg";
//$siruta = 10;

if(!filter_var($url, FILTER_VALIDATE_URL))
{
	echo "URL is not valid! <a href=\"javascript:go(-1);\">Go back</a>";
	exit(1);
}

$filename = end(explode("/", $url));
$newurl = "http://commons.wikimedia.org/w/api.php?format=json&action=query&titles=".$filename."&prop=imageinfo&iiprop=url&iiurlwidth=250px";
//print $newurl;

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "User-Agent: despresate/1.0 (http://despresate.strainu.ro/; despresate@strainu.ro)\r\n"
  )
);

$context = stream_context_create($opts);
$json = file_get_contents($newurl, false, $context);
$resp = json_decode($json, true);
print_r($resp);
//print_r($resp["query"]["pages"]);

foreach( $resp["query"]["pages"] as $img)
{
	if (array_key_exists("missing", $img)) {
		echo "Missing image";
		continue;
	}
	print_r($img["imageinfo"][0]);
	$thumb = $img["imageinfo"][0]["thumburl"];
	$imgurl = $img["imageinfo"][0]["url"];
	$descurl = $img["imageinfo"][0]["descriptionurl"];
	$sql = "INSERT INTO `imagini`(`siruta`, `imgurl`, `thumburl`, `descurl`) VALUES (".$siruta.", '".$imgurl."', '".$thumb."', '".$descurl."')";
	echo $sql;
}
?>
