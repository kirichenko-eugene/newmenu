<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/ActionsAdmin.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/ResizeImage.php';

$autorization = new Autorization;
$actions = new ActionsAdmin;
$form = new FormHelper;
$resizeImage = new ResizeImage;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Редактировать акцию';
	$checkId = $actions->checkActionId();

	$content = '<div class="row justify-content-center m-2"><h2>Редактировать акцию</h2></div>';

	if($checkId) {
		if (isset($_POST['weight'])) {
			$position = htmlspecialchars($_POST['weight']);
		} else {
			$position = $checkId[0]['weight'];
		}
		$actions->changeAction($site, $dirAction);
		$content .= '<div class="row justify-content-center m-2">';
		$content .= $form->openForm(['method' => 'POST', 'enctype' => 'multipart/form-data']);
		
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'name', 
			'aria-describedby' => 'name', 
			'name' => 'position',
			'value' => $position,
			'placeholder' => '1 - начало списка', 
			'required' => true],
			'Позиция акции на странице');

		$content .= $form->checkbox(
			['class' => 'form-check-input',
			'name' => 'changePhoto',
			'id' => 'changePhoto',
			'onclick' => 'displayHidden()'], 
			'Изменить фото акции?'
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
		$content .= '<div class="row justify-content-center m-2">Данная акция не найдена</div>';
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