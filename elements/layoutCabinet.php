<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$site?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=$site?>assets/css/style.css">
</head>
<body>

	<?php 
		include 'headerCabinet.php'; 
		include 'info.php';
	?>

	<div class="content">		
		<div class="body-main">
			<?=$content?>
		</div>
	</div>

	<?php 
		include 'footer.php';
		include 'modalButtons.php';
	?>

	<script src="<?=$site?>assets/js/jquery-3.5.1.min.js"></script>
	<script src="<?=$site?>assets/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript">
		$(function() {
		  $('[data-toggle="popover"]').popover({
		    trigger: 'focus'
		  }); 
		})
	</script>
</body>
</html>