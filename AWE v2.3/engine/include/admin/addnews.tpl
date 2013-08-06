<div class="content">
				<h2>Добавление новости</h2><br>
					<form action="" method="POST">
						[message]<p align="center">{message}</p>[/message]
						<h3>Заголовок:</h3>
						<div class="clearfix" style="height: 10px;"></div>
						<input style="width: 260px;" type="text" name="title" value="{title}"><br>
						<h3>Краткая новость:</h3>
						<div class="clearfix" style="height: 10px;"></div>
						<textarea type="text" name="shortstory" id="shortstory">{shortstory}</textarea><br>
						<h3>Полная новость:</h3>
						<div class="clearfix" style="height: 10px;"></div>
						<textarea type="text" name="fullstory" id="fullstory">{fullstory}</textarea><br>
						<h3>Превью-картинка:</h3>
						<div class="clearfix" style="height: 10px;"></div>
						<i>*оставьте поле пустым, если не хотите использовать превью-картинку</i><br>
						<i>*вводить надо полный путь к картинке <b>вместе с http://</b>, ширина - <b>{width}</b>, высота - <b>{height}</b></i>
						<div class="clearfix" style="height: 10px;"></div>
						<input style="width: 260px;" type="text" name="ximage" value="{ximage}">
						<div class="clearfix" style="height: 15px;"></div>
						<button name="addnews" type="submit" class="btn btn-primary">Добавить</button>
					</form>
			</div>