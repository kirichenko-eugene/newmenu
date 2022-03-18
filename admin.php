<?php
include 'config/config.php';
require_once 'config/Autorization.php';
require_once 'config/Menu.php';
require_once 'config/DishesAdmin.php';
require_once 'config/Properties.php';
require_once 'config/ResizeImage.php';
require_once 'config/TagHelper.php';
require_once 'config/TableHelper.php';
require_once 'config/FormHelper.php';
require_once 'config/CategoriesAdmin.php';

$autorization = new Autorization;
$menu = new Menu;
$dishes = new DishesAdmin;
$categories = new CategoriesAdmin;
$table = new TableHelper;
$form = new FormHelper;
$tag = new TagHelper;

if($autorization->noEmptyAuth($session->get('auth'))) {

	$title = 'Админка Goodcity';
	$dishes->changeStatus();

	if(empty($_GET['category']) OR ($_GET['category'] <= 0)) {
		$_GET['category'] = 1;
	} else {
		$_GET['category'] = (int) $_GET['category'];
	}

	$content = '';
	$content .= $tag->open('div', ['class' => 'row m-0']);
	$content .= $tag->open('div', ['class' => 'col-3']);
	$content .= $form->openForm(['method' => 'POST']);
	$content .= $form->submit([
		'name' => 'importMenu', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Импорт меню'
	]);
	$content .= $form->closeForm();
	$content .= $menu->getCategories($site);
	$content .= $tag->close('div');
	$content .= $tag->open('div', ['class' => 'col m-0']);
	$dishesForTable = $dishes->dishesForTableById($menu->getRoot());
	$getAllCategories = $categories->getAllActiveCategories();
	include 'pages/tableForDishesPageAdmin.php';
	$content .= $tag->close('div');
	$content .= $tag->close('div');

	if (isset($_POST['importMenu'])) {
        $command = escapeshellcmd($importLink);
        $message = shell_exec($command);
        echo $message;
		$messageData = ['text' => 'Импорт блюд выполнен успешно!', 'status' => 'success'];
		$session->set('message', $messageData);
	}

	include 'elements/layoutAdmin.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}
