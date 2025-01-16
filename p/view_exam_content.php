
<main class="section">
      <div class="container main-container messages-list">
         <div class="item"> <!--  to cancel streach in the first time -->
            <div class="notify">
               <i class="fab fa-gauge"></i>
               <span><?php echo 'this exam will end after ' . $exam['time'] . ' hours' ?></span>
            </div>
         </div>
         <div class="grid">
            <?php if(isset($questions) and !empty($questions)){
               foreach($questions as $q){?>
               <div class="exam"> <!--  to cancel streach in the first time -->
                  <h3><?php echo $q['title'] ?></h3>
                  <div class="button-list grow">
                     <?php 
                     shuffle($q['choose']);
                     foreach($q['choose'] as $ch){?>
                     <div class="super-item item">
                        <i class="fas fa-circle"></i>
                        <span><?php echo $ch ?></span>
                     </div>
                     <?php } ?>
                  </div>
                  </div>
               <?php }
            } ?>
         </div>
         <input type="submit" class="button" name="ارسال الاجابة">
      </div>
</main>