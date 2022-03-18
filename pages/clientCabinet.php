<?php 

include '../config/config.php';
require_once '../config/Service.php';
require_once '../config/Autorization.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/TableHelper.php';
require_once '../config/Cabinet.php';

$tag = new TagHelper;
$form = new FormHelper;
$table = new TableHelper;
$licence = new Service;
$autorization = new Autorization;
$cabinet = new Cabinet;

$title = 'Добро пожаловать';

if (isset($_POST['exitCrm'])) {
	$session->del('autorizationOk');
	$session->del('phoneAutorization');
	$autorization->toPage($site.'pages/startCabinet.php');
}

if($autorization->noEmptyAuth($session->get('autorizationOk'))) {

	$curlResult = $session->get('curlResult');
	$cabinet->setHolderId($curlResult);
	
	$result = $cabinet->getInfoByHolderId($cabinet->getHolderId());

	$accountNumber = $cabinet->getCosts($result);
	if (isset($accountNumber)) {
		$transactions = $cabinet->getTransactionsByAccountNumber($accountNumber);
	}

	$content = '';
	$content .= $tag->open('div', ['class' => 'row justify-content-center m-2 flex-column']);
	$content .= $form->openForm(['method' => 'POST', 'class' => 'col-10 col-sm-8 col-md-6 ml-auto mr-auto']);
	$content .= $form->submit([
		'name' => 'exitCrm', 
		'class' => 'btn btn-danger d-block mr-auto ml-auto m-2', 
		'value' => 'Выйти'
	]);
	$content .= $form->closeForm();

	$content .= $tag->open('a', ['href' => $site.'pages/personalInfoCabinet.php', 'class' => 'text-center text-uppercase text-dark']);
	$content .= $tag->open('ins');
	$content .= $cabinet->showFullName($result);
	$content .= $tag->close('ins');
	$content .= $tag->close('a');

	$content .= $tag->close('div');

	$content .= $tag->open('div', ['class' =>'container']);
	$content .= $tag->open('div', ['class' =>'accordion', 'id' => 'accordion']);
	$content .= $tag->open('div', ['class' =>'card mb-1']);
	$content .= $tag->open('h2', ['class' => 'card-header', 'id' => 'headerCard']);
	$content .= $tag->open('button', ['class' => 'btn btn-light btn-block text-center text-uppercase', 'type' => 'button', 'data-toggle' => 'collapse', 'data-target' => '#collapseCard', 'aria-expanded' => 'true', 'aria-controls' => 'collapseCard']);
	$content .= 'Карта';
	$content .= $tag->close('button');
	$content .= $tag->close('h2');
	$content .= $tag->open('div', ['class' =>'collapse', 'id' => 'collapseCard', 'aria-labelledby' => 'headerCard', 'data-parent' => '#accordion']);
	$content .= $tag->open('div', ['class' =>'card-body']);
	$content .= $tag->open('div');
	$content .= 'Ваша карта: '.$cabinet->getCardCode($result);
	$content .= $tag->close('div');
	$content .= $tag->open('div');
	$content .= 'Размер бонуса: '.$cabinet->getBonusRate($result).'%';
	$content .= $tag->close('div');
	$content .= $tag->open('div');
	$content .= 'Бонусный счет: '.$cabinet->getBonusBalance($result).' руб.';
	$content .= $tag->close('div');
	$content .= $tag->close('div');
	$content .= $tag->close('div');
	$content .= $tag->close('div');

	$content .= $tag->open('div', ['class' =>'card mb-1']);
	$content .= $tag->open('h2', ['class' => 'card-header', 'id' => 'headerTransaction']);
	$content .= $tag->open('button', ['class' => 'btn btn-light btn-block text-center text-uppercase', 'type' => 'button', 'data-toggle' => 'collapse', 'data-target' => '#collapseTransaction', 'aria-expanded' => 'true', 'aria-controls' => 'collapseTransaction']);
	$content .= 'Чеки';
	$content .= $tag->close('button');
	$content .= $tag->close('h2');
	$content .= $tag->open('div', ['class' =>'collapse', 'id' => 'collapseTransaction', 'aria-labelledby' => 'headerTransaction', 'data-parent' => '#accordion']);
	$content .= $tag->open('div', ['class' =>'card-body']);
	$content .= $table->tableOpen();
	$content .= $table->tableHead([
		['thname' => '№', 'attrs' => ['scope' => 'col']], 
		['thname' => 'Время', 'attrs' => ['scope' => 'col']],  
		['thname' => 'Сумма', 'attrs' => ['scope' => 'col']],  
		['thname' => 'Чек', 'attrs' => ['scope' => 'col']] 
	]);
	$content .= $table->tbodyOpen();

	$count = 1;
	foreach ($transactions["Transactions"] as $transaction) {
		$prepareTransactionTime = date_create($transaction["Transaction_Time"]);
		$transactionTime = date_format($prepareTransactionTime, 'd-m-Y H:i:s');
		$sum = $transaction["Summ"];
		$transactionId = $transaction["Transaction_ID"];
   		$dopInfo = $transaction["Dop_Info"]; // есть ли чек

   		if ($dopInfo) {
   			$checkTd = '<button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#check_'.$transactionId.'">Чек</button>';
   			$tdAttr = '';
   		} else {
   			$checkTd = '';
   			$tdAttr = 'pr-0 pl-0';
   		}

   		$content .= $table->tableBody([
   			['tdname' => $count],  
   			['tdname' => $transactionTime], 
   			['tdname' => $sum], 
   			['tdname' => $checkTd, 'attrs' => ['class' => $tdAttr]]
   		]);
   		$count++;	
   	}

   	$content .= $table->tbodyClose();
   	$content .= $table->tableClose();
   	$content .= $tag->close('div');
   	$content .= $tag->close('div');
   	$content .= $tag->close('div');

   	foreach ($transactions["Transactions"] as $transaction) {
   		$dopInfo = $transaction["Dop_Info"];
   		$transactionId = $transaction["Transaction_ID"];
   		$prepareCheckTime = date_create($transaction["checkTime"]);
   		$checkTime = date_format($prepareCheckTime, 'd-m-Y H:i:s');
   		$checkNum = $transaction["check"]["checknum"];
   		$checkOrder = $transaction["check"]["ordernum"];
   		$checkAll = $transaction["check"]["lines"];
   		$discountsAll = $transaction["check"]["discounts"];
   		$total = $transaction["check"]["total"];
   		$paymentsAll = $transaction["check"]["payments"];
   		if ($dopInfo) {
   			$content .= $tag->open('div', ['class' => 'modal fade', 'id' => 'check_'.$transactionId, 'tabindex' => '-1', 'role' => 'dialog', 'aria-labelledby' => 'CheckModal'.$transactionId, 'aria-hidden' => 'true']);
   			$content .= $tag->open('div', ['class' => 'modal-dialog modal-dialog-centered', 'role' => 'document']);
   			$content .= $tag->open('div', ['class' => 'modal-content']);
   			$content .= $tag->open('div', ['class' => 'modal-header']);
   			$content .= $tag->open('h5', ['class' => 'modal-title', 'id' => 'CheckModal'.$transactionId]);
   			$content .= 'Чек '.$checkNum;
   			$content .= $tag->close('h5');
   			$content .= $tag->open('button', ['class' => 'close', 'type' => 'button', 'data-dismiss' => 'modal', 'aria-label' => 'Close']);
   			$content .= $tag->open('span', ['aria-hidden' => 'true']);
   			$content .= "&times;";
   			$content .= $tag->close('span');
   			$content .= $tag->close('button');
   			$content .= $tag->close('div');
   			$content .= $tag->open('div', ['class' => 'modal-body']);
   			$content .= $tag->open('div');
   			$content .= 'Заказ: '.$checkOrder;
   			$content .= $tag->close('div');

   			$content .= $tag->open('table', ['class' => 'table table-striped table-sm']);
   			$content .= $table->tableHead([
   				['thname' => 'Название', 'attrs' => ['scope' => 'col', 'class' => 'p-0']], 
   				['thname' => 'Цена', 'attrs' => ['scope' => 'col', 'class' => 'p-0']],  
   				['thname' => 'Кол', 'attrs' => ['scope' => 'col', 'class' => 'p-0']],  
   				['thname' => 'Сумма', 'attrs' => ['scope' => 'col', 'class' => 'p-0']] 
   			]);
   			$content .= $table->tbodyOpen();
   			foreach ($checkAll as $dish) {
   				$dishName = $dish["name"];
   				$dishPrice = $dish["price"];
   				$dishQuantity = $dish["quantity"];
   				$dishSum = $dish["sum"];
   				if ($dishPrice != 0) {
   					$content .= $tag->open('tr');
   					$content .= $tag->open('td', ['class' => 'pr-0 pl-0 text-center']);
   					$content .= $dishName;
   					$content .= $tag->close('td');
   					$content .= $tag->open('td', ['class' => 'pr-0 pl-0 text-center']);
   					$content .= $dishPrice;
   					$content .= $tag->close('td');
   					$content .= $tag->open('td', ['class' => 'pr-0 pl-0 text-center']);
   					$content .= $dishQuantity;
   					$content .= $tag->close('td');
   					$content .= $tag->open('td', ['class' => 'pr-0 pl-0 text-center']);
   					$content .= $dishSum;
   					$content .= $tag->close('td');
   					$content .= $tag->close('tr');
   				}
   			}
   			if ($discountsAll) {
   				foreach ($discountsAll as $discount) {
   					$discountName = $discount["name"];
   					$discountSum = $discount["sum"];
   					$content .= $tag->open('tr', ['class' => 'font-italic']);
   					$content .= $tag->open('td', ['colspan' => '3']);
   					$content .= $discountName;
   					$content .= $tag->close('td');
   					$content .= $tag->open('td');
   					$content .= $discountSum;
   					$content .= $tag->close('td');
   					$content .= $tag->close('tr');
   				}
   			}
   			$content .= $tag->open('tr');
   			$content .= $tag->open('td', ['colspan' => '3', 'class' => 'font-weight-bold']);
   			$content .= 'Всего';
   			$content .= $tag->close('td');
   			$content .= $tag->open('td', ['class' => 'font-weight-bold']);
   			$content .= $total;
   			$content .= $tag->close('td');
   			$content .= $tag->close('tr');
   			if ($paymentsAll) {
   				foreach ($paymentsAll as $payment) {
   					$paymentName = $payment["name"];
   					$paymentSum = $payment["sum"];
   					$content .= $tag->open('tr', ['class' => 'font-italic']);
   					$content .= $tag->open('td', ['colspan' => '3']);
   					$content .= $paymentName;
   					$content .= $tag->close('td');
   					$content .= $tag->open('td');
   					$content .= $paymentSum;
   					$content .= $tag->close('td');
   					$content .= $tag->close('tr');
   				}
   			}
   			$content .= $table->tbodyClose();
   			$content .= $table->tableClose();

   			$content .= $tag->close('div');
   			$content .= $tag->open('div', ['class' => 'modal-footer']);
   			$content .= $tag->open('button', ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal']);
   			$content .= 'Закрыть';
   			$content .= $tag->close('button');
   			$content .= $tag->close('div');
   			$content .= $tag->close('div');
   			$content .= $tag->close('div');
   			$content .= $tag->close('div');
   		}
   	}

   	$content .= $tag->open('div', ['class' =>'card mb-1']);
   	$content .= $tag->open('h2', ['class' => 'card-header', 'id' => 'headerCoupon']);
   	$content .= $tag->open('button', ['class' => 'btn btn-light btn-block text-center text-uppercase', 'type' => 'button', 'data-toggle' => 'collapse', 'data-target' => '#collapseCoupon', 'aria-expanded' => 'true', 'aria-controls' => 'collapseCoupon']);
   	$content .= 'Купоны';
   	$content .= $tag->close('button');
   	$content .= $tag->close('h2');
   	$content .= $tag->open('div', ['class' =>'collapse', 'id' => 'collapseCoupon', 'aria-labelledby' => 'headerCoupon', 'data-parent' => '#accordion']);
   	$content .= $tag->open('div', ['class' =>'card-body']);
   	if ($result["Coupons"]) {
   	$content .= $table->tableOpen();
   	$content .= $table->tableHead([
   		['thname' => 'Купон', 'attrs' => ['class' => 'p-0']], 
   		['thname' => 'Начало', 'attrs' => ['class' => 'p-0']],  
   		['thname' => 'Окончание', 'attrs' => ['class' => 'p-0']]
   	]);
   	$content .= $table->tbodyOpen();
   		foreach($result["Coupons"] as $coupon) {
   			$prepareCouponOffered = date_create($value["Offered"]);
   			$couponOffered = date_format($prepareCouponOffered, 'd-m-Y');
   			$prepareCouponExpired = date_create($value["Expired"]);
   			$couponExpired = date_format($prepareCouponExpired, 'd-m-Y');
   			$content .= $table->tableBody([
   				['tdname' => $coupon["Coupon_Type_Name"]],  
	   			['tdname' => $couponOffered], 
	   			['tdname' => $couponExpired], 
   			]);
   		} 
   	$content .= $table->tbodyClose();
   	$content .= $table->tableClose();
   	} else {
   		$content .= 'Сейчас у вас нет купонов';
   	}
   	$content .= $tag->close('div');
   	$content .= $tag->close('div');
   	$content .= $tag->close('div');
   	$content .= $tag->close('div');
   	$content .= $tag->close('div');

   	include '../elements/layoutCabinet.php';
   } else {
   	$autorization->toPage($site.'pages/startCabinet.php');
   }