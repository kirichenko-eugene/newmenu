<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Users.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';

$autorization = new Autorization;
$users = new Users;
$form = new FormHelper;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Редактировать имя';
	$checkId = $users->checkUserId();
	$content = '<div class="row justify-content-center m-2"><h2>Редактировать имя</h2></div>';

	if($checkId) {
		if (isset($_POST['name'])) {
			$name = htmlspecialchars($_POST['name']);
		} else {
			$name = $checkId[0]['name'];
		}
		$users->changeUserName($site);
		$content .= '<div class="row justify-content-center m-2">';
		$content .= $form->openForm(['method' => 'POST']);
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'name', 
			'aria-describedby' => 'name', 
			'name' => 'name',
			'value' => $name, 
			'required' => true],
			'Введите новое имя');
		$content .= $form->submit(
			['name' => 'submit', 
			'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
			'value' => 'Изменить']);
		$content .= $form->closeForm();
		$content .= '</div>';
		$content .= ob_get_clean();
	} else {
		$content .= '<div class="row justify-content-center m-2">Данный пользователь не найден</div>';
	}
		
	include '../elements/layoutAdmin.php';

} else {
	$autorization->toPage($site.'pages/login.php');
}