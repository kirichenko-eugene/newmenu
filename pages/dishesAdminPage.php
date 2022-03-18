<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Pagination.php';
require_once '../config/DishesAdmin.php';
require_once '../config/Properties.php';
require_once '../config/ResizeImage.php';
require_once '../config/TagHelper.php';
require_once '../config/TableHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/CategoriesAdmin.php';

$autorization = new Autorization;
$pagination = new Pagination;
$categories = new CategoriesAdmin;
$dishes = new DishesAdmin;
$table = new TableHelper;
$form = new FormHelper;
$tag = new TagHelper;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Блюда';
	$dishes->changeStatus();
	$countElements = $dishes->countAllDishes();
	$pagination->setPerPage(8);
	$pagination->setPagesCount($countElements);
	$pagination->setLinksNumber(7);
	$startPosition = $pagination->startPosition();
	$perPage = $pagination->getPerPage();
	$dishesForTable = $dishes->dishesForTable($startPosition, $perPage);
	$getAllCategories = $categories->getAllActiveCategories();

	$content = $pagination->showPagination();
	$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
	$content .= $form->openForm(['class' => 'form-inline']);
	$content .= $form->search([
		'placeholder' => 'Поиск блюда', 
		'class' => 'form-control',
		'id' => 'search'
	]);
	$content .= $form->closeForm();
	$content .= $tag->close('div');

	$content .= $tag->open('div', ['id' => 'display']);
	include 'tableForDishesPageAdmin.php';
	$content .= $tag->close('div');
	include '../elements/layoutAdmin.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}
