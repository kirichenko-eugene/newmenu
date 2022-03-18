<?php 

include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/Cabinet.php';
require_once '../config/Service.php';

$tag = new TagHelper;
$form = new FormHelper;
$licence = new Service;
$cabinet = new Cabinet;
$autorization = new Autorization;

if($session->exists('autorizationOk')) {
	$autorization->toPage($site . 'pages/clientCabinet.php');
}

$title = 'Проверка авторизации';

$content = '';

if (isset($_POST['fillInfo'])) {
	if (isset($_POST['surname'])) {
		$session->set('surname', $_POST['surname']);
	}
	if (isset($_POST['name'])) {
		$session->set('name', $_POST['name']);
	}
	if (isset($_POST['patronymic'])) {
		$session->set('patronymic', $_POST['patronymic']);
	}
	if (isset($_POST['marrital'])) {
		$session->set('marrital', $_POST['marrital']);
	}
	if (isset($_POST['dob'])) {
		$session->set('dob', $_POST['dob']);
	}
	if (isset($_POST['sex'])) {
		$session->set('sex', $_POST['sex']);
	}
	if (isset($_POST['phone'])) {
		$session->set('phone', $_POST['phone']);
	}
}

$cabinet->setSurname($session->get('surname'));
$cabinet->setName($session->get('name'));
$cabinet->setPatronymic($session->get('patronymic'));
$cabinet->setMarrital($session->get('marrital'));
$cabinet->setBirth($session->get('dob'));
$cabinet->setGender($session->get('sex'));
$cabinet->setLogin($session->get('phone'));

if (isset($_POST['fillCode'])) {
	if (isset($_POST['code'])) {
		$cabinet->setAuthcode($_POST['code']);
	}
	$cabinet->registration();

	if (stristr($session->get('curlAnswer'), 'можно воспользоваться') == TRUE) {
		$content .= $form->openForm(['method' => 'POST', 'action' => '', 'id' => 'countdown', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);
		$content .= $tag->open('div', ['class' => 'text-center']);
		$content .= $tag->open('h5');
		$content .= str_replace('пароль', 'пароль.',$session->get('curlAnswer'));
		$content .= $tag->close('h5');
		$content .= $tag->close('div');

		$content .= $form->number(
			['class' => 'form-control', 
			'id' => 'code', 
			'aria-describedby' => 'code', 
			'name' => 'code', 
			'autocomplete' => 'off', 
			'placeholder' => 'Код из сообщения',
			'required' => true],
			'Введите код из сообщения');

		$content .= $form->submit([
			'name' => 'fillCode', 
			'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
			'value' => 'Далее'
		]);
		$content .= $form->closeForm(); 
		
	} elseif (stristr($session->get('curlAnswer'), 'Количество попыток ввода единоразового пароля израсходовано') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= str_replace('израсходовано', 'израсходовано.', $session->get('curlAnswer'));
		$content .= $tag->close('h5');
		$content .= $tag->open('div', ['class' => 'text-center mb-2']);
		$content .= $tag->open('a', ['class' => 'a-button', 'href' => $site . 'pages/startCabinet.php']);
		$content .= 'Назад';
		$content .= $tag->close('a');
		$content .= $tag->close('div');
		$content .= $tag->close('div');

	} elseif (stristr($session->get('curlAnswer'), 'Необходимо сгенерировать единоразовый') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= $session->get('curlAnswer');
		$content .= $tag->close('h5');
		$content .= $tag->open('div', ['class' => 'text-center mb-2']);
		$content .= $tag->open('a', ['class' => 'a-button', 'href' => $site . 'pages/startCabinet.php']);
		$content .= 'Назад';
		$content .= $tag->close('a');
		$content .= $tag->close('div');
		$content .= $tag->close('div');

	} else {
		$session->del('surname');
		$session->del('name');
		$session->del('patronymic');
		$session->del('marrital');
		$session->del('dob');
		$session->del('sex');
		$session->del('phone');

		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= "Вы зарегистрированы в бонусной программе GoodCity!";
		$content .= $tag->close('h5');
		$content .= $tag->open('div', ['class' => 'text-center mb-2']);
		$content .= $tag->open('a', ['class' => 'a-button', 'href' => $site . 'pages/startCabinet.php']);
		$content .= 'Назад';
		$content .= $tag->close('a');
		$content .= $tag->close('div');
		$content .= $tag->close('div');
	}
	
} else {
	if (stristr($cabinet->preRegistration(), 'Заведение одного и того же номера более чем') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= "Номер телефона {$cabinet->getLogin()} уже встречается в списках контактов. Заведение одного и того же номера более чем 1 раз(а) запрещено. Обратитесь к администратору.";
		$content .= $tag->close('h5');
		$content .= $tag->open('div', ['class' => 'text-center mb-2']);
		$content .= $tag->open('a', ['class' => 'a-button', 'href' => $site . 'pages/startCabinet.php']);
		$content .= 'Назад';
		$content .= $tag->close('a');
		$content .= $tag->close('div');
		$content .= $tag->close('div');
	} elseif (stristr($cabinet->preRegistration(), 'Not Found') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= 'Нет связи с сервером. Попробуйте повторить позднее.';
		$content .= $tag->close('h5');
		$content .= $tag->close('div');
	} elseif (stristr($cabinet->preRegistration(), 'Контакт "" не обнаружен') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= 'Поле с телефоном не заполнено';
		$content .= $tag->close('h5');
		$content .= $tag->open('div', ['class' => 'text-center mb-2']);
		$content .= $tag->open('a', ['class' => 'a-button', 'href' => $site . 'pages/startCabinet.php']);
		$content .= 'Назад';
		$content .= $tag->close('a');
		$content .= $tag->close('div');
		$content .= $tag->close('div');
	} else {
		$content .= $form->openForm(['method' => 'POST', 'action' => '', 'id' => 'countdown', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);

		$content .= $tag->open('div', ['class' => 'text-center']);
		$content .= $tag->open('h5');
		$content .= str_replace('пароль', 'пароль.', $cabinet->preRegistration());
		$content .= $tag->close('h5');
		$content .= $tag->close('div');

		$content .= $form->number(
			['class' => 'form-control', 
			'id' => 'code', 
			'aria-describedby' => 'code', 
			'name' => 'code', 
			'autocomplete' => 'off', 
			'placeholder' => 'Код из сообщения',
			'required' => true],
			'Введите код из сообщения');

		$content .= $form->submit([
			'name' => 'fillCode', 
			'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
			'value' => 'Далее'
		]);
		$content .= $form->closeForm(); 
	}
}




include '../elements/layoutCabinet.php';
?>
