<?php use Lightning\ViteHelper; ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Lightning 3</title>
		<?php ViteHelper::vite('main.js'); ?>
	</head>
	<body>
		<div id="app"></div>
		<?php ViteHelper::jsTag('main.js'); ?>
	</body>
</html>
