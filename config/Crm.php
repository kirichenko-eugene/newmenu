<?php 

class Crm 
{
	private $sms;
	private $token;
	private $crm;

	public function __construct()
	{
		$this->sms = 'http://172.20.0.11:8088';
		$this->token = 'rrr';
		$this->crm = 'https://crmclient.goodcity.com.ru/api';
	}

	public function curlCrm($action, $searchAction, $search)
	{
		$data = array("token" => $this->token,
		"request" => array("action" => "$action", "$searchAction" => "$search")
		); 
		$dataString = json_encode ($data, JSON_UNESCAPED_UNICODE);
		$curl = curl_init($this->crm);                                        
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($dataString))
	);
		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($result);
		return $result;
	}
}