<?php 
include '../config/config.php';
require_once '../config/Autorization.php';
require_once '../config/Pagination.php';
require_once '../config/DishesAdmin.php';
require_once '../config/Properties.php';
require_once '../config/ResizeImage.php';
require_once '../config/TagHelper.php';
require_once '../config/TableHelper.php';
require_once '../config/FormHelper.php';
require_once '../config/CategoriesAdmin.php';

$autorization = new Autorization;
$pagination = new Pagination;
$categories = new CategoriesAdmin;
$dishes = new DishesAdmin;
$table = new TableHelper;
$form = new FormHelper;
$tag = new TagHelper;

try{
    
    $search_data = filter_input_array(INPUT_POST);
    if(!$search_data){
        throw new Exception('нет данных');
    }
    $name = isset($search_data['name']) ? $search_data['name'] : "";
    
    $dishesForTable = $dishes->getDishesByFilter($name);
    include 'tableForDishesPageAdmin.php';
    include '../elements/layoutAdminAjax.php';
    
} catch (Exception $ex) {
    echo $ex->getMessage();
}