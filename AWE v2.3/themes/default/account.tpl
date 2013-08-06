[logged]
<div class="content-container">
	<div class="content-title">Здравствуйте, {username} | <a target="_blank" href="{profile-link}">Профиль на сайте</a></div>
	<div class="content-text cabinet">
		<ul class="tabs">
			<li class="lt" data-type="tab"><a data-href="#main">Личный кабинет</a></li>
			<li class="lt" data-type="tab"><a data-href="#data">Изменение данных</a></li>
			<li class="lt" data-type="tab"><a data-href="#security">Безопасность</a></li>
			<li class="rt"><a href="{logout-link}">Выход</a></li>
			[admin]<li class="rt"><a href="{admin-link}">ПУ</a></li>[/admin]
		</ul>
		<div class="tabs-container">
			<div id="main" class="tabs-content">
				<div style="width: 100%;" class="main">
					<div class="well left">
						<div class="field">
							<label>Ваше имя:</label>
							<b>{name}</b>
						</div>
						<div class="field">
							<label>Ваш логин:</label>
							<b>{username}</b>
						</div>
						<div class="field">
							<label>Ваш день рождения:</label>
							<b>{birth}</b>
						</div>
						<div class="field">
							<label>Ваш E-Mail:</label>
							<b>{mail}</b>
						</div>
						<div class="field">
							<label>Ваша группа:</label>
							<b>{group}</b>
						</div>
					</div>
					<div class="well right">
						[referal]<div class="field">
							<label>Вас пригласил:</label>
							<b>{referal}</b>
						</div>[/referal]
						<div class="field">
							<label>IP при регистрации:</label>
							<b>{regip}</b>
						</div>
						<div class="field">
							<label>IP сейчас:</label>
							<b>{nowip}</b>
						</div>
						<div class="field">
							<label>Дата регистрации:</label>
							<b>{regdate}</b>
						</div>
						<div class="field">
							<label>Последний визит:</label>
							<b>{lastdate}</b>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div id="data" class="tabs-content">
				<form action="" method="POST">
					<div class="main" style="width: 60%;">
						<div class="field">
							<label for="name">Логин:</label>
							<input type="text" id="name" name="name" value="{username}" readonly>
						</div>
						<div class="field">
							<label for="name">Имя:</label>
							<input type="text" id="name" name="name" value="{name_value}">
						</div>
						<div class="field">
							<label for="mail">E-Mail:</label>
							<input type="text" id="mail" name="mail" value="{mail_value}">
						</div>
					</div>
					<div class="right"  style="width: 25%;"><img oncontextmenu="return false;" ondragstart="return false;" src="{THEME}/images/edit.png"></div>
					<div class="clearfix"></div>
					<button type="submit" name="data_send" class="e_button">Отправить</button>
				</form>
			</div>
			<div id="security" class="tabs-content">
				<form action="" method="POST">
					<div class="main" style="width: 60%;">
						<div class="field">
							<label for="oldpassword">Старый пароль:</label>
							<input type="password" id="oldpassword" name="oldpassword">
						</div>
						<div class="field">
							<label for="newpassword">Новый пароль:</label>
							<input type="password" id="newpassword" name="newpassword">
						</div>
						<div class="field">
							<label for="renewpassword">Повторите пароль:</label>
							<input type="password" id="renewpassword" name="renewpassword">
						</div>
						<div class="clearfix"></div>
						<div class="left"><button type="submit" name="password_send" class="e_button">Отправить</button></div>
						<div class="right"><button type="button" class="e_button" onClick="toggle('oldpassword'), toggle('newpassword'), toggle('renewpassword')">Показать / Скрыть</button></div>
					</div>
					<div class="right"  style="width: 25%;"><img oncontextmenu="return false;" ondragstart="return false;" src="{THEME}/images/security.png"></div>
					<div class="clearfix"></div>
				</form>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
[/logged]
[!logged]
<div class="content-container">
	<div class="content-title">Вход в систему</div>
	<div class="content-text">
		<form action="" method="POST">
			<div class="main">
				<div class="field">
					<label style="padding-right: 70px;" for="username">Логин</label>
					<input style="width: 134px; padding-right: 95px;" type="text" id="username" name="username" value="{username}">
					<div class="registration"><a href="{registration-link}">Еще нет?</a></div>
				</div>
				<div class="field">
					<label style="padding-right: 70px;" for="password">Пароль</label>
					<input style="width: 134px; padding-right: 95px;" type="password" id="password" name="password" value="{password}">
					<div class="lostpassword"><a href="{forgot-link}">Забыли?</a></div>
				</div>
				<div class="clearfix" style="height: 10px;"></div>
				<input class="checkbox" type="checkbox" id="remember" name="remember"><label for="remember"> Запомнить меня</label>
				<div class="clearfix" style="height: 15px;"></div>
				<div class="left"><button type="submit" name="login_send" class="e_button">Отправить</button></div>
				<div class="right"><button type="button" class="e_button" onClick="toggle('password')">Показать / Скрыть</button></div>
			</div>
			<div class="clearfix"></div>
		</form>
	</div>
</div>
[/!logged]