<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$site?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=$site?>assets/css/jquery.fancybox.css">
	<link rel="stylesheet" href="<?=$site?>assets/css/style.css">

	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
		(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
			m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
		(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

		ym(87463140, "init", {
			clickmap:true,
			trackLinks:true,
			accurateTrackBounce:true,
			webvisor:true
		});
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/87463140" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
	
</head>
<body>
	<div class="content">
		
		<div class="body-main">
			<?=$content?>
		</div>
		
		<?php include 'footer.php';
		include 'modalButtons.php'; ?>
	</div>
	<script src="<?=$site?>assets/js/jquery-3.5.1.min.js"></script>
	<script src="<?=$site?>assets/js/bootstrap.bundle.min.js"></script>
	<script src="<?=$site?>assets/js/jquery.fancybox.js"></script>
	<script src="<?=$site?>assets/js/golos.js"></script>
	<script type="text/javascript">
		$(function() {
			$('[data-toggle="popover"]').popover({
				trigger: 'focus'
			}); 
		})
	</script>
</body>
</html>