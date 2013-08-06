<form action="" method="POST">
	<div class="main">
		<div class="field">
			<label for="name">Имя администратора</label>
			<input type="text" name="name" id="name" value="{name}">
		</div>
		<div class="field">
			<label for="birth">Дата рождения</label>
			<input type="text" name="birth" id="birth" value="{birth}" placeholder="По примеру: 14.06.1993">
		</div>
		<div class="field">
			<label for="username">Логин администратора</label>
			<input type="text" name="username" id="username" value="{username}">
		</div>
		<div class="field">
			<label for="mail">E-Mail администратора</label>
			<input type="text" name="mail" id="mail" value="{mail}">
		</div>
		<div class="field">
			<u>Только</u> латинские буквы и цифры.<br>
			<label for="password">Пароль администратора</label>
			<input type="password" name="password" id="password">
		</div>
		<div class="field">
			<label for="repassword">Повторите пароль</label>
			<input type="password" name="repassword" id="repassword">
		</div>
	</div>
	<div class="clearfix"></div>
	<button type="submit" name="goToFinish" class="e_button">Закончить</button>
</form>