<?php
include '../config/config.php';
require_once '../config/Service.php';
require_once '../config/Actions.php';

$title = 'Акции Goodcity';
$licence = new Service;
$actions = new Actions;

$title = 'Акционные предложения';
if ((isset($_GET['lic']) or isset($_SESSION['lic'])) and (isset($_GET['table']) or isset($_SESSION['table']))) {
	$actions->setRestaurantName('Majorelle');
	$activeActions = $actions->getAllActiveActions();

	$content = $actions->setActionTitle();
	$imgPath = $site . $adir;
	foreach ($activeActions as $action) {
		$content .= $actions->setImgPlace($imgPath, $action['img']);
	}
} else {
	$content = '<div class="row justify-content-center m-2"><h3 class="text-center">Пожалуйста, отсканируйте QR-код</h3></div>';
}

include '../elements/layout.php';
?>