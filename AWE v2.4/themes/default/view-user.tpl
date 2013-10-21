<div class="content-container">
	<div class="content-title">Профиль пользователя <b>{username}</b></div>
	<div class="content-text">
		<div class="table">
			<div class="tr">
				<label>Имя:</label>
				<div><b>{name}</b></div>
			</div>
			<div class="tr">
				<label>Логин:</label>
				<div><b>{username}</b></div>
			</div>
			<div class="tr">
				<label>Группа пользователя:</label>
				<div><b>{group}</b></div>
			</div>
			<div class="tr">
				<label>Дата регистрации:</label>
				<div><b>{reg-date}</b></div>
			</div>
			<div class="tr">
				<label>Последний визит:</label>
				<div><b>{last-date}</b></div>
			</div>
			[referal]
			<div class="tr">
				<label>Был приглашен:</label>
				<div><b><a target="_blank" href="{referal-link}">{referal}</a></b></div>
			</div>
			[/referal]
			<div class="tr last">
				<label>Пригласил:</label>
				<div><b>{referers}</b></div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>