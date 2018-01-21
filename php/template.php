<!doctype html>
<head>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1, user-scalable=no" />
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo STATIC_PATH;?>style/skin/base/app_explorer.css?ver=<?php echo KOD_VERSION;?>"/>
	<title><?php echo $fileName;?></title>
	<style type="text/css">
	.pathinfo{width: 100%;}
	.pathinfo .p .title {width: 100px;font-weight: bold;}
	.pathinfo .p .content {width: 80%;}
	</style>
</head>
<body>
<div class="pathinfo">
	<?php foreach ($data as $key => $value) {?>
	<div class="p">
		<div class="title"><?=$key?>:</div>
		<div class="content">
			<?php
			if (!is_array($value)) {
				echo $value;
			}else{
				echo "<ol>";
				foreach ($value as $svalue) {
					echo "<li>".$svalue["name"]. " <small>(" .$svalue["size"].")</small></li>";
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