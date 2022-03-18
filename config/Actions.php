<?php 

class Actions
{
	private $restaurant;

	public function __construct()
	{
		$this->connection = new DatabaseShell;
	}

	public function setRestaurantName($restaurant)
	{
		$this->restaurant = $restaurant;
	}

	public function getRestaurantName()
	{
		return $this->restaurant;
	}

	public function setActionTitle()
	{
		$name = $this->getRestaurantName();
		$result = "<div class=\"text-center\"><h3 class=\"m-1\">Акционные предложения $name</h3></div>";
		return $result;
	}

	public function getAllActiveActions()
	{
		$result = $this->connection->select("SELECT * FROM actions WHERE status = 1 ORDER BY weight ASC");
		return $result;
	}

	public function setImgPlace($site, $image)
	{
		$result = '<p class="m-2">';
		$result .= "<img src=\"$site$image\" alt=\"$image\" width=\"100%\">";
		$result .= '</p>';
		return $result;
	}
}