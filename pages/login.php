<?php
	include '../config/config.php';
	require_once '../config/Autorization.php';
	require_once '../config/Users.php';
	$autorization = new Autorization;
	$user = new Users;

	if($session->exists('auth')) {
		$autorization->issetAuth($session->get('auth'), $site.'admin.php');
	}

	$title = 'Добро пожаловать!';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$site?>assets/css/bootstrap.min.css">
</head>
<body>
	<div class="container d-flex h-100 justify-content-center">
		<div class="row align-self-center">
			<div class="col-xl-10 col-lg-10 mx-auto mt-5">
				<div class="jumbotron text-center">
					<h1>GoodCity <span class="badge badge-warning">Menu</span></h1>
					<h3>Войдите в учетную запись</h3>
					<form method="post">
						<div class="form-group">
							<label for="username">Имя пользователя</label>
							<input type="text" class="form-control" id="username" aria-describedby="loginHelp" placeholder="Введите логин" name="login">
						</div>
						<div class="form-group">
							<label for="userpass">Пароль</label>
							<input type="password" class="form-control" id="userpass" placeholder="Введите пароль" name="password">
						</div>
						<button type="submit" class="btn btn-primary btn-lg" name="submit">Войти</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php
		if(isset($_POST['submit'])) {
			$login = $autorization->getLogin();
			$password = $autorization->getPassword();
			$findUser = $user->getOneUserByLogin($login);
			if($findUser) {
				if($autorization->userVerify($findUser)) {
					$messageData = ['text' => 'Логин пользователя выполнен успешно!', 
									'status' => 'success'];
					$session->set('auth', true);
					$session->set('message', $messageData);
					$autorization->toPage($site.'admin.php');
				} else {
				echo '<div class="text-center"><p class="font-weight-bold text-danger">Логин или пароль неверны!</p></div>';
				}
			} else {
			echo '<div class="text-center"><p class="font-weight-bold text-danger">Логин или пароль неверны!</p></div>';
			}
		} 
	?>

	<script src="<?=$site?>assets/js/jquery-3.5.1.min.js"></script>
	<script src="<?=$site?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>