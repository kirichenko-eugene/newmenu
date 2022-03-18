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
	
	if (isset($_POST['fillInfo'])) {
		$holder = $cabinet->getHolderId();
		$prepareResult = $cabinet->getPersonalInfoByHolderId($holder);
		$cabinet->setName($_POST['name']);
		$cabinet->setSurname($_POST['surname']);
		$cabinet->setPatronymic($_POST['patronymic']);
		$cabinet->setGender($_POST['sex']);
		$cabinet->setMarrital($_POST['marrital']);
		$name = $cabinet->getName();
		$surname = $cabinet->getSurname();
		$patronymic = $cabinet->getPatronymic();
		$gender = $cabinet->getGender();
		$marrital = $cabinet->getMarrital();
		$cabinet->prepareHolderInfo($prepareResult, $name, $surname, $patronymic, $gender, $marrital);
	}

	$result = $cabinet->getInfoByHolderId($cabinet->getHolderId());
	
	$content = '';
	$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
	$content .= $form->openForm(['method' => 'POST', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);
	$content .= $form->submit([
		'name' => 'exitCrm', 
		'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
		'value' => 'Выйти'
	]);
	$content .= $form->closeForm();

	$content .= $form->openForm(['method' => 'POST', 'action' => '', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);

	$content .= $form->input(
		['class' => 'form-control', 
		'id' => 'surname', 
		'aria-describedby' => 'surname', 
		'name' => 'surname', 
		'autocomplete' => 'off', 
		'placeholder' => 'Ваша фамилия', 
		'value' => $cabinet->showSurname($result), 
		'required' => true],
		'Фамилия');

	$content .= $form->input(
		['class' => 'form-control', 
		'id' => 'name', 
		'aria-describedby' => 'name', 
		'name' => 'name', 
		'autocomplete' => 'off', 
		'placeholder' => 'Ваше имя', 
		'value' => $cabinet->showName($result),
		'required' => true],
		'Имя');

	$content .= $form->input(
		['class' => 'form-control', 
		'id' => 'patronymic', 
		'aria-describedby' => 'patronymic', 
		'name' => 'patronymic', 
		'autocomplete' => 'off', 
		'placeholder' => 'Вашe отчество', 
		'value' => $cabinet->showPatronymic($result), 
		'required' => true],
		'Отчество');

	$content .= $tag->open('hr');

	$content .= $form->date(
		['class' => 'form-control', 
		'id' => 'dob', 
		'aria-describedby' => 'dob', 
		'name' => 'dob',
		'autocomplete' => 'off', 
		'tabindex' => '-1', 
		'style' => 'pointer-events: none;', 
		'readonly' => 'true', 
		'value' => $cabinet->showBirth($result),  
		'required' => true], 
		'Дата рождения');

	$content .= $tag->open('div', ['class' => 'alert alert-secondary text-justify', 'role' => 'alert']);
	$content .= 'Вашу дату рождения может изменить оператор колл-центра (звоните 606)';
	$content .= $tag->close('div');

	$content .= '<div><label class="mb-0" for="male">Пол</label></div>
				<div class="form-check form-check-inline">';

	if($cabinet->showGender($result) == 'Male') {
		$radioGender = 'checked';
	} else {
		$radioGender = '';
	}

	$content .= "<input class=\"form-check-input\" type=\"radio\" name=\"sex\" id=\"male\" value=\"Male\" $radioGender>";		
	$content .= '<label class="form-check-label" for="male">Мужской</label>
				</div>
				<div class="form-check form-check-inline">';

	if($cabinet->showGender($result) == 'Female') {
		$radioGender = 'checked';
	} else {
		$radioGender = '';
	}

	$content .= "<input class=\"form-check-input\" type=\"radio\" name=\"sex\" id=\"female\" value=\"Female\" $radioGender>";

	$content .= '<label class="form-check-label" for="female">Женский</label>
				</div>
				<div><label class="mb-0"s for="marrital">Женат/замужем</label></div>
				<div class="form-check form-check-inline">';

	if($cabinet->showMarrital($result) == 'Yes') {
		$radioMarrital = 'checked';
	} else {
		$radioMarrital = '';
	}

	$content .= "<input class=\"form-check-input\" type=\"radio\" name=\"marrital\" id=\"marrital\" value=\"Yes\" $radioMarrital>";
	$content .= '<label class="form-check-label" for="marrital">Да</label>
				</div>
				<div class="form-check form-check-inline">';

	if($cabinet->showMarrital($result) == 'No') {
		$radioMarrital = 'checked';
	} else {
		$radioMarrital = '';
	}

	$content .= "<input class=\"form-check-input\" type=\"radio\" name=\"marrital\" id=\"no_marrital\" value=\"No\" $radioMarrital>";
	$content .= '<label class="form-check-label" for="no_marrital">Нет</label>
	</div>';

	$content .= $tag->open('hr');

	$content .= $tag->open('div', ['class' => 'd-flex flex-row justify-content-center m-1 mb-2']);
	$content .= $tag->open('div', ['class' => 'mt-auto mb-auto m-1']);
	$content .= $tag->open('a',['href' => $site.'pages/clientCabinetPhone.php', 'class' => 'btn btn-secondary']);
	$content .= 'Добавить/изменить телефон';
	$content .= $tag->close('a');
	$content .= $tag->close('div');
	$content .= $tag->close('div');

	$content .= $tag->open('div', ['class' => 'd-flex flex-row justify-content-center']);
	$content .= $tag->open('div', ['class' => 'mt-auto mb-auto m-1']);
	$content .= $tag->open('a',['href' => $site.'pages/clientCabinet.php', 'class' => 'btn btn-secondary']);
	$content .= 'Назад';
	$content .= $tag->close('a');
	$content .= $tag->close('div');
	$content .= $tag->open('div', ['class' => 'mt-auto mb-auto m-1']);
	$content .= $form->submit([
		'name' => 'fillInfo', 
		'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
		'value' => 'Сохранить'
	]);
	$content .= $tag->close('div');
	$content .= $tag->close('div');
	
	$content .= $form->closeForm();

	$content .= $tag->close('div');

	include '../elements/layoutCabinet.php';
} else {
	$autorization->toPage($site.'pages/startCabinet.php');
}