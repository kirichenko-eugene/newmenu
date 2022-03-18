<?php 

class DishesAdmin
{
	private $connection;
	private $session;
	private $autorization;
	private $properties = [];
	private $classProperties;
	private $resizeImage;

	public function __construct()
	{
		$this->connection = new DatabaseShell;
		$this->session = new SessionShell;
		$this->autorization = new Autorization;
		$this->classProperties = new Properties;
		$this->properties = $this->classProperties->getAllProperties();
		$this->resizeImage = new ResizeImage;
	}

	public function getPropertyByDishId($ident)
	{
		$result = $this->connection->select("SELECT p.name AS name, dp.property AS id  
			FROM dishproperties dp 
			LEFT JOIN properties p ON dp.property = p.id  
			WHERE dp.dish = ?", [$ident]);
		return $result;
	}

	public function getPropertyForDish($ident)
	{
		$result = '';
		foreach ($this->getPropertyByDishId($ident) as $dish) {
			$result .= "{$dish['name']} ";
		}
		return $result;
	}

	public function checkDishId()
	{
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$result = $this->connection->select("SELECT * FROM elements WHERE id = ? LIMIT 1", [$id]);
			return $result;
		}
	}

	public function getDishesByFilter($name = '')
	{
		$sql = "SELECT e.id AS id, e.Ident AS Ident, e.Name AS name, e.genName0419 AS genName0419, e.genLongComment0419 AS genLongComment0419, e.CategPath AS CategPath, cat.name AS parent, e.price AS price, e.LargeImagePath AS LargeImagePath, e.weight AS weight, e.votes AS votes, e.status AS status 
			FROM elements e 
			LEFT JOIN categories cat ON e.Parent = cat.id";
		if ($name != '') {
			$sql .= " WHERE e.Name LIKE ?";
			$params = ["%$name%"];
		} else {
			$params = [];
		}
		
		$result = $this->connection->select($sql, $params);
		return $result;
	}

	public function countAllDishes()
	{
		$result = $this->connection->selectCount("SELECT * FROM elements ORDER BY status ASC");
		return $result;	
	}

	public function allDishes()
	{
		$result = $this->connection->select("SELECT * FROM elements ORDER BY status ASC"); 
		return $result;
	}

