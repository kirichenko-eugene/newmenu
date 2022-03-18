<?php
include 'config/config.php';
require_once 'config/Service.php';
require_once 'config/Categories.php';
require_once 'config/Dishes.php';
require_once 'config/Properties.php';
require_once 'config/Autorization.php';
require_once 'config/ResizeImage.php';

$title = 'Меню Goodcity';
$licence = new Service;
$categories = new Categories;
$dishes = new Dishes;
$rootCategories = $categories->getCategories();

$content = '';
if ((isset($_GET['lic']) or isset($_SESSION['lic'])) and (isset($_GET['table']) or isset($_SESSION['table']))) {
	foreach($rootCategories as $category) {
		$root_parent = $category['id']; 
		$root_image = $category['img'];
		if ($root_image != '') {
			$image = $root_image;
		} else {
			$image = 'no_category.jpg';
		}

		$content .= "<a class=\"picture-block\" style=\"background: url({$site}img/categories/$image)\" href=\"{$site}index.php?category={$category['id']}\"><div class=\"picture-block-cover\"></div><span class=\"picture-text\">{$category['name']}</span></a>"; 
	}

	$dishList = $dishes->allDishes($categories->getRoot());
	include 'pages/dishesPage.php';
} else {
	$content .= '<div class="row justify-content-center m-2"><h3 class="text-center">Пожалуйста, отсканируйте QR-код</h3></div>';
}


include 'elements/layout.php';
?>