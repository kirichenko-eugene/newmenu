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
$autorization = new Autorization;

if($session->exists('autorizationOk')) {
	$autorization->toPage($site . 'pages/clientCabinet.php');
}

$title = 'Добро пожаловать';
$content = '';
$content .= $form->openForm(['method' => 'POST', 'action' => $site.'pages/endRegistration.php', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);

$content .= $form->phone(
	['class' => 'form-control', 
	'id' => 'phone', 
	'aria-describedby' => 'phone', 
	'name' => 'phone', 
	'minlength' => '10', 
	'autocomplete' => 'off', 
	'placeholder' => 'Ваш номер телефона',
	'required' => true],
	'Введите телефон для регистрации');

$content .= $form->input(
	['class' => 'form-control', 
	'id' => 'surname', 
	'aria-describedby' => 'surname', 
	'name' => 'surname', 
	'autocomplete' => 'off', 
	'placeholder' => 'Ваша фамилия',
	'required' => true],
	'Фамилия');

$content .= $form->input(
	['class' => 'form-control', 
	'id' => 'name', 
	'aria-describedby' => 'name', 
	'name' => 'name', 
	'autocomplete' => 'off', 
	'placeholder' => 'Ваше имя',
	'required' => true],
	'Имя');

$content .= $form->input(
	['class' => 'form-control', 
	'id' => 'patronymic', 
	'aria-describedby' => 'patronymic', 
	'name' => 'patronymic', 
	'autocomplete' => 'off', 
	'placeholder' => 'Вашe отчество',
	'required' => true],
	'Отчество');

$content .= $form->date(
			['class' => 'form-control', 
			'id' => 'dob', 
			'aria-describedby' => 'dob', 
			'name' => 'dob',
			'autocomplete' => 'off',
			'required' => true],
			'Дата рождения');

$content .= $tag->open('hr', ['class' => 'my-1']);

$content .= '<div><label class="mb-0" for="male">Выберите пол</label></div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="sex" id="male" value="Male" checked>
						<label class="form-check-label" for="male">Мужской</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="sex" id="female" value="Female">
						<label class="form-check-label" for="female">Женский</label>
					</div>
					<div><label class="mb-0"s for="marrital">Женат/замужем</label></div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="marrital" id="marrital" value="Yes">
						<label class="form-check-label" for="marrital">Да</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="marrital" id="no_marrital" value="No" checked>
						<label class="form-check-label" for="no_marrital">Нет</label>
					</div>';

$content .= $tag->open('hr', ['class' => 'my-1']);

$content .= $form->checkbox(
			['class' => 'form-check-input',
			'name' => 'agree',
			'id' => 'agree', 
			'required' => 'true',],  
			'Я согласен с </label><u data-toggle="modal" data-target="#terms"> условиями регистрации</u>'
		);

$content .= $form->submit([
	'name' => 'fillInfo', 
	'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
	'value' => 'Далее'
]);
$content .= $form->closeForm();

$content .=	'<div class="modal fade" id="terms" tabindex="-1" role="dialog" aria-labelledby="termsLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="termsLabel">Условия регистрации</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <p class="text-justify">Даю свое согласие на хранение и обработку своих персональных данных (имени, номера телефона) и на получение материалов рекламного и/или информационного характера посредством SMS-сервисов от ООО «Русь» (GoodCity), ИКЮЛ 20366950.</p>
			<p class="text-justify">Согласие на SMS-оповещения может быть отозвано в любой момент путем обращения по тел: 606.</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
	      </div>
	    </div>
	  </div>
	</div>';

include '../elements/layoutCabinet.php';