	public function dishesForTableById($dishParent)
	{
		$result = $this->connection->select("SELECT e.id AS id, e.Ident AS Ident, e.Name AS name, e.genName0419 AS genName0419, e.genLongComment0419 AS genLongComment0419, e.CategPath AS CategPath, cat.name AS parent, e.price AS price, e.LargeImagePath AS LargeImagePath, e.weight AS weight, e.votes AS votes, e.status AS status 
			FROM elements e 
			LEFT JOIN categories cat ON e.Parent = cat.id 
			WHERE e.status = 1 AND e.Parent=? ORDER BY e.status ASC ", [$dishParent]);
		return $result;
	}

	public function dishesForTable($startPosition, $perPage)
	{
		$result = $this->connection->select("SELECT e.id AS id, e.Ident AS Ident, e.Name AS name, e.genName0419 AS genName0419, e.genLongComment0419 AS genLongComment0419, e.CategPath AS CategPath, cat.name AS parent, e.price AS price, e.LargeImagePath AS LargeImagePath, e.weight AS weight, e.votes AS votes, e.status AS status 
			FROM elements e 
			LEFT JOIN categories cat ON e.Parent = cat.id 
			ORDER BY status ASC LIMIT ?, ?", [$startPosition, $perPage]);
		return $result;
	}

	public function getStatus($dish)
	{
		if ($dish['status'] == 1) {
			$textStatus = 'Активно';
		} else {
			$textStatus = 'Отключено';
		}
		return $textStatus;
	}

	public function changeStatus()
	{
		if (isset($_GET['changeStatus'])) {
			$id = $_GET['changeStatus'];
			$status = $_GET['status'];
			$category = $_GET['category'];
			if($status == 1) {
				$newstatus = 0;
				$result = $this->connection->update("UPDATE elements SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Блюдо было отключено!', 
				'status' => 'error'];
				$this->session->set('message', $messageData);
			} elseif ($status == 0) {
				$newstatus = 1;
				$result = $this->connection->update("UPDATE elements SET status = ? WHERE id = ?", [$newstatus, $id]);
				$messageData = ['text' => 'Блюдо снова активно!', 
				'status' => 'success'];
				$this->session->set('message', $messageData);
			}

			return $status;
		}
	}

	public function changeDish($site, $dirImg, $bdirImg)
	{
		if (isset($_POST['submit'])) {
			$propertiesSelect = $_POST['property'];
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
					$dishIdent = $this->getDishIdentById($id);
					$dishProperties = $this->getPropertyByDishId($dishIdent);
					$dishPropertyOld = [];
					foreach($dishProperties as $dishProperty) {
						$dishPropertyOld[] = $dishProperty['id'];
					}
					$diffProperties = array_diff($propertiesSelect, $dishPropertyOld); 			

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
								$result = $this->connection->update("UPDATE elements SET LargeImagePath = ?, Parent = ?, weight = ? WHERE id = ?", [$photoPath, $parent, $position, $id]);
								$this->resizeImage->saveImage($file, 144, 96, $dirImg);
								$this->resizeImage->saveImage($file, 600, 400, $bdirImg);

								foreach($dishPropertyOld as $onePropertyOld) {
									if (in_array($onePropertyOld, $propertiesSelect)) {
										continue;
									} else {
										$this->deletePropertyByIdent($dishIdent, $onePropertyOld);
									}
								}

								if ($diffProperties != NULL) {
									foreach($diffProperties as $oneDiffProperty) {
										$this->insertPropertyByIdent($dishIdent, $oneDiffProperty);
									}
								}

								$messageData = ['text' => 'Блюдо успешно обновлено', 
								'status' => 'success'];
								$this->session->set('message', $messageData);
								$this->autorization->toPage($site.'pages/dishesAdminPage.php');

							}
						} else {
							$messageData = ['text' => 'Неподдерживаемый тип файла',
							'status' => 'error'];
							$this->session->set('message', $messageData);
						}

					} else {
						$result = $this->connection->update("UPDATE elements SET Parent = ?, weight = ? WHERE id = ?", [$parent, $position, $id]);

						foreach($dishPropertyOld as $onePropertyOld) {
							if (in_array($onePropertyOld, $propertiesSelect)) {
								continue;
							} else {
								$this->deletePropertyByIdent($dishIdent, $onePropertyOld);
							}
						}

						if ($diffProperties != NULL) {
							foreach($diffProperties as $oneDiffProperty) {
								$this->insertPropertyByIdent($dishIdent, $oneDiffProperty);
							}
						}

						$messageData = ['text' => 'Блюдо успешно обновлено', 
						'status' => 'success'];
						$this->session->set('message', $messageData);

						$this->autorization->toPage($site.'pages/dishesAdminPage.php');
					}
				} else {
					$messageData = ['text' => 'Ошибка! Выберите родительскую категорию',
					'status' => 'error'];
					$this->session->set('message', $messageData);
				}


			} else {
				$messageData = ['text' => 'Данное блюдо не найдено',
					'status' => 'error'];
				$this->session->set('message', $messageData);
			}
		}
	}

	private function getDishIdentById($id)
	{
		$result = $this->connection->select("SELECT Ident FROM elements WHERE id = ?", [$id]); 
		return $result[0]['Ident'];
	}

	private function deletePropertyByIdent($dishIdent, $propertyId)
	{
		$result = $this->connection->delete("DELETE FROM dishproperties WHERE dish = ? AND property = ?", [$dishIdent, $propertyId]); 
		return $result;
	}

	private function insertPropertyByIdent($dishIdent, $propertyId)
	{
		$result = $this->connection->insert("INSERT INTO dishproperties(dish, property) VALUES(?, ?)", [$dishIdent, $propertyId]); 
		return $result;
	}
}