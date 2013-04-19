<?php
require('smarty/libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->assign('name', "Căutare");
$smarty->display('tpl/cauta.tpl');
?>
