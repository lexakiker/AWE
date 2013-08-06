<div class="content">
				<h2>Изменение статической страницы</h2><br>
					<form action="" method="POST">
						[message]<p align="center">{message}</p>[/message]
						[form]
						<h3>Заголовок:</h3>
						<div class="clearfix" style="height: 10px;"></div>
						<input style="width: 260px;" type="text" name="title" value="{title}"><br>
						<h3>URL (адрес страницы, по примеру <i>/do/<u>URL</u></i>):</h3>
						<div class="clearfix" style="height: 10px;"></div>
						<input style="width: 260px;" type="text" name="url" value="{url}"><br>
						<h3>Содержимое страницы:</h3>
						<div class="clearfix" style="height: 10px;"></div>
						<textarea type="text" name="content" id="content">{content}</textarea>
						<div class="clearfix" style="height: 15px;"></div>
						<button name="editstatic" type="submit" class="btn btn-primary">Изменить</button>
						[/form]
					</form>
			</div>