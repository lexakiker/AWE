[!logged]
<div class="content-container">
	<div class="content-title">Восстановление пароля</div>
	<div class="content-text">
		<form action="" method="POST">
			<div class="main">
				<div class="field">
					<label for="username">Логин от забытого аккаунта</label>
					<input type="text" id="username" name="username" value="{username}">
				</div>
				<div class="field">
					<label for="mail">E-Mail от забытого аккаунта</label>
					<input type="text" id="mail" name="mail" value="{mail}">
				</div>
				<div class="field">
					<label for="keystring">Код с картинки</label>
					<input type="text" id="keystring" style="width: 150px;" name="keystring">
				</div>
				<button type="submit" name="forgotpass_send" class="e_button">Отправить</button>
			</div>
			<div class="right">
				<img id="captcha" class="img-polaroid" src="{captcha-link}">
				<p style="text-align: center;"><a href="javascript:captcha('captcha','{captcha-link}');">Не видно? Обновить</a></p>
			</div>
			<div class="clearfix"></div>
		</form>
	</div>
</div>
[/!logged]
[logged]
<div class="alert alert-info"><b>Информация:</b> Извините, вы уже авторизированы.</div>
[/logged]