<!doctype html>
<head>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1, user-scalable=no" />
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo STATIC_PATH;?>style/skin/base/app_explorer.css?ver=<?php echo KOD_VERSION;?>"/>
	<title><?php echo $fileName;?></title>
	<style type="text/css">
	html{overflow:auto;}
	.pathinfo{width: 100%;}
	.pathinfo .p .title {width: 100px;font-weight: bold;}
	.pathinfo .p .content {width: 80%;}
	</style>
</head>
<body>
<div class="pathinfo">
	<?php foreach ($data as $key => $data) {?>
	<div class="p">
		<div class="title"><?=$data->title?>:</div>
		<div class="content">
			<?php
			if (!is_array($data->value)) {
				echo $data->value;
			}else{
				echo "<ol>";
				foreach ($data->value as $value) {
					echo "<li>".$value["name"]. " <small>(" .$value["size"].")</small></li>";
				}
				echo "</ol>";
			}
			?>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php }?>
</div>
</body>
</html>
