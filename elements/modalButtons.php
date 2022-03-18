<!-- Modal -->
<div class="modal fade right" id="exampleModalPreview1" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel1" aria-hidden="true">
    <div class="modal-dialog-full-width modal-dialog momodel modal-fluid" role="document">
        <div class="modal-content-full-width modal-content ">
            <div class=" modal-header-full-width modal-header text-center">
                <h4 class="modal-title w-100" id="exampleModalPreviewLabel1">Сервис</h4>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column justify-content-center">
                  <div>
                    <!-- Кнопка личный кабинет (Панфилова) ************** -->
                    <?php if($licence->getLicence() == 11) { ?> 
                      <form class="text-center" action="https://goodcity.com.ru/cards/login" method="POST">
                         <input class="btn btn-danger btn-lg menu-button" type="submit" name="crm" value="Личный кабинет">
                     </form>
                 <?php  } ?> 	
             </div>
<?php if ((isset($_GET['lic']) or isset($_SESSION['lic'])) and (isset($_GET['table']) or isset($_SESSION['table']))) { ?>
                 <div>
                     <!-- Кнопка вызов официанта ************** -->
                     <form class="text-center" action="<?=$site?>pages/callwaiterPage.php?id=00&msg=вызов" method="POST">
                      <input class="btn btn-danger btn-lg menu-button mb-2" type="submit" name="callwaiter" value="Официант">
                  </form>
              </div>

              <div>
                 <!-- Кнопка вызов кальянщика ************** -->
                 <?php if($licence->getSmoke() == 1) { ?>
                  <form class="text-center" action="<?=$site?>pages/callwaiterPage.php?id=02&msg=кальян" method="POST">
                      <input class="btn btn-danger btn-lg menu-button" type="submit" name="callwaiter" value="Кальянщик">
                  </form>	
              <?php } ?>		
          </div>

          <div>
            <!-- Кнопка получить счет **************** -->
            <form class="text-center" action="<?=$site?>pages/callwaiterPage.php?id=00&msg=счет" method="POST">
              <input class="btn btn-danger btn-lg menu-button mb-2" type="submit" name="callwaiter" value="Счет">
          </form>
      </div>

      <div class="text-center mb-2">
         <!-- Кнопка оставить отзыв **************** -->
         <a class="a-button" href="<?=$site?>pages/reviewPage.php?id=01">Отзыв</a>
     </div>

     <!-- <div class="text-center">
         Кнопка личный кабинет **************** 
         <a class="a-button" href="<?=$site?>pages/startCabinet.php">GoodMoney</a>
     </div> -->

<?php } else {
    echo '<div class="row justify-content-center m-2"><h3 class="text-center">Пожалуйста, отсканируйте QR-код</h3></div>';
} ?>
</div>

</div>

<div class="modal-footer-full-width  modal-footer">
    <button type="button" class="btn btn-dark btn-md btn-rounded" data-dismiss="modal">Закрыть</button>
</div>
</div>
</div>
</div>