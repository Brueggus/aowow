<?php

function php2js($data)
{
	if (is_array($data))
	{
		// ������
		if (array_key_exists (0,$data))
		{
			// ������� ������ []
			$ret = "[";
			$i = 0;
			foreach ($data as $key => $obj)
			{
				// TODO: ��������� ��� ���������� ���������� ��� ��� �� ������ ������� �������
				if ($i != 0) {$ret.=',';}
				$ret .= php2js($obj);
				$i++;
			}			
			$ret .= "]";
		} else {
			// ������������� ������ {}
			$ret = "{"; $i = 0;
			foreach ($data as $key => $obj)
			{
				// TODO: ��������� ��� ���������� ���������� ��� ��� �� ������ ������� �������
				if ($i != 0) {$ret.=',';}
				$ret .= $key.':'.php2js($obj)."";
				$i++;
			}			
			$ret .= "}";
		}
	} else {
		// ������ ��������
		$ret = is_string($data)? "'".str_replace("\n", "<br>", str_normalize($data))."'" : $data;
	}
	return $ret;
}

switch($_GET['data'])
{
case 'talents':
	// i - id ������ (id �� aowow_talent)
	// n - �������� (spellname �� aowow_spell ��� spellID=rank1)
	// m - ���-�� ������ (6-����������_������_������)
	// s - ������ ��� ������ (rank1, rank2, ..., rank5 �� aowow_talent)
	// d - �������� �������
	// x - ������� (col �� aowow_talent)
	// y - ������ (row �� aowow_talent)
	// r - �� ���� � ������ ����� �������: "r: [r_1, r_2]", ��� r_1 - ����� (��������� � 0) �������, r_2 - ����
	
	require_once "includes/allspells.php";
	
	global $DB;
	// ��� "����" �������� ��������� ������
	
	$tabs = $DB->select('
		SELECT `id`, name_loc?d as `name`
		FROM ?_talenttab
		WHERE `classes`=?d
		ORDER by `order`
		',
		$_SESSION['locale'],
		pow(2, $_GET['class']-1)
		);
	
	$t_nums = array();
	$p_arr = array();
	$i = 0;
	foreach ($tabs as $tab)
	{
		$p_arr[$i] = array();
		$p_arr[$i]['n'] = $tab['name'];
		$talents = $DB->select('
			SELECT t.*, s.spellname_loc?d as `spellname`, i.`iconname`
			FROM ?_talent t, ?_spell s
			{LEFT JOIN (?_spellicons i) ON i.`id`=s.`spellicon`}
			WHERE
				t.`tab`=?d AND
				s.`spellID` = t.`rank1`
			ORDER by t.`row`, t.`col`
			',
			$_SESSION['locale'],
			$tab['id']
		);
		$j = 0;
		$p_arr[$i]['t'] = array();
		foreach ($talents as $talent)
		{
			$t_nums[$talent['id']] = $j;
			$p_arr[$i]['t'][$j] = array();
			$p_arr[$i]['t'][$j]['i'] = (integer) $talent['id'];
			$p_arr[$i]['t'][$j]['n'] = (string) $talent['spellname'];
			$p_arr[$i]['t'][$j]['m'] = (integer) ($talent['rank2']==0 ? 1 : ($talent['rank3']==0 ? 2 : (($talent['rank4']==0 ? 3 : (($talent['rank5']==0 ? 4 : 5))))));
			for ($k=0;$k<=($p_arr[$i]['t'][$j]['m']-1);$k++)
			{
				$p_arr[$i]['t'][$j]['s'][] = (integer) $talent['rank'.($k+1)];
				$p_arr[$i]['t'][$j]['d'][] = (string) spell_desc($talent['rank'.($k+1)]);
			}
			$p_arr[$i]['t'][$j]['x'] = (integer) $talent['col'];
			$p_arr[$i]['t'][$j]['y'] = (integer) $talent['row'];
			if ($talent['dependsOn'] AND $talent['dependsOnRank'])
				$p_arr[$i]['t'][$j]['r'] = array($t_nums[$talent['dependsOn']], $talent['dependsOnRank']+1);
			// Spell icons
			$p_arr[$i]['t'][$j]['icon'] = (string) $talent['iconname'];
			$j++;
		}
		$i++;
	}
	echo '$WowheadTalentCalculator.registerClass('.$_GET['class'].', '.php2js ($p_arr).')';
	break;
case 'glyphs':
	/*
		name - ��� ����
		description - ������ ������
		icon - ������ ����
	*/
	$glyphs = array();
	$glyphs = $DB->select('
		SELECT it.`entry`, it.`name`, it.`spellid_1` as `spell`, it.`AllowableClass`, ic.`iconname`
		FROM `item_template` it
		LEFT JOIN (?_icons ic) ON ic.id=it.displayid
		WHERE
			it.`class` = 16
	');
	$g_glyphs = array();
	foreach ($glyphs as $glyph)
	{
		$g_glyphs[$glyph['entry']] = array();
		$g_glyphs[$glyph['entry']]['name'] = (string) $glyph['name'];
		$g_glyphs[$glyph['entry']]['description'] = (string) 'Test';
		$g_glyphs[$glyph['entry']]['icon'] = (string) 'Test';
		$g_glyphs[$glyph['entry']]['type'] = (integer) 1;
		$g_glyphs[$glyph['entry']]['classs'] = (integer) 1;
		$g_glyphs[$glyph['entry']]['skill'] = (integer) 1;
	}
	echo('var g_glyphs='.php2js($g_glyphs));
	break;
}
?>