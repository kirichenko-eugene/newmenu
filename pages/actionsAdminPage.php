<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Pagination.php';
require_once '../config/ActionsAdmin.php';
require_once '../config/TagHelper.php';
require_once '../config/TableHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/ResizeImage.php';

$autorization = new Autorization;
$actions = new ActionsAdmin;
$pagination = new Pagination;
$table = new TableHelper;
$form = new FormHelper;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Акции';
	$actions->changeStatus();
	$actions->createAction($dirAction);
	$countElements = $actions->countAllActions();
	$pagination->setPerPage(10);
	$pagination->setPagesCount($countElements);
	$pagination->setLinksNumber(7);
	$startPosition = $pagination->startPosition();
	$perPage = $pagination->getPerPage();
	$actionsForTable = $actions->actionsForTable($startPosition, $perPage);

	$content = $pagination->showPagination();
	$content .= $table->tableOpen();
	$content .= $table->tableHead([
		['thname' => 'Фото'], 
		['thname' => 'Позиция'], 
		['thname' => 'Статус'], 
		['thname' => 'Редактировать'],
		['thname' => 'Отключить/Восстановить'] 
	]);
	$content .= $table->tbodyOpen();

	foreach($actionsForTable as $key => $action) {
		$tdImg = $action['img'];
		$modalIdImgButton = '#modal-'. $action['id'];
		$modalButtonImg = $form->modalButton($tdImg, [
			'data-target' => $modalIdImgButton, 
			'class' => 'btn btn-secondary']);
		
		if ($tdImg != '') {
			$tdImg = $modalButtonImg;
			$imgPath = $site.''.$adir.''.$action['img'];
			$actionImage = "<img src=\"$imgPath\" class=\"img-fluid\" alt=\"{$action['img']}\">";
		}

		$textStatus = $actions->getStatus($action);
		$editLink = "<a href=\"{$site}pages/editActionAdminPage.php?id={$action['id']}\">Редактировать</a>";
		$changeStatusLink = "<a href=\"?changeStatus={$action['id']}&status={$action['status']}\">Отключить/восстановить</a>";

		$content .= $table->tableBody([
			['tdname' => $tdImg], 
			['tdname' => $action['weight']],			
			['tdname' => $textStatus], 
			['tdname' => $editLink], 
			['tdname' => $changeStatusLink] 
		]);
		$content .= $form->modalBody($action['id'], $action['img'], $actionImage);
	}

	$content .= $table->tbodyClose();
	$content .= $table->tableClose();
	$content .= '</div>';

	$content .= '<div class="row justify-content-center m-2"><h2>Добавить акцию</h2></div>';
	$content .= '<div class="row justify-content-center m-2">';
	$content .= $form->openForm(['method' => 'POST', 'enctype' => 'multipart/form-data']);
	$content .= $form->fileInput(
			['class' => 'form-control-file', 
			'id' => 'photo', 
			'aria-describedby' => 'photo', 
			'name' => 'photo',
			'required' => true],
			'Добавить фото');

	$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'position', 
			'aria-describedby' => 'position', 
			'name' => 'position',
			'autocomplete' => 'off',
			'placeholder' => '(1 - начало страницы)',
			'required' => true],
			'Позиция на странице');

	$content .= $form->submit([
		'name' => 'submitAction', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Создать'
		]);
	$content .= $form->closeForm();
	$content .= '</div>';

	include '../elements/layoutAdmin.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}
