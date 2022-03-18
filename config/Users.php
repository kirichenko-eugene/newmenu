<?php 

class Users
{
	private $connection;
	private $session;
	private $autorization;

	public function __construct()
	{
		$this->connection = new DatabaseShell;
		$this->session = new SessionShell;
		$this->autorization = new Autorization;
	}

	public function getOneUserByLogin($login)
	{
		$result = $this->connection->select("SELECT * FROM users WHERE name = ? and status = 1 LIMIT 1", [$login]);
		return $result;
	}

	public function countAllUsers()
	{
		$result = $this->connection->selectCount("SELECT * FROM users");
		return $result;	
	}

	public function usersForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT * FROM users LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function getStatus($user)
	{
		if ($user['status'] == 1) {
			$textStatus = 'Активен';
		} else {
			$textStatus = 'Удален';
		}
		return $textStatus;
	}

	public function changeStatus()
	{
		if (isset($_GET['changeStatus'])) {
			$id = $_GET['changeStatus'];
			$status = $_GET['status'];
			if($status == 1) {
				$newstatus = 0;
				$result = $this->connection->update("UPDATE users SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Пользователь был отключен!', 
				'status' => 'error'];
				$this->session->set('message', $messageData);
			} elseif ($status == 0) {
				$newstatus = 1;
				$result = $this->connection->update("UPDATE users SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Пользователь снова активен!', 
				'status' => 'success'];
				$this->session->set('message', $messageData);
			}

			return $status;
		}
	}

	public function checkUserId()
	{
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$result = $this->connection->select("SELECT * FROM users WHERE id = ? LIMIT 1", [$id]);
			return $result;
		}
	}

	public function changePassword($site)
	{
		if (isset($_POST['password'])) {
				$password = htmlspecialchars($_POST['password']);
				
				if (isset($_GET['id'])) {
					$id = $_GET['id'];

					if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password)) {

						$messageData = ['text' => 'Пароль не удовлетворяет требованиям! Нужно хотя бы 1 число, 1 буква, 8-12 символов', 
						'status' => 'error'];
						$this->session->set('message', $messageData);
					} else {
						if($password == '') {
							$password = password_hash(mt_rand(999999, 999999999), PASSWORD_DEFAULT);
						} else {
							$password = password_hash($password, PASSWORD_DEFAULT);
						}
						$result = $this->connection->update("UPDATE users SET password = ? WHERE id = ?", [$password, $id]);

						$messageData = ['text' => 'Пароль успешно обновлен', 
						'status' => 'success'];
						$this->session->set('message', $messageData);

						$this->autorization->toPage($site.'pages/usersAdminPage.php');
					}	
				}
			} else {
				return '';
			}
	}

	public function getUserById($id)
	{
		$result = $this->connection->select("SELECT * FROM users WHERE id = ? LIMIT 1", [$id]);
		return $result;
	}

	public function countUsersByName($name)
	{
		$countUsers = $this->connection->selectCount("SELECT * FROM users WHERE name = ?", [$name]);
		return $countUsers;
	}

	public function changeUserName($site)
	{
		if (isset($_POST['name'])) {
			$name = htmlspecialchars($_POST['name']);
			if (isset($_GET['id'])) {
				$id = $_GET['id'];
				if($this->getUserById($id)[0]['name'] !== $name) {
					if ($this->countUsersByName($name) !== 0) {
						$messageData = ['text' => 'Пользователь с таким именем уже зарегистрирован', 
						'status' => 'error'];
						$this->session->set('message', $messageData);
					} else {
						$result = $this->connection->update("UPDATE users SET name = ? WHERE id = ?", [$name, $id]);

						$messageData = ['text' => 'Пользователь успешно обновлен', 
						'status' => 'success'];
						$this->session->set('message', $messageData);

						$this->autorization->toPage($site.'pages/usersAdminPage.php');
					}
				} else {
					$this->autorization->toPage($site.'pages/usersAdminPage.php');
				}
			}
		}
	}

	public function createUser()
	{
		if (isset($_POST['submit'])) {
			$name = htmlspecialchars($_POST['name']);
			$password = '';
		} else {
			$name = '';
			$password = '';
		}

		if (isset($_POST['submit'])) {
			if(!preg_match("/^[a-zA-Z0-9\s]{3,30}$/", $name)){
				$messageData = ['text' => 'Логин может состоять только из букв английского алфавита, цифр и иметь длину от 3 до 30 символов', 
				'status' => 'error'];
				$this->session->set('message', $messageData);
			} else {
				if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', htmlspecialchars($_POST['password']))) {
					$messageData = ['text' => 'Пароль не удовлетворяет требованиям! Нужно хотя бы 1 число, 1 буква, 8-12 символов', 
					'status' => 'error'];
					$this->session->set('message', $messageData);
				} else {
					if(htmlspecialchars($_POST['password']) == '') {
						$password = password_hash(mt_rand(999999, 999999999), PASSWORD_DEFAULT);
					} else {
						$password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
					}

					if ($this->countUsersByName($name) !== 0) {
						$messageData = ['text' => 'Пользователь с таким именем уже зарегистрирован', 
						'status' => 'error'];
						$this->session->set('message', $messageData);
					} else {
						$result = $this->connection->insert("INSERT into users (name, password, status) VALUES (?, ?, 1)", [$name, $password]);

						$messageData = ['text' => 'Пользователь успешно добавлен',
						'status' => 'success'];
						$this->session->set('message', $messageData);
					}	
				}
			}
		} else {
			return '';
		}
	}
}