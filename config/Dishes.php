<?php 

class Dishes 
{
	private $connection;
	private $properties = [];
	private $classProperties;

	public function __construct()
	{
		$this->connection = new DatabaseShell;
		$this->classProperties = new Properties;
		$this->resizeImage = new ResizeImage;
		$this->properties = $this->classProperties->getAllProperties();
	}

    public function allDishes($dishParent)
	{
		return $this->connection->select("SELECT * FROM elements WHERE status = 1 AND Parent=? ORDER BY weight ASC", [$dishParent]); 
	}

	public function getId($dish)
	{
		return htmlspecialchars($dish['id'], ENT_QUOTES);
	}

	public function getName($dish)
	{
		return htmlspecialchars($dish['genName0419'], ENT_QUOTES);
	}

	public function getPrice($dish)
	{
		return $dish['price'] / 100;
	}

	public function getImage($dish)
	{
		return $dish['LargeImagePath'];
	}

	public function getVotes($dish)
	{
		return $dish['votes'];
	}

	public function getDishIdent($dish)
	{
		return $dish['Ident'];
	}

	public function addToLikeContent($voteId)
	{
		if(isset($_SESSION['likes'])) {
			$likesContent = $_SESSION['likes'];
		} else {
			$likesContent = [];
		}
		array_push($likesContent, $voteId);
		$_SESSION['likes'] = $likesContent;
		return $_SESSION['likes'];
	}

	public function getAllVotes($voteId)
	{
		$result = $this->connection->select("SELECT votes FROM elements WHERE id=?", [$voteId]); 
		return $result['0'];
	}

	public function updateVotes($curVotes, $voteId)
	{
		$votesUp = $curVotes['votes']+1;
		return $this->connection->update("UPDATE elements SET votes=? WHERE id=?", [$votesUp, $voteId]); 
	}

	public function getEffectiveVotes($voteId)
	{
		$votes = $this->getAllVotes($voteId);
		return $votes['votes'];
	}

	public function getDescription($dish)
	{
		return htmlspecialchars($dish['genLongComment0419'], ENT_QUOTES);
	}

	public function getProperties($dishIdent)
	{
		$result = $this->connection->select("SELECT dp.id AS id, dp.dish AS dish, dp.property AS property, pr.img AS img   
			FROM dishproperties dp
			LEFT JOIN properties pr ON dp.property = pr.id 
			WHERE dp.dish = ? AND pr.status = 1", [$dishIdent]);
		return $result;	
	}

	public function countProperties($dishIdent)
	{
		$result = $this->connection->selectCount("SELECT dp.id AS id, dp.dish AS dish, dp.property AS property  
			FROM dishproperties dp
			LEFT JOIN properties pr ON dp.property = pr.id 
			WHERE dp.dish = ? AND pr.status = 1", [$dishIdent]);
		return $result;	
	}
}