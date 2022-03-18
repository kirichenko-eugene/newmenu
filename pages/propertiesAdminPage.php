<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Pagination.php';
require_once '../config/TagHelper.php';
require_once '../config/TableHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/Properties.php';
require_once '../config/ResizeImage.php';

$autorization = new Autorization;
$pagination = new Pagination;
$properties = new Properties;
$tag = new TagHelper;
$table = new TableHelper;
$form = new FormHelper;
$resizeImage = new ResizeImage;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Свойства блюд';
	$properties->changeStatus();
	$properties->createProperty($dirProperty);
	$countElements = $properties->countAllProperties();
	$pagination->setPerPage(10);
	$pagination->setPagesCount($countElements);
	$pagination->setLinksNumber(7);
	$startPosition = $pagination->startPosition();
	$perPage = $pagination->getPerPage();
	$propertiesForTable = $properties->propertiesForTable($startPosition, $perPage);
	
	$content = $pagination->showPagination();
	
	$content .= $table->tableOpen();
	$content .= $table->tableHead([
		['thname' => 'Название'], 
		['thname' => 'Фото'], 
		['thname' => 'Статус'], 
		['thname' => 'Редактировать'], 
		['thname' => 'Отключить/Восстановить'] 
	]);
	$content .= $table->tbodyOpen();
	
	foreach($propertiesForTable as $key => $property) {

		$tdImg = $property['img'];
		$modalIdImgButton = '#modal-'. $property['id'];
		$modalButtonImg = $form->modalButton($tdImg, [
			'data-target' => $modalIdImgButton, 
			'class' => 'btn btn-secondary']);
		
		if ($tdImg != '') {
			$tdImg = $modalButtonImg;
			$imgPath = $site.''.$pdir.''.$property['img'];
			$categoryImage = "<img src=\"$imgPath\" class=\"img-fluid\" alt=\"{$property['name']}\">";
		}

		$textStatus = $properties->getStatus($property);
		$editLink = "<a href=\"{$site}pages/editPropertyAdminPage.php?id={$property['id']}\">Редактировать</a>";
		$changeStatusLink = "<a href=\"?changeStatus={$property['id']}&status={$property['status']}\">Отключить/восстановить</a>";

		$content .= $table->tableBody([
			['tdname' => $property['name']],  
			['tdname' => $tdImg], 
			['tdname' => $textStatus], 
			['tdname' => $editLink], 
			['tdname' => $changeStatusLink] 
		]);
		$content .= $form->modalBody($property['id'], $property['name'], $categoryImage);
	}
	$content .= $table->tbodyClose();
	$content .= $table->tableClose();
	$content .= '</div>';

	$content .= '<div class="row justify-content-center m-2"><h2>Добавить свойство блюда</h2></div>';
	$content .= '<div class="row justify-content-center m-2">';
	$content .= $form->openForm(['method' => 'POST', 'enctype' => 'multipart/form-data']);
	$content .= $form->input( 
			['class' => 'form-control', 
			'id' => 'name', 
			'aria-describedby' => 'name', 
			'name' => 'name', 
			'placeholder' => 'Название свойства блюда', 
			'autocomplete' => 'off', 
			'required' => true],
			'Свойство блюда');

	$content .= $form->fileInput(
			['class' => 'form-control-file', 
			'id' => 'photo', 
			'aria-describedby' => 'photo', 
			'name' => 'photo',
			'required' => true],
			'Добавить иконку в формате SVG!');

	$content .= $form->submit([
		'name' => 'submitProperty', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Создать'
		]);
	$content .= $form->closeForm();
	$content .= '</div>';

	include '../elements/layoutAdmin.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}
