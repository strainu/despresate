<?php
setlocale(LC_ALL, 'ro_RO');
require('./include/class.SimpleSQL.php');
require('./include/config.php');
require('./include/common.php');
require('smarty/libs/Smarty.class.php');

$smarty = new Smarty();

if (!isset($_GET['siruta']) || !is_numeric($_GET['siruta']) || $_GET['siruta'] <= 0 || $_GET['siruta'] > 1000000)
	$siruta = 40;
else
	$siruta = $_GET['siruta'];

// Conectare
$MyObject = new SimpleSQL( $dbs, $dbu, $dbp, $db, 0, 0 );

// Sate X si Y
$MyObject->Query("select siruta.denloc as denloc,sat.X as x, sat.Y as y,sat.siruta as siruta from siruta,sat where siruta.sirsup='".$siruta."' and siruta._siruta=sat.siruta;");
$lista = $MyObject->getTable();

// GeoJSON
$data = array(
	'type'      => 'FeatureCollection',
	'features'  => array()
);

foreach ($lista as $key=>$value)
{
	$data['features'][] = array(
		'type'  => 'Feature',
		'id'    => $value['siruta'],
		'properties' => $value,
		'geometry' => array(
			'type' => 'Point',
			'coordinates' => array( (float)$value['x'], (float)$value['y'] )
		)
	);
}

echo json_encode($lista);
?>
