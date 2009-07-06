<?php

require_once('includes/allspells.php');

$smarty->config_load($conf_file, 'spell');

global $DB;
global $AoWoWconf;
global $spell_cols;

@list($s1, $s2, $s3) = explode('.', $podrazdel);

$cache_str = (!isset($s1)?'x':intval($s1)).'_'.(!isset($s2)?'x':intval($s2)).'_'.(!isset($s3)?'x':intval($s3));

if(!$spells = load_cache(15, $cache_str))
{
	unset($spells);

	$spells = array();
	if($s1 == 7)
	{
		// Классовые
		$title = $smarty->get_config_vars('Class_spells');
		$rows = $DB->select('
				SELECT ?#, s.`spellID`, sla.skillID
				FROM ?_spell s, ?_skill_line_ability sla, ?_spellicons i
				WHERE
					s.spellID = sla.spellID
					AND s.levelspell > 0
					AND i.id=s.spellicon
					{AND sla.classmask & ?d}
					{AND sla.skillID=?d}
				ORDER BY s.levelspell
				{LIMIT ?d}
			',
			$spell_cols[2],
			(isset($s2))? pow(2,$s2-1): DBSIMPLE_SKIP,
			(isset($s3))? $s3: DBSIMPLE_SKIP,
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}
	elseif($s1 > 0)
	{
		switch($s1)
		{
			case 6:
				$title = $smarty->get_config_vars('Weapon_spells');
				break;
			case 8:
				$title = $smarty->get_config_vars('Armor_spells');
				break;
			case 10:
				$title = $smarty->get_config_vars('Languages');
				break;
			case 9:
				$title = $smarty->get_config_vars('Secondary_spells');
				break;
			case 11:
				$title = $smarty->get_config_vars('Profession_spells');
				break;
			default:
				$title = '???';
				break;
		}
		$spells['sort'] = "'skill', 'name'";
		// Профессии & other
		$rows = $DB->select('
			SELECT
				?#, `s`.`spellID`,
				sla.skillID, sla.min_value, sla.max_value
			FROM ?_spell s, ?_skill_line_ability sla, ?_spellicons i, ?_skill sk
			WHERE
				s.spellID = sla.spellID
				AND i.id=s.spellicon
				{AND sk.categoryID=?d}
				{AND sla.skillID=?d}
				AND sla.skillID=sk.skillID
			{LIMIT ?d}
		',
		$spell_cols[2],
		$s1,
		(isset($s2))? $s2: DBSIMPLE_SKIP,
		($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}
	elseif($s1 == -3)
	{
		$title = $smarty->get_config_vars('Pet_talents');
		// Петы
		$spells['sort'] = "'name'";
		$pets = isset($s2) ? array($s2) : $pet_skill_categories;
		$rows = $DB->select('
				SELECT
					?#, `s`.`spellID`, sla.skillID
				FROM ?_spell s, ?_skill_line_ability sla, ?_spellicons i
				WHERE
					s.spellID = sla.spellID
					AND s.levelspell > 0
					AND i.id=s.spellicon
					{AND sla.skillID IN (?a)}
				{LIMIT ?d}
			',
			$spell_cols[2],
			$pets,
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}
	elseif($s1 == -4)
	{
		$title = $smarty->get_config_vars('Racial_spells');
		$spells['sort'] = "'name'";
		// Racial Traits
		$spellids = $DB->selectCol('SELECT spellID FROM ?_skill_line_ability WHERE racemask > 0');
		$rows = $DB->select('
			SELECT
				?#, `s`.`spellID`
			FROM ?_spell s, ?_spellicons i
			WHERE
				s.spellID IN (?a)
				AND i.id=s.spellicon
			{LIMIT ?d}
			',
			$spell_cols[2],
			$spellids,
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}
	elseif ($s1 == -2)
	{
		// Talents
		$title = $smarty->get_config_vars('Talents');
		$spells['sort'] = "'name'";
		$spellids = $DB->selectCol('
			SELECT rank1
			FROM ?_talent t, ?_talenttab b
			WHERE b.id = t.tab {AND classes & ?d}
			GROUP BY rank1
			{LIMIT ?d}
			',
			(isset($s2))? pow(2,$s2-1) : DBSIMPLE_SKIP,
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
		$rows = $DB->select('
			SELECT
				?#, `s`.`spellID`, 1 AS `talent`
			FROM ?_spell s, ?_spellicons i
			WHERE
				s.spellID IN (?a)
				AND i.id=s.spellicon
			',
			$spell_cols[2],
			$spellids
		);
	}
	elseif ($s1 == -7)
	{
		// Pet Talents
		$title = $smarty->get_config_vars('Pet_Talents');
		$spells['sort'] = "'name'";
		// for speed
		$spellids = $DB->selectCol('
			SELECT rank1
			FROM ?_talent t, ?_talenttab b
			WHERE b.id = t.tab AND classes = 0
			GROUP BY rank1
			{LIMIT ?d}
			',
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
		// skill_line_ability тут не поможет
		$rows = $DB->select('
			SELECT
				?#, `s`.`spellID`, 1 AS `talent`
			FROM ?_spell s, ?_spellicons i
			WHERE
				s.spellID IN (?a)
				AND i.id=s.spellicon
			',
			$spell_cols[2],
			$spellids
		);
	}
	else
	{
		$spells['sort'] = "'name'";
		// просто спеллы
		$rows = $DB->select('
				SELECT
					?#, `s`.`spellID`
				FROM ?_spell s, ?_spellicons i
				WHERE
					i.id=s.spellicon
				{LIMIT ?d}
			',
			$spell_cols[2],
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}

	foreach($rows as $i => $row)
		$spells['data'][] = spellinfo2($row);

	save_cache(15, $cache_str, $spells);
}
global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => ($title?$title.' - ':'').$smarty->get_config_vars('Spells'),
	'tab' => 0,
	'type' => 6,
	'typeid' => 0,
	'path' => "[0, 1, ".intval($s1).", ".intval($s2).", ".intval($s3)."]",
	'sort' => isset($spells['sort'])?$spells['sort']:"'level','name'"
);
$smarty->assign('page', $page);

// Статистика выполнения mysql запросов
$smarty->assign('mysql', $DB->getStatistics());
// Если хоть одна информация о вещи найдена - передаём массив с информацией о вещях шаблонизатору
if (isset($allitems))
	$smarty->assign('allitems',$allitems);
if (count($allspells)>=0)
	$smarty->assign('allspells',$allspells);
if (count($spells)>=0)
	$smarty->assign('spells',$spells['data']);
// Загружаем страницу
$smarty->display('spells.tpl');

?>
