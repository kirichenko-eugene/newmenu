<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Properties.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/ResizeImage.php';

$autorization = new Autorization;
$properties = new Properties;
$form = new FormHelper;
$resizeImage = new ResizeImage;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Редактировать свойсво';
	$checkId = $properties->checkPropertyId();

	$content = '<div class="row justify-content-center m-2"><h2>Редактировать свойство</h2></div>';

	if($checkId) {
		if (isset($_POST['name'])) {
			$name = htmlspecialchars($_POST['name']);
		} else {
			$name = $checkId[0]['name'];
		}
		$properties->changeProperty($site, $dirProperty);
		$content .= '<div class="row justify-content-center m-2">';
		$content .= $form->openForm(['method' => 'POST', 'enctype' => 'multipart/form-data']);
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'name', 
			'aria-describedby' => 'name', 
			'name' => 'name',
			'value' => $name, 
			'placeholder' => 'Имя свойства блюда',
			'required' => true],
			'Введите новое имя');

		$content .= $form->checkbox(
			['class' => 'form-check-input',
			'name' => 'changePhoto',
			'id' => 'changePhoto',
			'onclick' => 'displayHidden()'], 
			'Изменить фото свойства?'
		);

		$content .= $form->fileInputHidden(
			['class' => 'form-control-file', 
			'id' => 'photo', 
			'aria-describedby' => 'photo', 
			'name' => 'photo'],
			'Обновить фото');

		$content .= $form->submit(
			['name' => 'submit', 
			'class' => 'btn btn-primary d-block mr-auto ml-auto m-2', 
			'value' => 'Изменить']);
		$content .= $form->closeForm();
		$content .= '</div>';
		$content .= ob_get_clean();
	} else {
		$content .= '<div class="row justify-content-center m-2">Данное свойсво не найдено</div>';
	}
		
	include '../elements/layoutAdmin.php';

} else {
	$autorization->toPage($site.'pages/login.php');
}

?>

<script>
function displayHidden() {
    var checkBox = document.getElementById("changePhoto");
    var photoInput = document.getElementById("photo");
    if (checkBox.checked == true){
        photoInput.style.display = "block";
    } else {
       photoInput.style.display = "none";
    }
}
</script>