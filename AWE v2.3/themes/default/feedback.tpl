<div class="content-container">
	<div class="content-title">Обратная связь</div>
	<div class="content-text">
		<form action="" method="POST">
			<div class="main">
				<div class="field">
					<label for="name">Ваше имя</label>
					<input style="width: 230px;" type="text" id="name" name="name" value="{name}"[logged] readonly[/logged]>
				</div>
				<div class="field">
					<label for="mail">Ваш E-Mail</label>
					<input style="width: 230px;" type="text" id="mail" name="mail" value="{mail}"[logged] readonly[/logged]>
				</div>
				<div class="field">
					<label for="subject">Тема</label>
					<input style="width: 230px;" type="text" id="subject" name="subject" value="{subject}">
				</div>
				<div class="field">
					<label for="message">Текст сообщения</label>
					<textarea style="width: 230px;" type="text" id="message" name="message">{message}</textarea>
				</div>
				<button type="submit" name="feedback_send" class="e_button">Отправить</button>
			</div>
			<div class="right">
				<img id="captcha" class="img-polaroid" src="{captcha-link}">
				<p style="text-align: center;"><a href="javascript:captcha('captcha','{captcha-link}');">Не видно? Обновить</a></p>
				<p><input type="text" id="keystring" name="keystring" style="width: 150px;" placeholder="Код с картинки"></p>
			</div>
			<div class="clearfix"></div>
		</form>
	</div>
</div>