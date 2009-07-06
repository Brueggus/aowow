<?php
require_once 'configs/config.php';

// Директория
global $cwd;
global $AoWoWconf;

$cwd = str_replace("\\", "/", getcwd());

// загружаем библиотеку Smarty
require_once $cwd.'/includes/Smarty-2.6.19/libs/Smarty.class.php';
class Smarty_AoWoW extends Smarty
{
		function Smarty_AoWoW()
		{
			global $cwd;
			global $AoWoWconf;
			$this->Smarty();
			// Папки с шаблонами, кэшом шаблонов и настройками
			$this->template_dir = $cwd.'/templates/'.$AoWoWconf['aowow']['template'].'/';
			$this->compile_dir = $cwd.'/cache/templates/'.$AoWoWconf['aowow']['template'].'/';
			$this->config_dir = $cwd.'/configs/';
			$this->cache_dir = $cwd.'/cache/';
			// Режим отладки
			$this->debugging = $AoWoWconf['debug'];
			// Разделители
			$this->left_delimiter = '{';
			$this->right_delimiter = '}';
			// Общее Кэширование, для этого сайта не работает
			$this->caching = false;
			// Имя сайта
			$this->assign('app_name', $AoWoWconf['aowow']['name']);
		}

		function uDebug($name, $val='')
		{
			if (!$val) $val='unset';
				$this->append($name,$val);
//			$this->append("UserDebug",);
		}
}

?>
