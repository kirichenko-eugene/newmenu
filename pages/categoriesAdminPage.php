<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Pagination.php';
require_once '../config/TagHelper.php';
require_once '../config/TableHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/CategoriesAdmin.php';
require_once '../config/ResizeImage.php';

$autorization = new Autorization;
$pagination = new Pagination;
$categories = new CategoriesAdmin;
$tag = new TagHelper;
$table = new TableHelper;
$form = new FormHelper;
$resizeImage = new ResizeImage;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Категории';
	$categories->createRootCategory();
	$categories->changeStatus();
	$categories->createCategory($dirCategory);
	$countElements = $categories->countAllCategories();
	$getAllCategories = $categories->getAllActiveCategories();
	$pagination->setPerPage(10);
	$pagination->setPagesCount($countElements);
	$pagination->setLinksNumber(7);
	$startPosition = $pagination->startPosition();
	$perPage = $pagination->getPerPage();
	$categoriesForTable = $categories->categoriesForTable($startPosition, $perPage);
	
	$content = $pagination->showPagination();
	$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
	$content .= $form->modalButton('Создать категорию', [
			'data-target' => '#modal-createCategory', 
			'class' => 'btn btn-primary']);
	$content .= $tag->close('div');
	$createCategory = '<div class="row justify-content-center m-1">';
	$createCategory .= $form->openForm(['method' => 'POST', 'enctype' => 'multipart/form-data']);
	$createCategory .= $form->input( 
			['class' => 'form-control', 
			'id' => 'name', 
			'aria-describedby' => 'name', 
			'name' => 'name',
			'autocomplete' => 'off', 
			'required' => true,
			'placeholder' => 'Новая категория'],
			'Название категории');

	$createCategory .= $form->input(
			['class' => 'form-control', 
			'id' => 'position', 
			'aria-describedby' => 'position', 
			'name' => 'position',
			'autocomplete' => 'off',
			'placeholder' => '(1 - начало списка)',
			'required' => true],
			'Позиция в меню');
	$selectAttr = ['name' => 'parent', 'class' => 'form-control'];
	$selectLabel = 'Родительская категория';
	$selectOptions[0] = ['text' => 'Выберите категорию', 'attrs' => ['selected' => 'true', 'value' => '']];
	foreach($getAllCategories as $category) {
				$selectOptions[] = [
					'text' => $category['name'], 
					'attrs' => ['value' => $category['id']]
				];
			}
	$createCategory .= $form->select($selectAttr, $selectOptions, $selectLabel);

	$createCategory .= $form->fileInput(
			['class' => 'form-control-file', 
			'id' => 'photo', 
			'aria-describedby' => 'photo', 
			'name' => 'photo',
			'required' => true],
			'Добавить фото');

	$createCategory .= $form->submit([
		'name' => 'submitCategory', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Создать'
		]);
	$createCategory .= $form->closeForm();
	$createCategory .= '</div>';

	$content .= $form->modalBody('createCategory', 'Создать категорию', $createCategory);

	$content .= $table->tableOpen();
	$content .= $table->tableHead([
		['thname' => 'Название'], 
		['thname' => 'Позиция'], 
		['thname' => 'Фото'], 
		['thname' => 'Родитель'], 
		['thname' => 'Статус'], 
		['thname' => 'Редактировать'], 
		['thname' => 'Отключить/Восстановить'] 
	]);
	$content .= $table->tbodyOpen();
	
	foreach($categoriesForTable as $key => $category) {

		$tdImg = $category['img'];
		$modalIdImgButton = '#modal-'. $category['id'];
		$modalButtonImg = $form->modalButton($tdImg, [
			'data-target' => $modalIdImgButton, 
			'class' => 'btn btn-secondary']);
		
		if ($tdImg != '') {
			$tdImg = $modalButtonImg;
			$imgPath = $site.''.$cdir.''.$category['img'];
			$categoryImage = "<img src=\"$imgPath\" class=\"img-fluid\" alt=\"{$category['name']}\">";
		}

		$textStatus = $categories->getStatus($category);
		$editLink = "<a href=\"{$site}pages/editCategoryAdminPage.php?id={$category['id']}\">Редактировать</a>";
		$changeStatusLink = "<a href=\"?changeStatus={$category['id']}&status={$category['status']}\">Отключить/восстановить</a>";

		$content .= $table->tableBody([
			['tdname' => $category['name']], 
			['tdname' => $category['weight']], 
			['tdname' => $tdImg], 
			['tdname' => $category['parent']], 
			['tdname' => $textStatus], 
			['tdname' => $editLink], 
			['tdname' => $changeStatusLink] 
		]);
		$content .= $form->modalBody($category['id'], $category['name'], $categoryImage);
	}
	$content .= $table->tbodyClose();
	$content .= $table->tableClose();
	$content .= '</div>';

	include '../elements/layoutAdmin.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}
