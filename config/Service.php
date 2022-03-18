<?php 

class Service 
{
	private $licence = '';
	private $table = '';
	private $licenceUrl = '';
	private $likes = [];
	private $smoke = '';
	private $id = '';
	private $message = '';
	private $name = '';
	private $phone = '';
	private $mark = '';
	private $data = [];

	public function __construct()
	{
		if (isset($_GET['lic'])) {
			$this->licence = htmlspecialchars($_GET['lic']);
			$_SESSION['lic'] = htmlspecialchars($_GET['lic']);
		} else {
			$this->licence = $_SESSION['lic']; 
		}	

		if(isset($_SESSION['likes'])) {
			$this->likes = $_SESSION['likes'];
		}

		if (isset($_GET['table'])) {
			$this->table = htmlspecialchars($_GET['table']);
			$_SESSION['table'] = htmlspecialchars($_GET['table']);
		} else {
			$this->table = $_SESSION['table'];
		}

		if (isset($_GET['smoke'])) {
			$this->smoke = htmlspecialchars($_GET['smoke']);
			$_SESSION['smoke'] = htmlspecialchars($_GET['smoke']);
		} else {
			$this->smoke = $_SESSION['smoke'];
		}

		if(isset($_GET['id'])) {
			$this->id = htmlspecialchars($_GET['id']);
		} 

		if(isset($_REQUEST['msg'])) {
			$this->message = htmlspecialchars($_REQUEST['msg']);
		} 

		if(isset($_REQUEST['name'])) {
			$this->name = htmlspecialchars($_REQUEST['name']);
		} 

		if(isset($_REQUEST['phone'])) {
			$this->phone = htmlspecialchars($_REQUEST['phone']);
		} 

		if(isset($_REQUEST['mark'])) {
			$this->mark = htmlspecialchars($_REQUEST['mark']);
		} 
	}

	public function getLicenceUrl()
	{
		switch ($this->licence) {
			case '02':
			$this->licenceUrl = 'http://91.193.253.140:8085';
			break;

			case '03':
			$this->licenceUrl = 'http://109.254.39.185:8085';
			break;

			case '04':
			$this->licenceUrl = 'http://109.254.37.112:8085';
			break;

			case '05':
			$this->licenceUrl = 'http://109.254.91.92:8085';
			break;

			case '06':
			$this->licenceUrl = 'http://178.158.165.27:8085';
			break;

			case '08':
			$this->licenceUrl = 'http://109.254.12.14:8085';
			break;

			case '09':
			$this->licenceUrl = 'http://109.254.91.42:8085';
			break;

			case '11':
			$this->licenceUrl = 'http://109.254.10.102:8085';
			break;

			case '12':
			$this->licenceUrl = 'http://109.254.64.23:8085';
			break;

			case '16':
			$this->licenceUrl = 'http://mail.goodcity.com.ru:8085';
			break;
		}

		return $this->licenceUrl;
	}

	public function headerStatusOk()
	{
		if ($this->getHeaders() === "HTTP/1.0 200 OK") {
			return true;
		} else {
			return false;
		}
	}

	public function sendRequest($data)
	{
		$data = $this->data;
		if ($data['lic'] != '' AND $data['table'] != '' AND $this->getLicenceUrl() != '') {
			$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($this->getLicenceUrl(), false, $context);
		} else {
			return false;
		}	
	}

	public function callwaiterData()
	{
		$this->data = [
			'lic' => $this->getLicence(),
			'table' => $this->getTable(),
			'id' => $this->getId(),
			'msg' => $this->getMessage(),
			'smoke' => $this->getSmoke()
		];
		return $this->data;
	}

	public function feedbackData()
	{
		$this->data = [
			'lic' => $this->getLicence(),
			'table' => $this->getTable(),
			'id' => $this->getId(),
			'name' => $this->getGuestName(),
			'phone' => $this->getGuestPhone(),
			'msg' => $this->getMessage(),
			'mark' => $this->getGuestMark(),
			'smoke' => $this->getSmoke()
		];
		return $this->data;
	}

	public function getLicence()
	{
		return $this->licence;
	}

	public function getLikes()
	{
		return $this->likes;
	}

	public function getTable()
	{
		return $this->table;
	}

	public function getSmoke()
	{
		return $this->smoke;
	}

	public function getId()
	{
		return htmlspecialchars($this->id);
	}

	public function getMessage()
	{
		return htmlspecialchars($this->message);
	}

	public function getGuestName()
	{
		return htmlspecialchars($this->name);
	}

	public function getGuestPhone()
	{
		return htmlspecialchars($this->phone);
	}

	public function getGuestMark()
	{
		return htmlspecialchars($this->mark);
	}

	private function getHeaders()
	{
		return current(get_headers($this->getLicenceUrl(),0));
	}
}
