<?php 
function menuItem($site, $link, $name)
{
	$fileName = basename($_SERVER['SCRIPT_FILENAME']);
	$activeLink = '';

	if($fileName == $link) {
		$activeLink = ' active';
	} 

	echo "<li class=\"nav-item{$activeLink} mt-auto mb-auto\">
	<a class=\"nav-link\" href=\"{$site}pages/{$link}\"><h5>$name</h5></a>
	</li>";
}
?>

<header class="row justify-content-center m-2 bg-secondary">
	<nav class="navbar navbar-expand-lg navbar-dark  bg-secondary">
		<a class="navbar-brand" href="<?=$site?>admin.php"><h3>Меню GoodCity</h3></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
				<?=menuItem($site, 'usersAdminPage.php', 'Пользователи');?>
				<?=menuItem($site, 'categoriesAdminPage.php', 'Категории');?>
				<?=menuItem($site, 'dishesAdminPage.php', 'Блюда');?>
				<?=menuItem($site, 'propertiesAdminPage.php', 'Свойства блюд');?>
				<?=menuItem($site, 'actionsAdminPage.php', 'Акции');?>
				<?=menuItem($site, 'logout.php', 'Выход');?>
			</ul>
		</div>
	</nav>
</header>
