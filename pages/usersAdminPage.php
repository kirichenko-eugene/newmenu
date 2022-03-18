<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Pagination.php';
require_once '../config/Users.php';
require_once '../config/TagHelper.php';
require_once '../config/TableHelper.php';
require_once '../config/FormHelper.php';

$autorization = new Autorization;
$users = new Users;
$pagination = new Pagination;
$table = new TableHelper;
$form = new FormHelper;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Пользователи';
	$users->changeStatus();
	$users->createUser();
	$countElements = $users->countAllUsers();
	$pagination->setPerPage(10);
	$pagination->setPagesCount($countElements);
	$pagination->setLinksNumber(7);
	$startPosition = $pagination->startPosition();
	$perPage = $pagination->getPerPage();
	$usersForTable = $users->usersForTable($startPosition, $perPage);

	$content = $pagination->showPagination();
	$content .= $table->tableOpen();
	$content .= $table->tableHead([
		['thname' => 'Логин'], 
		['thname' => 'Пароль'], 
		['thname' => 'Статус'], 
		['thname' => 'Редактировать имя'],
		['thname' => 'Отключить/Восстановить'] 
	]);
	$content .= $table->tbodyOpen();
	
	foreach($usersForTable as $key => $user) {
		$textStatus = $users->getStatus($user);
		$passwordLink = "<a href=\"{$site}pages/editPasswordAdminPage.php?id={$user['id']}\">Новый пароль</a>";
		$editLink = "<a href=\"{$site}pages/editUserAdminPage.php?id={$user['id']}\">Редактировать</a>";
		$changeStatusLink = "<a href=\"?changeStatus={$user['id']}&status={$user['status']}\">Отключить/восстановить</a>";

		$content .= $table->tableBody([
			['tdname' => $user['name']], 
			['tdname' => $passwordLink], 			
			['tdname' => $textStatus], 
			['tdname' => $editLink], 
			['tdname' => $changeStatusLink] 
		]);
	}
	$content .= $table->tbodyClose();
	$content .= $table->tableClose();

	$content .= '<div class="row justify-content-center m-2"><h2>Создать пользователя</h2></div>';
	$content .= '<div class="row justify-content-center m-2">';
	$content .= $form->openForm(['method' => 'POST']);
	$content .= $form->input( 
			['class' => 'form-control', 
			'id' => 'name', 
			'aria-describedby' => 'name', 
			'name' => 'name', 
			'placeholder' => 'Новый логин', 
			'autocomplete' => 'off', 
			'required' => true],
			'Введите логин');
	$content .= $form->password(
			['class' => 'form-control', 
			'id' => 'password', 
			'aria-describedby' => 'password', 
			'name' => 'password',
			'autocomplete' => 'new-password', 
			'placeholder' => 'Пароль пользователя',  
			'required' => true],
			'Введите пароль');
	$content .= $form->submit([
		'name' => 'submit', 
		'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
		'value' => 'Создать'
		]);
	$content .= $form->closeForm();
	$content .= '</div>';
	$content .= ob_get_clean();
	$content .= '</div>';

	include '../elements/layoutAdmin.php';
} else {
	$autorization->toPage($site.'pages/login.php');
}