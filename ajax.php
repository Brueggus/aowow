<?php
header('Content-type: application/x-javascript');
require_once('includes/allutil.php');

// Настройки
require_once('configs/config.php');
// Для Ajax отключаем debug
$AoWoWconf['debug'] = false;
// Для Ajax ненужен реалм
$AoWoWconf['realmd'] = false;
// Настройка БД
require_once('includes/db.php');

// Параметры передаваемые скрипту
@list($what, $id) = explode("=", $_SERVER['QUERY_STRING']);
$id = intval($id);

$x = '';

switch($what)
{
	case 'item':
		if(!$item = load_cache(6, $id))
		{
			require_once('includes/allitems.php');
			$item = allitemsinfo($id, 1);
			save_cache(6, $id, $item);
		}
		$x .= '$WowheadPower.registerItem('.$id.', 0, {';
		if ($item['name'])
			$x .= 'name: \''.ajax_str_normalize($item['name']).'\',';
		if ($item['quality'])
			$x .= 'quality: '.$item['quality'].',';
		if ($item['icon'])
			$x .= 'icon: \''.ajax_str_normalize($item['icon']).'\',';
		if ($item['info'])
			$x .= 'tooltip: \''.ajax_str_normalize($item['info']).'\'';
		$x .= '});';
		break;
	case 'spell':
		if(!$spell = load_cache(14, $id))
		{
			require_once('includes/allspells.php');
			$spell = allspellsinfo($id, 1);
			save_cache(14, $id, $spell);
		}
		$x .= '$WowheadPower.registerSpell('.$id.', 0,{';
		if ($spell['name'])
			$x .= 'name: \''.ajax_str_normalize($spell['name']).'\',';
		if ($spell['icon'])
			$x .= 'icon: \''.ajax_str_normalize($spell['icon']).'\',';
		if ($spell['info'])
			$x .= 'tooltip: \''.ajax_str_normalize($spell['info']).'\'';
		$x .= '});';
		break;
	case 'quest':
		if(!$quest = load_cache(11, $id))
		{
			require_once('includes/allquests.php');
			$quest = GetDBQuestInfo($id, QUEST_DATAFLAG_AJAXTOOLTIP);
			$quest['tooltip'] = GetQuestTooltip($quest);
			save_cache(11, $id, $quest);
		}
		$x .= '$WowheadPower.registerQuest('.$id.', 0,{';
		if($quest['name'])
			$x .= 'name: \''.ajax_str_normalize($quest['name']).'\',';
		if($quest['tooltip'])
			$x .= 'tooltip: \''.ajax_str_normalize($quest['tooltip']).'\'';
		$x .= '});';
		break;
	default:
		break;
}

echo $x;

?>