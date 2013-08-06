<form action="" method="POST">
	<div class="main">
		<div class="field">
			<label for="db_host">Адрес MySQL</label>
			<input type="text" name="db_host" id="db_host" value="{db_host}">
		</div>
		<div class="field">
			<label for="db_user">Пользователь MySQL</label>
			<input type="text" name="db_user" id="db_user" value="{db_user}">
		</div>
		<div class="field">
			<label for="db_pass">Пароль пользователя MySQL</label>
			<input type="password" name="db_pass" id="db_pass" value="{db_pass}">
		</div>
		<div class="field">
			<label for="db_name">Имя базы MySQL</label>
			<input type="text" name="db_name" id="db_name" value="{db_name}">
		</div>
	</div>
	<div class="clearfix"></div>
	<button type="submit" name="goToStep3" class="e_button">Далее</button>
</form>