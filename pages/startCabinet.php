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

if($session->exists('autorizationOk')) {
	$autorization->toPage($site . 'pages/clientCabinet.php');
}

$title = 'Добро пожаловать';

$content = '';
$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
$content .= $tag->open('div', ['class' => 'text-center']);
$content .= $tag->open('h4');
$content .= 'Вход в программу лояльности';
$content .= $tag->close('h4');
$content .= $tag->close('div');

$content .= $form->openForm(['method' => 'POST', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto mt-2']);
$content .= $form->phone(
	['class' => 'form-control', 
	'id' => 'phone', 
	'aria-describedby' => 'phone', 
	'name' => 'phone', 
	'minlength' => '10', 
	'autocomplete' => 'off', 
	'placeholder' => 'Ваш номер телефона',
	'required' => true],
	'');
$content .= $form->submit([
	'name' => 'loginCrm', 
	'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
	'value' => 'Войти'
]);
$content .= $form->closeForm();
$content .= $tag->close('div');

$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
$content .= $tag->open('h6');
$content .= "Нет учетной записи? <a href=\"{$site}pages/getRegistration.php\">Создайте ее!</a>";
$content .= $tag->close('h6');
$content .= $tag->close('div');


if (isset($_POST['loginCrm'])) {
	$cabinet->setLogin($_POST['phone']);
	$cabinet->preLogin();

	if (stristr($session->get('curlAutorization'), 'не обнаружен') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= $session->get('curlAutorization');
		$content .= $tag->close('h5');
		$content .= $tag->close('div');
	} elseif (stristr($session->get('curlAutorization'), 'Ошибочно введен') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= $session->get('curlAutorization');
		$content .= $tag->close('h5');
		$content .= $tag->close('div');
	} elseif (stristr($session->get('curlAutorization'), 'Not Found') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= 'Нет связи с сервером. Попробуйте повторить позднее.';
		$content .= $tag->close('h5');
		$content .= $tag->close('div');
	} else {
		$cabinet->preLogin();
		$session->set('phoneAutorization', $_POST['phone']);
		$autorization->toPage($site . 'pages/checkAutorization.php');
	}
}

include '../elements/layoutCabinet.php';
