<?php 

class CategoriesAdmin
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

	public function countAllCategories()
	{
		$result = $this->connection->selectCount("SELECT * FROM categories");
		return $result;	
	}

	public function getAllActiveCategories()
	{
		$result = $this->connection->select("SELECT * FROM categories WHERE status = 1");
		return $result;
	}

	public function categoriesForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT c.id AS id, c.name AS name, c.img AS img, cat.name AS parent, c.weight AS weight, c.status AS status 
			FROM categories c 
			LEFT JOIN categories cat ON c.parent = cat.id 
		 	ORDER BY status DESC LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function getStatus($category)
	{
		if ($category['status'] == 1) {
			$textStatus = 'Активна';
		} else {
			$textStatus = 'Отключена';
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
				$result = $this->connection->update("UPDATE categories SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Категория была отключена!', 
				'status' => 'error'];
				$this->session->set('message', $messageData);
			} elseif ($status == 0) {
				$newstatus = 1;
				$result = $this->connection->update("UPDATE categories SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Категория снова активна!', 
				'status' => 'success'];
				$this->session->set('message', $messageData);
			}

			return $status;
		}
	}

	public function createCategory($dirCategory)
	{
		if (isset($_POST['submitCategory'])) {
			$categoryName = htmlspecialchars($_POST['name']);
			$categoryPosition = htmlspecialchars($_POST['position']);
			$parentName = htmlspecialchars($_POST['parent']);
			$file = $this->resizeImage->setFile($_FILES['photo']);
			$photoPath = $this->resizeImage->getImageName();
			$types = $this->resizeImage->getSupportTypes();
			$fullFileName = $this->resizeImage->getFullFileName();

			if ($parentName != '') {
					if (in_array($this->resizeImage->getImageMime(), $types)) {
						if ($this->resizeImage->imageRegExp($fullFileName) === false) {
							$messageData = ['text' => 'Имя файла может состоять только из букв английского алфавита, цифр и иметь длину от 3 до 30 символов', 
							'status' => 'error'];
							$this->session->set('message', $messageData);
						} else {
							$result = $this->connection->insert("INSERT into categories (name, img, parent, weight, status) VALUES (?, ?, ?, ?, 1)", [$categoryName, $photoPath, $parentName, $categoryPosition]);
								$this->resizeImage->saveImage($file, 700, 150, $dirCategory);

								$messageData = ['text' => 'Категория успешно добавлена',
												'status' => 'success'];
								$this->session->set('message', $messageData);;
						}
				} else {
					$messageData = ['text' => 'Неподдерживаемый тип файла',
					'status' => 'error'];
					$this->session->set('message', $messageData);
				}	
			} else {
				$messageData = ['text' => 'Ошибка! Выберите родительскую категорию',
								'status' => 'error'];
				$this->session->set('message', $messageData);
			}
		} else {
			return '';
		}
	}

	public function checkCategoryId()
	{
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$result = $this->connection->select("SELECT * FROM categories WHERE id = ? LIMIT 1", [$id]);
			return $result;
		}
	}

	public function createRootCategory()
	{
		if ($this->firstCategory() == '' OR $this->firstCategory() == NULL) {
			$this->createFirstCategory();
		} 
	}

	public function changeCategory($site, $dirCategory)
	{
		if (isset($_POST['submit'])) {
			$name = htmlspecialchars($_POST['name']);
			$position = htmlspecialchars($_POST['position']);
			$parent = htmlspecialchars($_POST['parent']);
			if (isset($_POST['changePhoto'])) {
				$checkStatus = htmlspecialchars($_POST['changePhoto']);
			} else {
				$checkStatus = 0;
			}
			if (isset($_GET['id'])) {
				if ($parent != '') {

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
								$result = $this->connection->update("UPDATE categories SET name = ?, img = ?, parent = ?, weight = ? WHERE id = ?", [$name, $photoPath, $parent, $position, $id]);
								$this->resizeImage->saveImage($file, 700, 150, $dirCategory);
								$messageData = ['text' => 'Категория успешно обновлена', 
								'status' => 'success'];
								$this->session->set('message', $messageData);
								$this->autorization->toPage($site.'pages/categoriesAdminPage.php');
							}
						} else {
							$messageData = ['text' => 'Неподдерживаемый тип файла',
							'status' => 'error'];
							$this->session->set('message', $messageData);
						}

					} else {
						$result = $this->connection->update("UPDATE categories SET name = ?, parent = ?, weight = ? WHERE id = ?", [$name, $parent, $position, $id]);

						$messageData = ['text' => 'Категория успешно обновлена', 
						'status' => 'success'];
						$this->session->set('message', $messageData);

						$this->autorization->toPage($site.'pages/categoriesAdminPage.php');
					}
				} else {
					$messageData = ['text' => 'Ошибка! Выберите родительскую категорию',
					'status' => 'error'];
					$this->session->set('message', $messageData);
				}


			}
		}
	}

	private function firstCategory()
	{
		$result = '';
		$result = $this->connection->select("SELECT id FROM categories WHERE name=?", ['Корневая категория']);
		return $result;
	}

	private function createFirstCategory()
	{
		$result = '';
		$result = $this->connection->insert("INSERT into categories (name, img, parent, weight, status) VALUES ('Корневая категория', '', 0, 1, 1)");
		return $result;
	}
}