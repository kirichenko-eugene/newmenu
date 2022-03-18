<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/CategoriesAdmin.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/ResizeImage.php';

$autorization = new Autorization;
$categories = new CategoriesAdmin;
$form = new FormHelper;
$resizeImage = new ResizeImage;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Редактировать категорию';
	$checkId = $categories->checkCategoryId();
	$getAllCategories = $categories->getAllActiveCategories();

	$content = '<div class="row justify-content-center m-2"><h2>Редактировать категорию</h2></div>';

	if($checkId) {
		if (isset($_POST['name'])) {
			$name = htmlspecialchars($_POST['name']);
			$position = htmlspecialchars($_POST['position']);
			$parent = htmlspecialchars($_POST['parent']);
		} else {
			$name = $checkId[0]['name'];
			$position = $checkId[0]['weight'];
			$parent = $checkId[0]['parent'];
		}
		$categories->changeCategory($site, $dirCategory);
		$content .= '<div class="row justify-content-center m-2">';
		$content .= $form->openForm(['method' => 'POST', 'enctype' => 'multipart/form-data']);
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'name', 
			'aria-describedby' => 'name', 
			'name' => 'name',
			'value' => $name, 
			'placeholder' => 'Имя категории',
			'required' => true],
			'Введите новое имя');
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'position', 
			'aria-describedby' => 'position', 
			'name' => 'position',
			'value' => $position,
			'placeholder' => '1 - начало списка', 
			'required' => true],
			'Позиция категории в меню');

		$selectAttr = ['name' => 'parent', 'class' => 'form-control'];
		$selectLabel = 'Родительская категория';
		$selectOptions[0] = ['text' => 'Выберите категорию', 'attrs' => ['value' => '']];
		foreach($getAllCategories as $category) {
			if ($parent == $category['id']) {
				$selectOptions[] = [
				'text' => $category['name'], 
				'attrs' => ['value' => $category['id'], 'selected' => true]
				];
			} else {
				$selectOptions[] = [
				'text' => $category['name'], 
				'attrs' => ['value' => $category['id']]
				];
			}	
		}

		$content .= $form->select($selectAttr, $selectOptions, $selectLabel);

		$content .= $form->checkbox(
			['class' => 'form-check-input',
			'name' => 'changePhoto',
			'id' => 'changePhoto',
			'onclick' => 'displayHidden()'], 
			'Изменить фото категории?'
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
		$content .= '<div class="row justify-content-center m-2">Данная категория не найдена</div>';
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