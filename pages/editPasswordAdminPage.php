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
	$title = 'Смена пароля';
	$checkId = $users->checkUserId();
	$content = '<div class="row justify-content-center m-2"><h2>Изменить пароль</h2></div>';

	if($checkId) {
		$users->changePassword($site);
		$content .= '<div class="row justify-content-center m-2">';
		$content .= $form->openForm(['method' => 'POST']);
		$content .= $form->password(
			['class' => 'form-control', 
			'id' => 'password', 
			'aria-describedby' => 'password', 
			'name' => 'password',
			'required' => true], 
			'Введите новый пароль');
		$content .= $form->submit(
			['name' => 'submit', 
			'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
			'value' => 'Изменить',
			'required' => true]);
		$content .= $form->closeForm();
		$content .= '</div>';
	} else {
		$content .= '<div class="row justify-content-center m-2">Данный пользователь не найден</div>';
	}
		
	include '../elements/layoutAdmin.php';

} else {
	$autorization->toPage($site.'pages/login.php');
}