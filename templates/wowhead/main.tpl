<html>
<head>
	{include file='head.tpl'}
	<style type="text/css">
{literal}
		.menu-buttons a { border-color: black }
		.news { position: relative; text-align: left; width: 415px; height: 191px; margin: 30px auto 0 auto; background: url(templates/wowhead/images/{/literal}{$language}{literal}/mainpage-bg-news.jpg) no-repeat }
		.news-list { padding: 26px 0 0 26px; margin: 0 }
		.news-list li { line-height: 2em }
		.news-img1 { position: absolute; left: 60px; top: 155px; width: 172px; height: 17px }
		.news-img2 { position: absolute; left: 246px; top: 48px; width: 145px; height: 127px }
		.news-talent { position: absolute; left: 240px; top: 29px; width: 152px; height: 146px }
{/literal}
	</style>
</head>
<body>
	<div id="layers"></div>
	<div id="home">
		<h1>{$title}</h1>
		<span id="h43jv6jk346" class="menu-buttons"></span>
		<script type="text/javascript">
			Menu.addButtons(ge('h43jv6jk346'), mn_path);
		</script>
		
		<div class="pad"></div>

		<form method="get" action="." onsubmit="if(trim(this.elements[0].value) == '') return false">
			<input type="text" name="search" size="38" id="kghjklvhj43v6" /><input type="submit" value="{#search#}" />
		</form>
		
		<script type="text/javascript">var _ = ge('kghjklvhj43v6'); LiveSearch.attach(_); _.focus();</script>

{if $news}
		<div class="news">
			<div class="news-list text">
				<ul>
{foreach from=$news item=item}
					<li><div>{$item.text}</div></li>
{/foreach}
				</ul>
			</div>
		</div>
{/if}
		
		<div id="toplinks" class="toplinks">
			{if $user}<a href="?user={$user.name}">{$user.name}</a>|<a href="?account=signout">{#Sign_out#}</a>{else}<a href="?account=signin">{#Sign_in#}</a>{/if}
			|<a href="javascript:;" id="toptabs-menu-language">{#Language#} <small>&#9660;</small></a>
			<script type="text/javascript">g_initHeaderMenus()</script>
		</div>
	</div>
	
	<div id="footer">
	</div>
	<noscript><div id="noscript-bg"></div><div id="noscript-text"><b>{#js_err#}</div></noscript>
	<script type="text/javascript">DomContentLoaded.now()</script>
</body>
</html>