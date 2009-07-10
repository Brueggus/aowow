<?php
$smarty->config_load($conf_file, 'factions');

global $DB;

$rows = $DB->select('
		SELECT factionID, team, name_loc?d AS name, side
		FROM ?_factions
		WHERE
			reputationListID != -1
	',
	$_SESSION['locale']
);
if(!$factions = load_cache(19, 'x'))
{
	unset($factions);

	$factions = array();
	foreach($rows as $i => $row)
	{
		$factions[$i] = array();
		$factions[$i]['entry'] = $row['factionID'];

		if($row['team'] <> 0)
			$factions[$i]['group'] = $DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_factions WHERE factionID=? LIMIT 1', $row['team']);

		if($row['side'])
			$factions[$i]['side'] = $row['side'];

		$factions[$i]['name'] = $row['name'];
	}
	save_cache(19, 'x', $factions);
}

global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => $smarty->get_config_vars('Factions'),
	'tab' => 0,
	'type' => 0,
	'typeid' => 0,
	'path' => '[0, 7]'
);
$smarty->assign('page', $page);

$smarty->assign('factions', $factions);
// Статистика выполнения mysql запросов
$smarty->assign('mysql', $DB->getStatistics());
// Загружаем страницу
$smarty->display('factions.tpl');
?>