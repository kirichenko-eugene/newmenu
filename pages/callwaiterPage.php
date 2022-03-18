<?php
include '../config/config.php';
require_once '../config/Service.php';

$title = 'Вызов Goodcity';
$licence = new Service;
if(isset($_POST['callwaiter'])) {
	$sendRequest = $licence->sendRequest($licence->callwaiterData());
	if($sendRequest !== false AND $licence->headerStatusOk() === true) {
		$content = '<div class="d-flex justify-content-center text-center mt-5 ml-2 mr-2 pt-5">
		<div class="jumbotron">
		<h1 class="display-4">GoodCity</h1>
		<hr class="my-2">
		<p class="lead">Ваш вызов успешно отправлен, ожидайте</p>
		</div>
		</div>';
	} else {
		$content = '<div class="d-flex justify-content-center text-center mt-5 ml-2 mr-2 pt-5">
		<div class="jumbotron">
		<h1 class="display-4">GoodCity</h1>
		<hr class="my-2">
		<p class="lead">К сожалению, произошла ошибка, и вызов не был отправлен</p>
		</div>
		</div>';
	}
}

include '../elements/layout.php';
?>