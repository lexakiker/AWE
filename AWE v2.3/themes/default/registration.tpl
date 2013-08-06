[!logged]
<div class="content-container">
	<div class="content-title">Регистрация пользователя</div>
	<div class="content-text">
		<form action="" method="POST">
			<div class="main">
				<div class="field">
					<label for="name">Ваше имя</label>
					<input type="text" id="name" name="name" value="{name}">
				</div>
				<div class="field">
					<label for="username">Логин</label>
					<input type="text" id="username" name="username" value="{username}">
				</div>
				<div class="field">
					<label for="mail">E-Mail</label>
					<input type="text" id="mail" name="mail" value="{mail}">
				</div>
				<div class="field">
					<label for="reg_password"><br>Пароль</label>
					<a style="cursor: pointer;" onclick="toggle('reg_password'), toggle('re_password')">Показать / Скрыть пароль</a><br>
					<input type="password" id="reg_password" name="password">
				</div>
				<div class="field">
					<label for="re_password">Повторите пароль</label>
					<input type="password" id="re_password" name="repassword">
				</div>
				<div class="field">
					<label for="birth">Дата рождения</label>
					<select name="day">{day-options}</select>
					<select name="month">{month-options}</select>
					<div><select name="year">{year-options}</select></div>
				</div>
			</div>
			<div class="right">
				<img id="captcha" class="img-polaroid" src="{captcha-link}">
				<p style="text-align: center;"><a href="javascript:captcha('captcha','{captcha-link}');">Не видно? Обновить</a></p>
				<p><input type="text" id="keystring" name="keystring" style="width: 150px;" placeholder="Код с картинки"></p>
			</div>
			<div class="clearfix"></div>
			<br><i style="color: red;">* При нажатии кнопки "Отправить" вы автоматически соглашаетесь <a target="_blank" href="{terms-link}">с нашими правилами</a></i><br><br>
			<button type="submit" name="registration_send" class="e_button">Отправить</button>
		</form>
	</div>
</div>
[/!logged]
[logged]
<div class="alert alert-info"><b>Информация:</b> Извините, вы уже авторизированы.</div>
[/logged]