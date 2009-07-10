<?php
// Настройка шаблонизатора и ДБ
include('includes/kernel.php');

if(isset($_COOKIE['remember_me']) && !isset($_SESSION['username']))
{
	$_SESSION['username'] = substr($_COOKIE['remember_me'], 0, strlen($_COOKIE['remember_me'])-40);
	$_SESSION['shapass'] = substr($_COOKIE['remember_me'], strlen($_COOKIE['remember_me'])-40, 40);
}

if(isset($_SESSION['username']) && isset($_SESSION['shapass']))
{
	$user = array();
	$user = CheckPwd($_SESSION['username'], $_SESSION['shapass']);
	$_SESSION['userid'] = $user['id'];
	$_SESSION['roles'] = $user['roles'];
	if($user > 0)
		$smarty->assign('user', $user);
	else
		unset($user);
}

$smarty->assign('locale', $_SESSION['locale']);
$smarty->assign('language', $languages[$_SESSION['locale']]);
$conf_file = $languages[$_SESSION['locale']].'.conf';
$smarty->assign('conf_file', $conf_file);

// Параметры передаваемые скрипту
$queryx = $_SERVER['QUERY_STRING'];
@list($razdel, $podrazdel) = explode('=', $_SERVER['QUERY_STRING'], 2);
$smarty->assign('query', $_SERVER['QUERY_STRING']);

// Параметры страницы
global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => '',
	'tab' => 0,
	'type' => 0,
	'typeid' => 0,
	'path' => '[]'
);

// В зависимости от раздела, выбираем что открывать:
switch($razdel)
{
	case 'locale':
		// Изменение языка сайта
		// Проверка на корректность данных происходит в checklocale()
		$_SESSION['locale'] = $podrazdel;
		checklocale();
		header('Location: '.($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '.'));
		break;
	case 'account':
		include 'account.php';
		break;
	case 'admin':
		if($_SESSION['roles'] == 2)
			include 'admin.php';
		else
			include 'main.php';
		break;
	case 'comment':
		include 'comment.php';
		break;
	case 'faction':
		include 'faction.php';
		break;
	case 'factions':
		include 'factions.php';
		break;
	case 'item':
		include 'item.php';
		break;
	case 'items':
		include 'items.php';
		break;
	case 'itemset':
		include 'itemset.php';
		break;
	case 'itemsets':
		include 'itemsets.php';
		break;
	case 'latest':
		include 'latest.php';
		break;
	case 'maps':
		include 'maps.php';
		break;
	case 'npc':
		include 'npc.php';
		break;
	case 'npcs':
		include 'npcs.php';
		break;
	case 'object':
		include 'object.php';
		break;
	case 'objects':
		include 'objects.php';
		break;
	case 'quest':
		include 'quest.php';
		break;
	case 'quests':
		include 'quests.php';
		break;
	case 'search':
		include 'search.php';
		break;
	case 'spell':
		include 'spell.php';
		break;
	case 'spells':
		include 'spells.php';
		break;
	case 'talent':
		include 'talent.php';
		break;
	case 'data':
		include 'data.php';
		break;
	default:
		include 'main.php';
		break;
}

?>