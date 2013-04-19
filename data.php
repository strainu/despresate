<?php
require('smarty/libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->assign('name', "Date");
$smarty->display('tpl/data.tpl');
?>
