<!DOCTYPE html>
<html>
	<head>
		{p-headers}
		<link rel="shortcut icon" href="{THEME}/admin/images/favicon.ico">
		<link type="text/css" rel="stylesheet" href="{THEME}/admin/css/styles.css">
		<link type="text/css" rel="stylesheet" href="{THEME}/admin/css/jQuery.CLEditor.css">
		<script type="text/javascript" src="{THEME}/admin/js/jQuery.CLEditor.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("textarea#short-story, textarea#full-story, textarea#content").cleditor({
					width: 620,
					height: 250
				});
				$('div.tabs-content').hide();
				$('ul.tabs li:first').addClass('active').show();
				$('div.tabs-content:first').show();
				$('ul.tabs li[data-type="tab"]').click(function() {
					$('ul.tabs li').removeClass('active');
					$(this).addClass('active');
					$('div.tabs-content').hide();
					var activeTab = $(this).find('a').attr('data-href');
					$(activeTab).show();
					return false;
				});
			});
		</script>
		<title>{p-title} » Панель управления {engine-name}</title>
	</head>
	<body>
		<div class="wrapper">
			<div class="login-panel">
				Здравствуйте, <b>{username}</b>!<br>
				<a href="{logout-link}">Выйти</a>
			</div>
			<div class="title-panel">{engine-name}</div>
			<div class="links-panel">
				<a href="{admin-link}">Главная</a><br>
				<a target="_blank" href="{main-link}">Просмотр сайта</a>
			</div>
			<div class="divider"><span></span></div>
			{p-content}
			<div class="divider"><span></span></div>
			<div class="footer">
				<a href="{admin-link}">{engine-name}</a> © 2012-2013 - All rights reserved<br>
				<em>Powered by <a target="_blank" href="http://www.ameden.net/">Ameden Web Services</a></em>
			</div>
		</div>
	</body>
</html>