<?php 
if (!isset($content)) {
	$content = '';
}
$content .= $table->tableOpen();
	$content .= $table->tableHead([
		['thname' => 'RK данные'], 
		['thname' => 'Название'], 
		['thname' => 'Описание'], 
		['thname' => 'Цена'],
		['thname' => 'Позиция'],
		['thname' => 'Свойство'],
		['thname' => 'Фото'],
		['thname' => 'Родитель'], 
		['thname' => 'Редактировать'], 
		['thname' => 'Отключить/Восстановить'] 
	]);
	$content .= $table->tbodyOpen();

	foreach($dishesForTable as $key => $dish) {
		$properties = ($dishes->getPropertyForDish($dish['Ident']));
		$modalIdRkInfoButton = '#modal-'. $dish['Ident'];
		$rkInfo = $form->modalButton($dish['name'], [
			'data-target' => $modalIdRkInfoButton, 
			'class' => 'btn btn-link']);
		$showRkInfo = '<div class="row justify-content-center m-1">';
		$showRkInfo .= $form->openForm();
		$showRkInfo .= $tag->open('div', ['class' => 'row justify-content-center m-2']);
		$showRkInfo .= 'Идентификатор в RK - ' . $dish['Ident'] . $tag->open('br');
		$showRkInfo .= 'Путь блюда в RK - ' . $dish['CategPath'];
		$showRkInfo .= $tag->close('div');
		$showRkInfo .= $form->closeForm();
		$showRkInfo .= '</div>';

		$content .= $form->modalBody($dish['Ident'], $dish['name'], $showRkInfo);

		$tdImg = $dish['LargeImagePath'];
		
		
		if ($tdImg != '') {
			$modalIdImgButton = '#modal-'. $dish['id'];
			$modalButtonImg = $form->modalButton($tdImg, [
			'data-target' => $modalIdImgButton, 
			'class' => 'btn btn-secondary']);
			$tdImg = $modalButtonImg;
			$imgPath = $site.''.$bdir.''.$dish['LargeImagePath'];
			$dishImage = "<img src=\"$imgPath\" class=\"img-fluid\" alt=\"{$dish['name']}\">";
		}

		if (!isset($_GET['page'])) {
			$_GET['page'] = 1;
		}

		if (!isset($_GET['category'])) {
			$_GET['category'] = '';
		}

		$textStatus = $dishes->getStatus($dish);
		$editLink = "<a href=\"{$site}pages/editDishesAdminPage.php?id={$dish['id']}\">Редактировать</a>";
		$changeStatusLink = "<a href=\"?page={$_GET['page']}&category={$_GET['category']}&changeStatus={$dish['id']}&status={$dish['status']}\">Применить</a>";
		$tdStatusLink = $textStatus . '<br>' . $changeStatusLink;

		// $selectAttr = ['name' => 'parent', 'class' => 'form-control'];
		// $selectLabel = 'Родительская категория';
		// $selectOptions[0] = ['text' => 'Выберите категорию', 'attrs' => ['value' => '']];
		// foreach($getAllCategories as $category) {
		// 	if ($parent == $category['id']) {
		// 		$selectOptions[] = [
		// 		'text' => $category['name'], 
		// 		'attrs' => ['value' => $category['id'], 'selected' => true]
		// 		];
		// 	} else {
		// 		$selectOptions[] = [
		// 		'text' => $category['name'], 
		// 		'attrs' => ['value' => $category['id']]
		// 		];
		// 	}	
		// }
		//$form->select($selectAttr, $selectOptions, $selectLabel);

		$content .= $table->tableBody([
			['tdname' => $rkInfo], 
			['tdname' => $dish['genName0419']], 
			['tdname' => $dish['genLongComment0419']], 
			['tdname' => $dish['price'] / 100], 
			['tdname' => $dish['weight']], 
			['tdname' => $properties], 
			['tdname' => $tdImg], 
			['tdname' => $dish['parent']], 
			['tdname' => $editLink], 
			['tdname' => $tdStatusLink] 
		]);

		if (isset($dishImage)) {
			$content .= $form->modalBody($dish['id'], $dish['name'], $dishImage);
		}
		
	}

	$content .= $table->tbodyClose();
	$content .= $table->tableClose();