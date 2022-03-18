<?php 

class ActionsAdmin 
{
	private $connection;
	private $session;
	private $autorization;
	private $resizeImage;

	public function __construct()
	{
		$this->connection = new DatabaseShell;
		$this->session = new SessionShell;
		$this->autorization = new Autorization;
		$this->resizeImage = new ResizeImage;
	}

	public function countAllActions()
	{
		$result = $this->connection->selectCount("SELECT * FROM actions");
		return $result;	
	}

	public function actionsForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT * FROM actions LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function getStatus($action)
	{
		if ($action['status'] == 1) {
			$textStatus = 'Активна';
		} else {
			$textStatus = 'Отключена';
		}
		return $textStatus;
	}

	public function checkActionId()
	{
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$result = $this->connection->select("SELECT * FROM actions WHERE id = ? LIMIT 1", [$id]);
			return $result;
		}
	}

	public function changeStatus()
	{
		if (isset($_GET['changeStatus'])) {
			$id = $_GET['changeStatus'];
			$status = $_GET['status'];
			if($status == 1) {
				$newstatus = 0;
				$result = $this->connection->update("UPDATE actions SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Акция была отключена!', 
				'status' => 'error'];
				$this->session->set('message', $messageData);
			} elseif ($status == 0) {
				$newstatus = 1;
				$result = $this->connection->update("UPDATE actions SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Акция снова активна!', 
				'status' => 'success'];
				$this->session->set('message', $messageData);
			}

			return $status;
		}
	}

	public function createAction($dirAction)
	{
		if (isset($_POST['submitAction'])) {
			$actionPosition = htmlspecialchars($_POST['position']);
			$file = $this->resizeImage->setFile($_FILES['photo']);
			$photoPath = $this->resizeImage->getImageName();
			$types = $this->resizeImage->getSupportTypes();
			$fullFileName = $this->resizeImage->getFullFileName();

			if ($photoPath != '') {
				if (in_array($this->resizeImage->getImageMime(), $types)) {
					if ($this->resizeImage->imageRegExp($fullFileName) === false) {
						$messageData = ['text' => 'Имя файла может состоять только из букв английского алфавита, цифр и иметь длину от 3 до 30 символов', 
						'status' => 'error'];
						$this->session->set('message', $messageData);
					} else {
						$result = $this->connection->insert("INSERT into actions (img, weight, status) VALUES (?, ?, 1)", [$photoPath, $actionPosition]);
						$this->resizeImage->simpleSaveImage($dirAction);

						$messageData = ['text' => 'Акция успешно добавлена',
						'status' => 'success'];
						$this->session->set('message', $messageData);
					}
				} else {
					$messageData = ['text' => 'Неподдерживаемый тип файла',
					'status' => 'error'];
					$this->session->set('message', $messageData);
				}
	
			} else {
				$messageData = ['text' => 'Выберите изображение!',
				'status' => 'error'];
				$this->session->set('message', $messageData);
			}
		} else {
			return '';
		}
	}

	public function changeAction($site, $dirAction)
	{
		if (isset($_POST['submit'])) {
			$position = htmlspecialchars($_POST['position']);
			if (isset($_POST['changePhoto'])) {
				$checkStatus = htmlspecialchars($_POST['changePhoto']);
			} else {
				$checkStatus = 0;
			}
			if (isset($_GET['id'])) {
				if ($position != '') {

					$id = htmlspecialchars($_GET['id']);
					if($checkStatus == 1) {
						$file = $this->resizeImage->setFile($_FILES['photo']);
						$photoPath = $this->resizeImage->getImageName();
						$types = $this->resizeImage->getSupportTypes();
						$fullFileName = $this->resizeImage->getFullFileName();
						if (in_array($this->resizeImage->getImageMime(), $types)) {
							if ($this->resizeImage->imageRegExp($fullFileName) === false) {
								$messageData = ['text' => 'Имя файла может состоять только из букв английского алфавита, цифр и иметь длину от 3 до 30 символов', 
								'status' => 'error'];
								$this->session->set('message', $messageData);
							} else {
								$result = $this->connection->update("UPDATE actions SET img = ?, weight = ? WHERE id = ?", [$photoPath, $position, $id]);
								$this->resizeImage->simpleSaveImage($dirAction);
								$messageData = ['text' => 'Акция успешно обновлена', 
								'status' => 'success'];
								$this->session->set('message', $messageData);
								$this->autorization->toPage($site.'pages/actionsAdminPage.php');
							}
						} else {
							$messageData = ['text' => 'Неподдерживаемый тип файла',
							'status' => 'error'];
							$this->session->set('message', $messageData);
						}

					} else {
						$result = $this->connection->update("UPDATE actions SET weight = ? WHERE id = ?", [$position, $id]);

						$messageData = ['text' => 'Акция успешно обновлена', 
						'status' => 'success'];
						$this->session->set('message', $messageData);

						$this->autorization->toPage($site.'pages/actionsAdminPage.php');
					}
				} else {
					$messageData = ['text' => 'Ошибка! Укажите позицию акции',
					'status' => 'error'];
					$this->session->set('message', $messageData);
				}


			}
		}
	}
}