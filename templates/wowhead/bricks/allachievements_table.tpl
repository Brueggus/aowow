{*
		������ ���������� � �����
	����������, ������������ �������:
	data   - ������ ��� ����

	������ ������� ������ � �����:
		{include file='bricks/allachievements_table.tpl' data=$allachievements}
*}
var _ = g_achievements;
{strip}
	{foreach from=$data key=id item=item}
		_[{$id}]={ldelim}icon:'{$item.icon|escape:"javascript"}'{rdelim};
	{/foreach}
{/strip}

