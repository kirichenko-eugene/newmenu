<?php 

class Menu 
{
	private $categories;
	private $root = '';

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

	public function getRoot()
	{
		return $this->root;
	}

	public function getCategories($site)
	{
		$result = '';
		$result .= '<ul class="list-group list-group-flush">';
		$mainCategories = $this->rootCategories($this->firstCategory());
		foreach ($mainCategories as $category) {
			$result .= '<li class="list-group-item pt-0 pb-0">';
			$result .= "<a href =\"{$site}admin.php?category={$category['id']}\">{$category['name']}</a>";
			$subCategory = $this->rootCategories($category['id']);
			if ($subCategory) {
				$result .= '<ul class="list-group list-group-flush">';
				foreach ($subCategory as $category) {
					$result .= '<li class="list-group-item pt-0 pb-0">';
						$result .= "<a href =\"{$site}admin.php?category={$category['id']}\">{$category['name']}</a>";
					$result .= '</li>';
				}
				$result .= '</ul>';
			}
			$result .= '</li>';
		}
		$result .= '</ul>';

		return $result;
	}

	private function rootCategories($root)
	{
		return $this->connection->select("SELECT * FROM categories WHERE status = 1 AND parent=? ORDER BY weight ASC", [$root]); 
	}

	private function firstCategory()
	{
		$result = '';
		$result = $this->connection->select("SELECT id FROM categories WHERE name=?", ['Корневая категория']);
		return $result['0']['id'];
	}
}