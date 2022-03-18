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
$cabinet = new Cabinet;
$autorization = new Autorization;

if($session->exists('autorizationOk')) {
	$autorization->toPage($site . 'pages/clientCabinet.php');
}

$title = 'Проверка пароля';
$content = '';

if (isset($_POST['fillAutorizationCode'])) {
	if (isset($_POST['code'])) {
		$cabinet->setLogin($session->get('phoneAutorization'));
		$cabinet->setAuthcode($_POST['code']);
		$cabinet->login();
	}
	
	if (stristr($session->get('curlLogin'), 'можно воспользоваться') == TRUE) {
		$content .= $form->openForm(['method' => 'POST', 'action' => '', 'id' => 'countdown', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto mt-2']);
		$content .= $tag->open('div', ['class' => 'text-center']);
		$content .= $tag->open('h5', ['class' => 'm-1']);
		$content .= str_replace('пароль', 'пароль.',$session->get('curlLogin'));
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
		
	} elseif (stristr($session->get('curlLogin'), 'Количество попыток ввода единоразового пароля израсходовано') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= str_replace('израсходовано', 'израсходовано.', $session->get('curlLogin'));
		$content .= $tag->close('h5');
		$content .= $tag->open('div', ['class' => 'text-center mb-2']);
		$content .= $tag->open('a', ['class' => 'a-button', 'href' => $site . 'pages/startCabinet.php']);
		$content .= 'Назад';
		$content .= $tag->close('a');
		$content .= $tag->close('div');
		$content .= $tag->close('div');

	} elseif (stristr($session->get('curlLogin'), 'Необходимо сгенерировать единоразовый') == TRUE) {
		$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
		$content .= $tag->open('h5', ['class' => 'text-center']);
		$content .= $session->get('curlLogin');
		$content .= $tag->close('h5');
		$content .= $tag->open('div', ['class' => 'text-center mb-2']);
		$content .= $tag->open('a', ['class' => 'a-button', 'href' => $site . 'pages/startCabinet.php']);
		$content .= 'Назад';
		$content .= $tag->close('a');
		$content .= $tag->close('div');
		$content .= $tag->close('div');

	} else {
		$session->set('autorizationOk', true);
		$autorization->toPage($site . 'pages/clientCabinet.php');
	}

} else {
	if ($session->get('phoneAutorization') != NULL) {
		if (stristr($session->get('curlAutorization'), 'code_timeout') == TRUE) {
			$content .= $form->openForm(['method' => 'POST', 'action' => '', 'id' => 'countdown', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);
			$content .= $tag->open('div', ['class' => 'text-center']);

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
				'name' => 'fillAutorizationCode', 
				'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
				'value' => 'Далее'
			]);
			$content .= $form->closeForm();
		} elseif (stristr($session->get('curlAutorization'), 'Необходимо сгенерировать единоразовый') == TRUE) {
			$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
			$content .= $tag->open('h5', ['class' => 'text-center']);
			$content .= $session->get('curlAutorization');
			$content .= $tag->close('h5');
			$content .= $tag->open('div', ['class' => 'text-center mb-2']);
			$content .= $tag->open('a', ['class' => 'a-button', 'href' => $site . 'pages/startCabinet.php']);
			$content .= 'Назад';
			$content .= $tag->close('a');
			$content .= $tag->close('div');
			$content .= $tag->close('div');
		} elseif (stristr($session->get('curlAutorization'), 'можно воспользоваться') == TRUE) {
			$cabinet->setLogin($session->get('phoneAutorization'));
			$cabinet->preLogin();

			if(stristr($session->get('curlAutorization'), 'Необходимо сгенерировать единоразовый') == TRUE) {
				$autorization->toPage($site . 'pages/startCabinet.php');
			}

			if(stristr($session->get('curlAutorization'), 'code_timeout') == TRUE) {
				$content .= $form->openForm(['method' => 'POST', 'action' => '', 'id' => 'countdown', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);
				$content .= $tag->open('div', ['class' => 'text-center']);

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
					'name' => 'fillAutorizationCode', 
					'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
					'value' => 'Далее'
				]);
				$content .= $form->closeForm();
			} else {
				$content .= $form->openForm(['method' => 'POST', 'action' => '', 'id' => 'countdown', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);
				$content .= $tag->open('div', ['class' => 'text-center']);
				$content .= $tag->open('h5');
				$content .= str_replace('пароль', 'пароль.', $session->get('curlAutorization'));
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
					'name' => 'fillAutorizationCode', 
					'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
					'value' => 'Далее'
				]);
				$content .= $form->closeForm();
			}

		} 
	} else {
		$autorization->toPage($site . 'pages/startCabinet.php');
	}
}

include '../elements/layoutCabinet.php';
