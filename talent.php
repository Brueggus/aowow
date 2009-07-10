<?php

$smarty->config_load($conf_file);

global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Talent' => true,
	'Title' => $smarty->get_config_vars('Talent'),
	'tab' => 1,
	'type' => 0,
	'typeid' => 0,
	'path' => '[]'
);
$smarty->assign('page', $page);
$smarty->assign('mysql', $DB->getStatistics());
$smarty->display('talent.tpl');

?>
