<?php
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/CategoriesAdmin.php';
require_once '../config/DishesAdmin.php';
require_once '../config/TagHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/ResizeImage.php';
require_once '../config/Properties.php';

$autorization = new Autorization;
$categories = new CategoriesAdmin;
$dishes = new DishesAdmin;
$form = new FormHelper;
$resizeImage = new ResizeImage;
$properties = new Properties;

if($autorization->noEmptyAuth($session->get('auth'))) {
	$title = 'Редактировать блюдо';
	$checkId = $dishes->checkDishId();
	$getAllCategories = $categories->getAllActiveCategories();
	$getAllProperties = $properties->getAllProperties();
	
	$content = '<div class="row justify-content-center m-2"><h2>Редактировать блюдо</h2></div>';

	if($checkId) {
		$dishes->changeDish($site, $dirImg, $bdirImg);

		$position = $checkId[0]['weight'];
		$property = $dishes->getPropertyByDishId($checkId[0]['Ident']);
		$parent = $checkId[0]['Parent'];
		$dishPropertyArray = [];
		foreach($property as $dishProperty) {
			$dishPropertyArray[] = $dishProperty['id'];
		}

		$content = "<div class=\"row justify-content-center m-2\"><h2>Редактировать блюдо {$checkId[0]['Name']}</h2></div>";
		$content .= '<div class="row justify-content-center m-2">';
		$content .= $form->openForm(['method' => 'POST', 'enctype' => 'multipart/form-data']);
		$content .= $form->input(
			['class' => 'form-control', 
			'id' => 'position', 
			'aria-describedby' => 'position', 
			'name' => 'position',
			'value' => $position,
			'placeholder' => '1 - начало списка', 
			'required' => true],
			'Позиция блюда в меню');

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

		$selectAttrMultiple = ['name' => 'property[]', 'class' => 'form-control', 'multiple' => true, 'size' => 5];
		$selectLabelMultiple = 'Свойства блюда';
		foreach($getAllProperties as $oneProperty) {
			if (in_array($oneProperty['id'], $dishPropertyArray)) {
				$selectOptionsMultiple[] = [
					'text' => $oneProperty['name'], 
					'attrs' => ['value' => $oneProperty['id'], 'selected' => true]
				];
			} else {
				$selectOptionsMultiple[] = [
					'text' => $oneProperty['name'], 
					'attrs' => ['value' => $oneProperty['id']]
				];
			}
		}

		$content .= $form->select($selectAttrMultiple, $selectOptionsMultiple, $selectLabelMultiple);

		$content .= $form->checkbox(
			['class' => 'form-check-input',
			'name' => 'changePhoto',
			'id' => 'changePhoto',
			'onclick' => 'displayHidden()'], 
			'Изменить фото блюда?'
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
		$content .= '<div class="row justify-content-center m-2">Данное блюдо не найдено</div>';
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