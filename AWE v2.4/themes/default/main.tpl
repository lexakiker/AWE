<!DOCTYPE html>
<html>
	<head>
		{p-headers}
		<link rel="shortcut icon" href="{THEME}/images/favicon.ico">
		<link type="text/css" rel="stylesheet" href="{THEME}/css/styles.css">
		<link type="text/css" rel="stylesheet" href="{THEME}/css/jQuery.FormStyler.css">
		<script type="text/javascript" src="{THEME}/js/jQuery.FormStyler.js"></script>
		<script type="text/javascript">
			function reload(id, url) {
				$(id).attr('src', url);
			}
			function toggle(object, mode, type) {
				mode = mode || false;
				type = type || false;
				if(type) {
					if($(object).css('display') == 'none') {
						$(object).css('display', 'block');
						$('#comment-form-state').html('Скрыть');
						$('#comment-form-toggle').css('border-bottom', '1px solid #C2C2C2');
					} else {
						$(object).css('display', 'none');
						$('#comment-form-state').html('Показать');
						$('#comment-form-toggle').css('border-bottom', '0');
					}
				} else {
					if(mode) {
						if($(object).get(0).type == 'password') {
							$(object).get(0).type = 'text';
							$('#show-hide').html('Скрыть');
						} else {
							$(object).get(0).type = 'password';
							$('#show-hide').html('Показать');
						}
					} else {
						if($(object).get(0).type == 'password') {
							$(object).get(0).type = 'text';
						} else {
							$(object).get(0).type = 'password';
						}
					}
				}
			}
			$(document).ready(function() {
				$('select').styler();
				$('#comment-form-toggle').css('border-bottom', '0');
				$('#show-hide').html('Показать');
				$('#comment-form-state').html('Показать');
				$('#comment-form-box').css('display', 'none');
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
				$('#comment-form-toggle').click(function(e) {
					toggle('#comment-form-box', true, true);
				});
				$('#show-hide').click(function(e) {
					toggle('#reg-password', true);
					toggle('#reg-re-password');
				});
				$('.menu .m-button').click(function(e) {
					e.stopPropagation();
					if($(this).parent().hasClass('selected')) {
						$('.menu .selected div div').removeClass('show');
						$('.menu .selected').removeClass('selected');
					} else {
						$('.menu .selected div div').removeClass('show');
						$('.menu .selected').removeClass('selected');
						if($(this).next('.subs').length) {
							$(this).parent().addClass('selected');
							$(this).next('.subs').children().addClass('show');
						}
					}
				});
				$(document).click(function() {
					$('.show').removeClass('show');
					$('.selected').removeClass('selected');
				});
			});
		</script>
		<title>{p-title} » {site-name}</title>
	</head>
	<body>
		[module="DesirableGoogle"][if_google_browser]
		<script type="text/javascript">setTimeout(function() { $('.browser').fadeOut('fast') },15000);</script>
		<style type="text/css">
			.browser { position: fixed; top: 15px; right: 15px; color: #FFFFFF; width: 279px; background: rgba(62,87,46,0.7); box-shadow: 0px 2px 7px rgba(0,0,0,0.3); text-shadow: 0px 1px 1px rgba(0,0,0,0.7); padding: 10px; }
			.browser h3 { margin-bottom: 5px; }
			.browser a { color: #F4FF74; }
			.browser b.box-hide { font-size: 12px; color: #EEEEEE; }
		</style>
		<div class="browser">
			<h3>Совет</h3>
			Для более правильного отображения<br>
			некоторых элементов желательно<br>
			использовать браузер <a href="https://google.com/intl/ru/chrome/browser/?hl=ru" target="_blank"><b>Google Chrome</b>!</a><br>
			<b class="box-hide">Через 15 секунд это сообщение исчезнет</b>
		</div>
		[/if][/module]
		<div class="header">
			<div class="wrap">
				<a href="{main-link}"><div class="logotype"></div></a>
			</div>
		</div>
		<div class="wrapper">
			<div class="top">
				<div class="left">
					<ul class="menu">
						<li><a href="{main-link}" class="m-button">Главная</a></li>
						<li><a href="{statistics-link}" class="m-button">Статистика</a></li>
						<li><a href="{feed-back-link}" class="m-button">Написать нам</a></li>
						<li><a href="{terms-link}" class="m-button">Правила</a></li>
						<li class="last">
							<a href="javascript:void(0)" class="m-button">Dropdown »</a>
							<div class="subs"><div>
								<ul>
									<li><a href="#">Item 1</a></li>
									<li><a href="#">Item 2</a></li>
									<li><a class="seperator"></a></li>
									<li><a href="#">Item 1</a></li>
									<li><a href="#">Item 2</a></li>
									<li><a href="#">Item 3</a></li>
								</ul>
							</div></div>
						</li>
					</ul>
				</div>
				<div class="right"><div class="fix"></div></div>
				<div class="clearfix"></div>
			</div>
			<div class="sidebar">
				<a target="blank" href="http://www.ameden.net/" class="developer">
					<div class="padding">Перейти на сайт<br>разработчика движка</div>
				</a>
				{login}
			</div>
			<div class="content">
				{p-info}{p-content}
			</div>
			<div class="clearfix"></div>
			<div class="footer">
				<div class="left">
					<a href="{main-link}">{config="title"}</a> - Copyright © 2013<br>
					<small>Powered by <a target="_blank" href="http://www.ameden.net/">Ameden Web Services</a>.</small>
				</div>
				<div class="right">
					<img src="{THEME}/images/88x31.png" title="88x31" alt="88x31">
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</body>
</html>