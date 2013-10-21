<div class="content-container">
	<div class="content-title">
		<div class="left"><b>№{numb}</b> - {title}</div>
		[admin]<div class="right">
			<span class="edit"><a><div class="pencil-icon"></div></a>
				<ul class="dropdown">
					<a href="{edit-link}"><li>Изменить новость</li></a>
					<a href="{nullify-link}"><li>Обнулить просмотры</li></a>
					<a href="{delete-link}"><li>Удалить новость</li></a>
				</ul>
			</span>
		</div>[/admin]
		<div class="right"><b class="news-date">{date}</b></div>
		<div class="clearfix"></div>
	</div>
	<div class="content-text">
		[with-x-image]
		<div class="news-left"><img class="img-polaroid" src="{x-image}" style="height: {x-img-height}; width: {x-img-width};" height="{x-img-height}" width="{x-img-width}" alt="X-Image"></div>
		<div class="news-right">{full-story}</div>
		<div class="clearfix"></div>
		[/with-x-image]
		[without-x-image]
		<div class="news-text">{full-story}</div>
		[/without-x-image]
	</div>
	<div class="content-footer">
		<div class="left">Просмотров: <b>{views}</b> | Комментариев: <b>{comments}</b> | <a target="_blank" href="{author-link}">{author}</a></div>
		<div class="right no-padding"><a href="{full-link}" class="button">Читать полностью</a></div>
		<div class="clearfix"></div>
	</div>
</div>