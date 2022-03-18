<?php
include '../config/config.php';
require_once '../config/Service.php';

$title = 'Отзыв Goodcity';
$licence = new Service;

if(isset($_POST['feedback'])) {
	$sendRequest = $licence->sendRequest($licence->feedbackData());
	if($sendRequest !== false AND $licence->headerStatusOk() === true) {
		$content = '<div class="d-flex justify-content-center text-center mt-5 ml-2 mr-2 pt-5">
		<div class="jumbotron">
		<h1 class="display-4">GoodCity</h1>
		<hr class="my-2">
		<p class="lead">Ваш отзыв успешно отправлен</p>
		</div>
		</div>';
	} else {
		$content = '<div class="d-flex justify-content-center text-center mt-5 ml-2 mr-2 pt-5">
		<div class="jumbotron">
		<h1 class="display-4">GoodCity</h1>
		<hr class="my-2">
		<p class="lead">К сожалению, произошла ошибка, и отзыв не был отправлен</p>
		</div>
		</div>';
	}
} else {
	$content = '<div class="d-flex flex-column justify-content-center">
		<h3 class="text-center">Отзыв о сервисе</h3>

		<div class="container">
			<div class="row justify-content-center align-items-center">
				<form method="POST" action="">
					<div class="form-group">      
						<input class="form-control" type="text" name="name" placeholder="Как вас зовут" required>
					</div>
					<div class="form-group">      
						<input class="form-control" type="tel" name="phone" placeholder="Ваш номер телефона" required>
					</div>
					<div class="form-group"> 
						<span class="m-1">Оставьте комментарий</span>
						<textarea class="form-control" name="msg" rows="5"></textarea>
					</div>
					<div class="star-field">
						<div><span class="m-1">Оцените нас</span></div>
						<div class="stars">
							<span class="starRating">
								<input class="star_input" id="rating5" type="radio" name="mark" value="5" checked>
								<label for="rating5" class="star_label">5</label>

								<input class="star_input" id="rating4" type="radio" name="mark" value="4">
								<label for="rating4" class="star_label">4</label>

								<input class="star_input" id="rating3" type="radio" name="mark" value="3">
								<label for="rating3" class="star_label">3</label>

								<input class="star_input" id="rating2" type="radio" name="mark" value="2">
								<label for="rating2" class="star_label">2</label>

								<input class="star_input" id="rating1" type="radio" name="mark" value="1">
								<label class="star_label" for="rating1">1</label>
							</span>
						</div>
					</div>
					<div class="d-flex justify-content-center">      
						<input class="btn btn-danger menu-button" type="submit" name="feedback" value="Отправить">
					</div>

				</form>
			</div>
		</div>
	</div>';
}

include '../elements/layout.php';
?>