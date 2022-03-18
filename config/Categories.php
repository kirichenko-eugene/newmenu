<?php 

class Categories
{
	private $root = '';
	private $connection;
	private $categories = [];

	public function __construct()
	{
		$this->connection = new DatabaseShell;

		if (isset($_GET['category'])) {
			$this->root = htmlspecialchars($_GET['category']);
		} else {
			$this->root = $this->firstCategory();
		}

		$this->categories = $this->rootCategories($this->root);
	}

	public function getCategories()
	{
		return $this->categories;
	}

	public function getRoot()
	{
		return $this->root;
	}

	public function getCategoryById($id)
	{
		$result = $this->connection->select("SELECT * FROM categories WHERE status = 1 AND id=? ORDER BY weight ASC", [$id]);
		return $result['0']['name'];
	}

	private function firstCategory()
	{
		$result = '';
		$result = $this->connection->select("SELECT id FROM categories WHERE name=?", ['Корневая категория']);
		return $result['0']['id'];
	}

	private function rootCategories($root)
	{
		return $this->connection->select("SELECT * FROM categories WHERE status = 1 AND parent=? ORDER BY weight ASC", [$root]); 
	}

}