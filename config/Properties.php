<?php 

class Properties
{
	private $properties = [];

	public function __construct()
	{
		$this->connection = new DatabaseShell;
		$this->session = new SessionShell;
		$this->autorization = new Autorization;
		$this->resizeImage = new ResizeImage;
	}

	public function getAllProperties()
	{
		$result = $this->connection->select("SELECT * FROM properties WHERE status = 1");
		return $result;
	}

	public function countAllProperties()
	{
		$result = $this->connection->selectCount("SELECT * FROM properties");
		return $result;	
	}

	public function propertiesForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT * FROM properties ORDER BY status DESC LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function getStatus($property)
	{
		if ($property['status'] == 1) {
			$textStatus = 'Активно';
		} else {
			$textStatus = 'Отключено';
		}
		return $textStatus;
	}

	public function checkPropertyId()
	{
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$result = $this->connection->select("SELECT * FROM properties WHERE id = ? LIMIT 1", [$id]);
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
				$result = $this->connection->update("UPDATE properties SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Свойство блюда было отключено!', 
				'status' => 'error'];
				$this->session->set('message', $messageData);
			} elseif ($status == 0) {
				$newstatus = 1;
				$result = $this->connection->update("UPDATE properties SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Свойство блюда снова активно!', 
				'status' => 'success'];
				$this->session->set('message', $messageData);
			}

			return $status;
		}
	}

	public function createProperty($dirProperty)
	{
		if (isset($_POST['submitProperty'])) {
			$propertyName = htmlspecialchars($_POST['name']);
			$file = $this->resizeImage->setFile($_FILES['photo']);
			$photoPath = $this->resizeImage->getImageName();
			$types = $this->resizeImage->getSupportTypes();
			$fullFileName = $this->resizeImage->getFullFileName();
			if ($photoPath != '') {
				if (in_array($this->resizeImage->getSvgType($_FILES['photo']), $types)) {
					if ($this->resizeImage->imageRegExp($fullFileName) === false) {
						$messageData = ['text' => 'Имя файла может состоять только из букв английского алфавита, цифр и иметь длину от 3 до 30 символов', 
						'status' => 'error'];
						$this->session->set('message', $messageData);
					} else {
						$result = $this->connection->insert("INSERT into properties (name, img, status) VALUES (?, ?, 1)", [$propertyName, $photoPath]);
						$this->resizeImage->simpleSaveImage($dirProperty);

						$messageData = ['text' => 'Свойство блюда успешно добавлено',
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

	public function changeProperty($site, $dirProperty)
	{
		if (isset($_POST['submit'])) {
			$name = htmlspecialchars($_POST['name']);
			if (isset($_POST['changePhoto'])) {
				$checkStatus = htmlspecialchars($_POST['changePhoto']);
			} else {
				$checkStatus = 0;
			}
			if (isset($_GET['id'])) {
				if ($name != '') {
					$id = htmlspecialchars($_GET['id']);
					if($checkStatus == 1) {
						$file = $this->resizeImage->setFile($_FILES['photo']);
						$photoPath = $this->resizeImage->getImageName();
						$types = $this->resizeImage->getSupportTypes();
						$fullFileName = $this->resizeImage->getFullFileName();
						if ($photoPath != '') {
							if (in_array($this->resizeImage->getSvgType($_FILES['photo']), $types)) {
								if ($this->resizeImage->imageRegExp($fullFileName) === false) {
									$messageData = ['text' => 'Имя файла может состоять только из букв английского алфавита, цифр и иметь длину от 3 до 30 символов', 
									'status' => 'error'];
									$this->session->set('message', $messageData);
								} else {
									$result = $this->connection->update("UPDATE properties SET name = ?, img = ? WHERE id = ?", [$name, $photoPath, $id]);
									$this->resizeImage->simpleSaveImage($dirProperty);

									$messageData = ['text' => 'Свойство блюда успешно добавлено',
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
						$result = $this->connection->update("UPDATE properties SET name = ? WHERE id = ?", [$name, $id]);

						$messageData = ['text' => 'Свойство успешно обновлено', 
						'status' => 'success'];
						$this->session->set('message', $messageData);

						$this->autorization->toPage($site.'pages/propertiesAdminPage.php');
					}
				} else { 
					$messageData = ['text' => 'Ошибка! Имя не должно быть пустым',
					'status' => 'error'];
					$this->session->set('message', $messageData);
				}


			} 
		}
	}
}