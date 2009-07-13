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
global $languages;

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
		$x .= '$WowheadPower.registerItem('.$id.', '.$_SESSION['locale'].', {';
		if ($item['name'])
			$x .= 'name: \''.ajax_str_normalize($item['name']).'\',';
		if ($item['quality'])
			$x .= 'quality: '.$item['quality'].',';
		if ($item['icon'])
			$x .= 'icon: \''.ajax_str_normalize($item['icon']).'\',';
		if ($item['info'])
			$x .= 'tooltip_'.$languages[$_SESSION['locale']].': \''.ajax_str_normalize($item['info']).'\'';
		$x .= '});';
		break;
	case 'spell':
		if(!$spell = load_cache(14, $id))
		{
			require_once('includes/allspells.php');
			$spell = allspellsinfo($id, 1);
			save_cache(14, $id, $spell);
		}
		$x .= '$WowheadPower.registerSpell('.$id.', '.$_SESSION['locale'].',{';
		if ($spell['name'])
			$x .= 'name: \''.ajax_str_normalize($spell['name']).'\',';
		if ($spell['icon'])
			$x .= 'icon: \''.ajax_str_normalize($spell['icon']).'\',';
		if ($spell['info'])
			$x .= 'tooltip_'.$languages[$_SESSION['locale']].': \''.ajax_str_normalize($spell['info']).'\'';
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
		$x .= '$WowheadPower.registerQuest('.$id.', '.$_SESSION['locale'].',{';
		if($quest['name'])
			$x .= 'name: \''.ajax_str_normalize($quest['name']).'\',';
		if($quest['tooltip'])
			$x .= 'tooltip_'.$languages[$_SESSION['locale']].': \''.ajax_str_normalize($quest['tooltip']).'\'';
		$x .= '});';
		break;
	case 'achievement':
	/*
$WowheadPower.registerAchievement(42, 0, {
	name_enus: 'Explore Eastern Kingdoms',
	icon: 'Achievement_Zone_EasternKingdoms_01',
	tooltip_enus: '<table><tr><td><b class="q">Explore Eastern Kingdoms</b></td></tr></table><table><tr><td><br />Explore the regions of Eastern Kingdoms.<br /><br /><span class="q">Criteria:</span><table width="100%"><tr><td class="q0" style="white-space: nowrap"><small><!--cr1277:8:760-->- Alterac Mountains<br /><!--cr1279:8:765-->- Badlands<br /><!--cr1281:8:775-->- Burning Steppes<br /><!--cr1283:8:627-->- Dun Morogh<br /><!--cr1285:8:776-->- Elwynn Forest<br /><!--cr1287:8:772-->- Hillsbrad Foothills<br /><!--cr1289:8:780-->- Redridge Mountains<br /><!--cr1291:8:774-->- Searing Gorge<br /><!--cr1293:8:781-->- Stranglethorn Vale<br /><!--cr1295:8:773-->- The Hinterlands<br /><!--cr1297:8:802-->- Westfall<br /><!--cr1550:8:859-->- Eversong Woods<br /><!--cr1796:8:868-->- Isle of Quel\'Danas</small></td><th class="q0" style="white-space: nowrap; text-align: left"><small><!--cr1278:8:761-->- Arathi Highlands<br /><!--cr1280:8:766-->- Blasted Lands<br /><!--cr1282:8:777-->- Deadwind Pass<br /><!--cr1284:8:778-->- Duskwood<br /><!--cr1286:8:771-->- Eastern Plaguelands<br /><!--cr1288:8:779-->- Loch Modan<br /><!--cr1290:8:768-->- Tirisfal Glades<br /><!--cr1292:8:769-->- Silverpine Forest<br /><!--cr1294:8:782-->- Swamp of Sorrows<br /><!--cr1296:8:770-->- Western Plaguelands<br /><!--cr1298:8:841-->- Wetlands<br /><!--cr1551:8:858-->- Ghostlands</small></th></tr></table></td></tr></table>'
});
	*/
		if(!$achievement = load_cache(23, $id))
		{
			require_once('includes/allachievements.php');
			$achievement = allachievementsinfo($id, 1);
			save_cache(23, $id, $achievement);
		}
		$x .= '$WowheadPower.registerAchievement('.$id.', '.$_SESSION['locale'].',{';
		$x .= 'name_'.$languages[$_SESSION['locale']].': \''.ajax_str_normalize($achievement['name']).'\',';
		$x .= 'icon:\''.$achievement['icon'].'\',';
		$x .= 'tooltip_'.$languages[$_SESSION['locale']].':\''.ajax_str_normalize($achievement['tooltip']).'\'';
		$x .= '});';
		break;
	default:
		break;
}

echo $x;

?>