<!DOCTYPE html>
<html>
	<head>
		{headers}
		<link rel="stylesheet" href="{THEME}/css/styles.css">
		<link rel="shortcut icon" href="{THEME}/images/favicon.ico">
		<link rel="stylesheet" href="{THEME}/css/jQuery.FormStyler.css">
		<script type="text/javascript" src="{THEME}/js/jQuery.FormStyler.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('select, input[type="checkbox"]').styler();
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
				$('.button-list > li > a').click(function () {
					if ($(this).parent().hasClass('selected')) {
						$('.button-list .selected div div').hide();
						$('.button-list .selected').removeClass('selected');
					} else {
						$('.button-list .selected div div').hide();
						$('.button-list .selected').removeClass('selected');
						if ($(this).next('div.subs').length) {
							$(this).parent().addClass('selected');
							$(this).next('div.subs').children().show();
						}
					}
				});
			});
			function captcha(id,url) {
				var element = document.getElementById(id);
				if(element) element.src = url;
			}
			function toggle(object) {
				var object = document.getElementById(object);
				if(object.type == 'password') object.type = 'text';
				else object.type = 'password';
			}
			$('window').load(function() {
				$('body').removeClass('preload');
			});
		</script>
	</head>
	<body class="preload">
		<div class="space"></div>
		<div class="header">
			<a href="/"><div class="logotype"></div></a>
		</div>
		<div class="wrapper-border">
			<div class="wrapper">
				<div class="top default">
					<div class="left">
						<ul class="button-list">
							<li><a href="/" class="button">Главная</a></li>
							<li><a href="{statistics-link}" class="button">Статистика</a></li>
							<li><a href="{feedback-link}" class="button">Написать нам</a></li>
							<li><a href="{terms-link}" class="button">Правила</a></li>
							<li>
								<a class="button">Dropdown »</a>
								<div class="subs"><div>
									<ul>
										<li><a href="#">Item 1</a></li>
										<li><a href="#">Item 2</a></li>
										<li><a class="sep"></a></li>
										<li><a href="#">Item 1</a></li>
										<li><a href="#">Item 2</a></li>
										<li><a href="#">Item 3</a></li>
									</ul>
								</div></div>
							</li>
						</ul>
					</div>
					<a href="#"><button type="button" class="download"><span>Какая-либо кнопка...<em>Описание</em></span></button></a>
				</div>
				<div class="clearfix"></div>
				<div class="sidebar">
					{login}
					<div class="sidebar-container">
						<div class="sidebar-title">Боковой блок</div>
						<div class="sidebar-text">
							Содержимое блока.
						</div>
					</div>
					<div class="sidebar-container">
						<div class="sidebar-title">Боковой блок</div>
						<div class="sidebar-text">
							Содержимое блока.
						</div>
					</div>
				</div>
				<div class="content">
					{info}{content}
				</div>
				<div class="clearfix"></div>
				<div class="footer">
					<a href="/"><div class="gear"></div></a>
					<a href="/"><div class="gear2"></div></a>
					<div class="footer-text">
						<a href="/">{config="title"}</a> - Copyright © 2013
					</div>
				</div>
			</div>
		</div>
		<div class="space"></div>
	</body>
</html>