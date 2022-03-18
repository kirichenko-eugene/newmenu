<?php 
if ((isset($_GET['lic']) or isset($_SESSION['lic'])) and (isset($_GET['table']) or isset($_SESSION['table']))) {
    ?>
    <?php if($dishList): ?>
        <div class="text-center"><h3 class="m-1"><?=$categories->getCategoryById(htmlspecialchars($_GET['category']))?></h3></div>
        <div id = "display" class="columns"> 
         <?php foreach($dishList as $dish): ?>
          <?php 
          $dishIdent = $dishes->getDishIdent($dish);
          $dishId = $dishes->getId($dish);
          $properties = $dishes->getProperties($dishIdent);
          $votes = $dishes->getVotes($dish);
          $dishName = $dishes->getName($dish);
          $dishPrice = $dishes->getPrice($dish);
          $description = $dishes->getDescription($dish);
          $imageExist = file_exists($bdir . $dishes->getImage($dish));
          ?>

          <figure> 
            <a data-fancybox="dish" data-caption=
            "<div><h3><?=$dishes->getName($dish)?></h3></div>
            <div> 
               <?php foreach ($properties as $one_property): ?>     
                 <img class='dishicons-img-size' src='<?=$site . $pdir . $one_property['img']?>'>
             <?php endforeach; ?>	
         </div>

         <?php if(mb_strlen($description) <= 40) { ?>
            <p><?=$description?></p>
        <?php } else { ?>
            <a style='text-decoration: underline;' data-fancybox data-src='#modal-description' href='javascript:;'><?=mb_substr($description, 0, 40,'UTF-8');?>...</a>
            <div style='display: none;' id='modal-description'>
              <h4 class='text-center'><?=$dishName?></h4>
              <hr class='my-2'>
              <p class='text-center'><?=$description?></p>
          </div>

      <?php } ?>
      <div><?=$dishes->getPrice($dish)?> руб.</div>
      "
      href="<?=$site . $bdir . $dishes->getImage($dish)?>">
      <?php if ($dishes->getImage($dish) != NULL AND $imageExist) { ?>
        <img src="<?=$site . $dir . $dishes->getImage($dish)?>" alt="<?=$dishes->getName($dish)?>">
    <?php } else { ?>
        <img src="img/no_img.png" alt="no_dish">
    <?php } 

    if($properties){
       $count_properties = $dishes->countProperties($dishIdent);
            // Значения для рассчета отступа иконок
       $startPx = 121;
       $gapPx = 17; 

       foreach ($properties as $one_property) { ?>
           <span class="dishicons d-flex justify-content-start" style="left:<?=$startPx?>px"> 
            <img class='dishicons-img' src='<?=$site . $pdir . $one_property['img']?>'>
        </span> 
        <?php  $startPx = $startPx - $gapPx - 2;      
    }
} ?>

</a>
<figcaption>
 <!-- likes -->
 <?php
 if(in_array($dishes->getId($dish), $licence->getLikes())) { ?>
    <div class="like-container">
     <div class="vote_buttons heart-container" id="vote_buttons<?=$dishes->getId($dish)?>">
        <img src="<?=$site?>img/heartfull.svg" alt="">
    </div>
    <div class="votes_count" id="votes_count<?=$dishes->getId($dish)?>">
        <?php echo "<span>$votes</span>"; ?>
    </div>
</div>
<?php } else { ?>
    <div class="like-container">
     <div class="vote_buttons heart-container vote-active" id="vote_buttons<?=$dishes->getId($dish)?>">
        <a href="javascript:;" class="vote_up" id="<?=$dishes->getId($dish)?>"><img src="<?=$site?>img/heart.svg" alt=""></a>
    </div>
    <div class="votes_count" id="votes_count<?=$dishes->getId($dish)?>">
        <?php if($votes != 0) {
            echo "<span>$votes</span>"; 
        } ?>
    </div>
</div>
<?php } ?>
<!-- Ограничиваем название в 27 символов и устанавливаем кодировку. Это для миниатюры -->
<div class="description-img cut_name">
 <?php if(mb_strlen($dishName) <= 27) {
    echo $dishName; 
} else { 
    echo mb_substr($dishName, 0, 27,'UTF-8').'...';
} ?> 
</div>
<div class="description-img dishprice"><?=$dishPrice?> руб.</div>
</figcaption>       
<!-- ****************************************************************************************** -->
</figure>

<?php endforeach; ?>	
</div>
<?php endif; 
}
?>