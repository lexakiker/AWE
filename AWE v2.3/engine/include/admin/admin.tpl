<!DOCTYPE html>
<html>
	<head>
		{headers}
		<link rel="shortcut icon" href="{engine}/admin/images/favicon.ico">
		<link rel="stylesheet" href="{engine}/admin/css/styles.css">
		<link rel="stylesheet" href="{engine}/admin/css/jQuery.FormStyler.css">
		<link rel="stylesheet" href="{engine}/admin/css/jQuery.CLEditor.css">
		<script type="text/javascript" src="{engine}/admin/js/jQuery.CLEditor.js"></script>
		<script type="text/javascript" src="{engine}/admin/js/jQuery.FormStyler.js"></script>
		<script type="text/javascript">
			$('window').load(function() {
				$('body').removeClass('preload');
			});
			$(document).ready(function() {
				$('select, input[type="checkbox"]').styler();
				$("#shortstory, #fullstory, #content").cleditor({
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
	</head>
	<body class="preload">
		<div class="wrapper">
			<div class="login-panel">
				Здравствуйте, {username}<br>
				<a href="{logout-link}">Выйти</a>
			</div>
			<div class="links-panel">
				<a href="{admin-link}">Главная</a><br>
				<a target="_blank" href="/">Просмотр сайта</a>
			</div>
			<div class="title-panel">Ameden Web Engine</div>
			<div class="divider"><span></span></div>
			{content}
			<div class="divider"><span></span></div>
			<div class="footer">
				<a href="{admin-link}">Ameden Web Engine</a> © 2012-2013 - Все права защищены!<br>
				<em>Powered by <a target="_blank" href="http://www.ameden.net/">Ameden Web Services</a></em>
			</div>
		</div>
	</body>
</html>