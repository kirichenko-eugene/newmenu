<?php 

class Cabinet 
{
	private $crm;
	private $token;
	private $name;
	private $surname;
	private $patronymic;
	private $gender;
	private $marrital;
	private $birth;
	private $login;
	private $authcode;
	private $session;

	public function __construct()
	{
		$this->crm = 'https://crmclient.goodcity.com.ru/api';
		$this->token = 'mkaGbKCH9Z7tft7F';
		$this->session = new SessionShell;
	}

	public function preLogin()
	{
		$data = array("token" => $this->token,
			"request" => array(
				"action" => "login", 
				"login" => "$this->login"
			)
		); 
		$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
		$curl = curl_init($this->crm);                                        
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
	);
		$result = curl_exec($curl);
		curl_close($curl);
		$this->session->set('curlAutorization', $result);
		return $result;
	}

	public function login()
	{
		$data = array("token" => $this->token,
			"request" => array(
				"action" => "login", 
				"login" => "$this->login", 
				"auth_code" => "$this->authcode" 
			)
		); 
		$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
		$curl = curl_init($this->crm);                                        
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
	);
		$result = curl_exec($curl);
		curl_close($curl);
		$this->session->set('curlLogin', $result);
		$result = json_decode($result, true);
		$this->session->set('curlResult', $result);
		return $result;
	}

	public function preRegistration()
	{
		$fullName = $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
		$data = array("token" => $this->token,
			"request" => array(
				"action" => "registration", 
				"F_Name" => "$this->name", 
				"L_Name" => "$this->surname", 
				"M_Name" => "$this->patronymic", 
				"Full_Name" => "$fullName", 
				"Marrital" => "$this->marrital",
				"Birth" => "$this->birth", 
				"Gender" => "$this->gender", 
				"login" => "$this->login"
			)
		); 
		$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
		$curl = curl_init($this->crm);                                        
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
	);
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	public function registration()
	{
		$fullName = $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
		$data = array("token" => $this->token,
			"request" => array(
				"action" => "registration", 
				"F_Name" => "$this->name", 
				"L_Name" => "$this->surname", 
				"M_Name" => "$this->patronymic", 
				"Full_Name" => "$fullName", 
				"Marrital" => "$this->marrital",
				"Birth" => "$this->birth", 
				"Gender" => "$this->gender", 
				"login" => "$this->login", 
				"auth_code" => "$this->authcode" 
			)
		); 
		$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
		$curl = curl_init($this->crm);                                        
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
	);
		$result = curl_exec($curl);
		curl_close($curl);
		$this->session->set('curlAnswer', $result);
		return $result;
	}

	public function getInfoByHolderId($holder)
	{
		$data = array("token" => $this->token,
			"request" => array(
				"action" => "holder_info", 
				"holder_id" => "$holder" 
			)
		); 
		$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
		$curl = curl_init($this->crm);                                        
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
	);
		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($result, true);
		$this->session->set('holderResult', $result);
		return $result;
	}

	public function getTransactionsByAccountNumber($accountNumber)
	{
		$data = array("token" => $this->token,
			"request" => array(
				"action" => "account_transactions", 
				"account_number" => "$accountNumber" 
			)
		); 
		$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
		$curl = curl_init($this->crm);                                        
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
	);
		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($result, true);
		return $result;
	}

	public function getPersonalInfoByHolderId($holder)
	{
		$data = array("token" => $this->token,
			"request" => array(
				"action" => "holder_info", 
				"holder_id" => "$holder" 
			)
		); 
		$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
		$curl = curl_init($this->crm);                                        
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
	);
		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($result, true);
		return $result;
	}

	public function prepareHolderInfo($holderInfo, $name, $surname, $patronymic, $gender, $marrital)
	{
		if (isset($holderInfo["Accounts"])) {
			unset($holderInfo["Accounts"]);
		}

		if(isset($holderInfo["Addresses"])) {
			unset($holderInfo["Addresses"]);
		}

		if(isset($holderInfo["Cards"])) {
			unset($holderInfo["Cards"]);
		}

		if(isset($holderInfo["Coupons"])) {
			unset($holderInfo["Coupons"]);
		}

		if(isset($holderInfo["Contacts"])) {
			unset($holderInfo["Contacts"]);
		}

		if (isset($holderInfo["Holder"])) {
			$holderInfo["Holder"]["F_Name"] = $name;
			$holderInfo["Holder"]["L_Name"] = $surname;
			$holderInfo["Holder"]["M_Name"] = $patronymic;
			$holderInfo["Holder"]["Gender"] = $gender;
			$holderInfo["Holder"]["Marrital"] = $marrital;
			$holderInfo["Holder"]["Full_Name"] = $surname . ' ' . $name . ' ' . $patronymic;
		}

		$dataEditHolder = array("token" => $this->token,
			"request" => array("action" => "edit_holder", "data" => $holderInfo)
		); 
		$editHolder = json_encode ($dataEditHolder, JSON_UNESCAPED_UNICODE);
		$curl = curl_init($this->crm);                                     
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $editHolder);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($editHolder))
	);
		$holderEditResult = curl_exec($curl);
		curl_close($curl);
		$holderEditResult = json_decode($holderEditResult, true);
		return $holderEditResult;
	}

	public function getLogin()
	{
		return $this->login;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getSurname()
	{
		return $this->surname;
	}

	public function getPatronymic()
	{
		return $this->patronymic;
	}

	public function getGender()
	{
		return $this->gender;
	}

	public function getMarrital()
	{
		return $this->marrital;
	}

	public function setName($name)
	{
		$this->name = htmlspecialchars($name);
	}

	public function setSurname($surname)
	{
		$this->surname = htmlspecialchars($surname);
	}

	public function setPatronymic($patronymic)
	{
		$this->patronymic = htmlspecialchars($patronymic);
	}

	public function setGender($gender)
	{
		$this->gender = htmlspecialchars($gender);
	}

	public function setMarrital($marrital)
	{
		$this->marrital = htmlspecialchars($marrital);
	}

	public function setBirth($birth)
	{

		$this->birth = htmlspecialchars($birth);
	}

	public function setLogin($login)
	{
		$result = preg_replace('#\D#', '', $login);
		$result = preg_replace('#^(071|8071)#', '38071', $result);
		$phoneLength = strlen($result);
		if ($phoneLength >= 10 AND $phoneLength <= 18) {
			$this->login = $result;
		}	
	}

	public function setAuthcode($authcode)
	{
		$this->authcode = $authcode;
	}

	public function setHolderId($result)
	{
		if (isset($result["Holder"])) {
			if (isset($result["Holder"]["Holder_ID"])) {
				$this->session->set('holderId', $result["Holder"]["Holder_ID"]);
			}
		}
	}

	public function getHolderId()
	{
		if ($this->session->exists('holderId')) {
			return $this->session->get('holderId');
		}
	}

	public function getCosts($result)
	{
		if (isset($result["Accounts"])) {
			foreach($result["Accounts"] as $account) {
				if ($account["Account_Type_ID"] == 3) {
					return $account["Account_Number"];
				}
			}
		}
	}

	public function getBonusRate($result)
	{
		if (isset($result["Accounts"])) {
			foreach($result["Accounts"] as $account) {
				if ($account["Account_Class"] == 1) {
					return intval($account["Base_Rate"]);
				}
			}
		}
	}

	public function getBonusBalance($result)
	{
		if (isset($result["Accounts"])) {
			foreach($result["Accounts"] as $account) {
				if ($account["Account_Class"] == 1) {
					return $account["Balance"];
				}
			}
		}
	}

	public function getCardCode($result)
	{
		if (isset($result["Cards"])) {
			foreach ($result["Cards"] as $card) {
				if ($card["Status"] == 'Active') {
					$userCard = $card["Card_Code"];
				}
				if ($userCard) {
					$day = date("d");
					$hour = date("H");
					$h1 = ($userCard & 0x0F000) >> 16;
                    $h2 = ($userCard & 0x0F000) >> 12;
                    $h3 = ($userCard & 0x00F00) >> 8;
                    $h4 = ($userCard & 0x000F0) >> 4;
                    $h5 = ($userCard & 0x0000F);
                    $crc = $h1 ^ $h2 ^ $h3 ^ $h4 ^ $h5;
                    $result = sprintf("%05X",$userCard+$day+$hour).sprintf("%X",$crc);
                    return $result;
				}	
			}	
		}
	}

	public function showFullName($result)
	{
		if (isset($result["Holder"])) {
			if (isset($result["Holder"]["Full_Name"])) {
				return $result["Holder"]["Full_Name"];
			}
		}
	}

	public function showSurname($result)
	{
		if (isset($result["Holder"])) {
			if (isset($result["Holder"]["L_Name"])) {
				return $result["Holder"]["L_Name"];
			}
		}
	}

	public function showName($result)
	{
		if (isset($result["Holder"])) {
			if (isset($result["Holder"]["F_Name"])) {
				return $result["Holder"]["F_Name"];
			}
		}
	}

	public function showPatronymic($result)
	{
		if (isset($result["Holder"])) {
			if (isset($result["Holder"]["M_Name"])) {
				return $result["Holder"]["M_Name"];
			}
		}
	}

	public function showBirth($result)
	{
		if (isset($result["Holder"])) {
			if (isset($result["Holder"]["Birth"])) {
				return $result["Holder"]["Birth"];
			}
		}
	}

	public function showGender($result)
	{
		if (isset($result["Holder"])) {
			if (isset($result["Holder"]["Gender"])) {
				return $result["Holder"]["Gender"];
			}
		}
	}

	public function showMarrital($result)
	{
		if (isset($result["Holder"])) {
			if (isset($result["Holder"]["Marrital"])) {
				return $result["Holder"]["Marrital"];
			}
		}
	}

	public function showPhone($result)
	{
		if (isset($result["Contacts"])) {
			$contacts = $result["Contacts"];
			$phoneResult = [];
			foreach ($contacts as $contact) {
				if ($contact["Type_Name"] == 'тел') {
					$phoneResult[] = $contact["Value"];
				}
			}
			return $phoneResult;
		}
	}
}
