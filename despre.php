<?php
require('smarty/libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->assign('name', "Despre proiect");
$smarty->display('tpl/despre.tpl');
?>
