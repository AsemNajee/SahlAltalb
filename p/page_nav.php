<script>
   const pageID = <?php echo (is_numeric($page['id']) ? $page['id'] : '\'' . $page['id'] . '\'')?>;
   const link = <?php echo '\'' . $_SERVER['PHP_SELF'] . '\''?>;
</script>
   

<a href=<?php echo './../profile.php?id=' . $page['id']?>>
<?php $page_dir = ((isset($page['dir'])) ? $page['dir'] : 'rtl') ?>
   <div class="page-title" dir="<?php echo $page_dir; ?>">
      <?php if($page['id'] != 'favorite'){ ?>
         <div class="share button-list">
            <?php if(($sign) and $_SESSION['id'] == $page['creator'] ){ ?>
               <a href="<?php echo './../add_page.php?page_id=' . $page['id'] . '&mode=edit' ?>">
               <div class="item">
                  <i class="fas fa-pen"></i>
                  <span>تعديل</span>
               </div>
               </a>
               <a href="<?php echo './../add_post.php?page_id=' . $page['id'] . '&mode=new' ?>">
               <div class="item">
                  <i class="fas fa-square-pen"></i>
                  <span>انشاء منشور</span>
               </div>
               </a>
            <?php } if($sign){ ?>
               <a href="<?php echo (($_SESSION['id'] == $page['creator'] ) ? './../users.php?id=' . $page['id'] : '') ?>">
               <div class="item">
                  <i class="fas fa-users"></i>
                  <span><?php echo get_short_nembers($page['members'])?></span>
               </div>
               </a>
               <div id="sub" onclick="subscripe()" class="item <?php echo ((isset($user_subscripes[$page['id']])) ? ' on' : '')?>">
                  <i class="fas fa-bolt"></i>
                  <span><?php echo ((isset($user_subscripes[$page['id']])) ? 'الغاء الاشتراك' : 'اشتراك')?></span>
               </div>
               <a href=<?php echo './../profile.php?id=' . $page['id']?>>
                  <div class="item">
                     <i class="fas fa-circle-info"></i>
                     <span>معلومات</span>
                  </div>
               </a>
               <?php } ?>
               <div onclick="share()" class="item">
                  <i class="fas fa-share"></i>
                  <span>مشاركة</span>
               </div>
         </div>
      <?php } ?>
      <h2 id="page-title"><?php echo $page['title']?></h2>
   </div>
</a>

