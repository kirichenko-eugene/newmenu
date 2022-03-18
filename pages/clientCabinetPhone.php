<?php 

include '../config/config.php';
require_once '../config/Service.php';
require_once '../config/Autorization.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/Cabinet.php';

$tag = new TagHelper;
$form = new FormHelper;
$licence = new Service;
$autorization = new Autorization;
$cabinet = new Cabinet;

$title = 'Персональные данные';

if (isset($_POST['exitCrm'])) {
	$session->del('autorizationOk');
	$session->del('phoneAutorization');
	$autorization->toPage($site.'pages/startCabinet.php');
}

if($autorization->noEmptyAuth($session->get('autorizationOk'))) {
	
	$holder = $cabinet->getHolderId();
	$prepareResult = $cabinet->getPersonalInfoByHolderId($holder);

	if (isset($_POST['delBtn'])) {
		$cabinet->deletePhoneInfo($prepareResult, $_POST);	
	}
	
	if (isset($_POST['editPhone'])) {
		$cabinet->preparePhoneInfo($prepareResult, $_POST);
	}

	$result = $cabinet->getInfoByHolderId($holder);
	
	$content = '';
	$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
	$content .= $form->openForm(['method' => 'POST', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);
	$content .= $form->submit([
		'name' => 'exitCrm', 
		'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
		'value' => 'Выйти'
	]);
	$content .= $form->closeForm();

	$content .= $form->openForm(['method' => 'POST', 'action' => '', 'class' => 'col-12 col-sm-12 col-md-12 ml-auto mr-auto']);
	if (count($cabinet->showPhone($result)) != '0') {
		foreach ($cabinet->showPhone($result) as $value) {
			foreach ($value as $key => $phoneNumber) {
				$content .= $tag->open('div', ['class' => 'col-12 col-sm-12 col-md-12 ml-auto mr-auto row justify-content-center m-2 flex-row pr-0 pl-0']);
				$content .= $form->phone(
					['class' => 'form-control', 
					'id' => $key, 
					'aria-describedby' => $key, 
					'name' => $key,  
					'autocomplete' => 'off', 
					'value' => $phoneNumber], 
					'');

				$content .= $tag->open('div', ['class' => 'mt-auto mb-auto m-1']);
				$content .= $tag->open('button', ['class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 'value' => $key, 'name' => 'delBtn']);
				$content .= '-';
				$content .= $tag->close('button');
				$content .= $tag->close('div');
				$content .= $tag->close('div');
			}
		}
	}

	$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-row']);
	$content .= $form->phone(
		['class' => 'form-control', 
		'id' => 'newNumber', 
		'aria-describedby' => 'newNumber', 
		'name' => 'newNumber', 
		'min-lenght' => 10,  
		'autocomplete' => 'off', 
		'placeholder' => 'Добавить новый номер'], 
		'Добавить телефон');
	$content .= $tag->close('div');


	$content .= $tag->open('div', ['class' => 'd-flex flex-row justify-content-center']);
	$content .= $tag->open('div', ['class' => 'mt-auto mb-auto m-1']);
	$content .= $tag->open('a',['href' => $site.'pages/personalInfoCabinet.php', 'class' => 'btn btn-secondary']);
	$content .= 'Назад';
	$content .= $tag->close('a');
	$content .= $tag->close('div');
	$content .= $tag->open('div', ['class' => 'mt-auto mb-auto m-1']);
	$content .= $form->submit([
		'name' => 'editPhone', 
		'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
		'value' => 'Сохранить изменения'
	]);
	$content .= $tag->close('div');
	$content .= $tag->close('div');
	
	$content .= $form->closeForm();

	$content .= $tag->close('div');

	include '../elements/layoutCabinet.php';
} else {
	$autorization->toPage($site.'pages/startCabinet.php');
}