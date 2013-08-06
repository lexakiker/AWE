<!DOCTYPE html>
<html>
	<head>
		{headers}
		<link rel="stylesheet" href="{THEME}/css/styles.css">
		<link rel="shortcut icon" href="{THEME}/images/favicon.ico">
		<script type="text/javascript">
			$('window').load(function() {
				$('body').removeClass('preload');
			});
		</script>
	</head>
	<body class="preload">
		<div class="wrapper-border">
			<div class="wrapper">
				<h1>{title}</h1><br>
				[message]<p align="center">{message}</p><br>[/message]
				{content}
			</div>
		</div>
	</body>
</html>