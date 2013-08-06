<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=windows-1251">
		<meta name="robots" content="noindex,nofollow">
		<link rel="shortcut icon" href="{engine}/admin/images/favicon.ico">
		<link rel="stylesheet" href="{engine}/admin/css/styles.css">
		<link rel="stylesheet" href="{engine}/admin/css/jQuery.FormStyler.css">
		<script type="text/javascript" src="{engine}/jQuery-1.9.1.min.js"></script>
		<script type="text/javascript" src="{engine}/admin/js/jQuery.FormStyler.js"></script>
		<script type="text/javascript">
			$('window').load(function() {
				$('body').removeClass('preload');
			});
			$(document).ready(function() {
				$('input[type="checkbox"]').styler();
			});
		</script>
		<title>{title} » Ameden Web Engine</title>
	</head>
	<body class="preload">
		<div class="wrapper login">
			<h2 style="text-align: center;">Панель управления</h2>
			<div class="clearfix" style="height: 10px;"></div>
			<form action="" method="POST">
				[message]<p align="center">{message}</p>[/message]
				<input type="text" name="username" placeholder="Логин"><br>
				<input type="password" name="password" placeholder="Пароль">
				<div class="divider"><span></span></div>
				<div class="left" style="line-height: 25px;"><input class="checkbox" type="checkbox" id="remember" name="remember"><label for="remember"> Запомнить</label></div>
				<div class="right"><button type="submit" name="admin_send" class="btn btn-primary">Войти в ПУ</button></div>
			</form>
			<div class="clearfix"></div>
		</div>
	</body>
</html>