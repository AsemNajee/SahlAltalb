<?php
// done in update
   require_once 'check.php';
   $all_pages = getPagesData();
?>

<div class="page-title">
   <div class="button-list">
      <div id="all" class="super-item item " onclick="viewPages('all')">
         <i class="fas fa-home"></i>
         <span>الكل</span>
      </div>
      <div id="12" class="super-item item  " onclick="viewPages('12')">
         <i class="fas fa-newspaper"></i>
         <span>العادية</span>
      </div>
      <div id="13" class="super-item item" onclick="viewPages('13')">
         <i class="fas fa-bullhorn"></i>
         <span>الموثقة</span>
      </div>
      <div id="my-sub" class="super-item item" onclick="viewPages('my-sub')">
         <i class="fas fa-bolt"></i>
         <span>الاشتراكات</span>
      </div>
      <div id="15" class="super-item item" onclick="viewPages('15')">
         <i class="fas fa-landmark"></i>
         <span>الاساسية</span>
      </div>
      <div id="16" class="super-item item" onclick="viewPages('16')">
         <i class="fas fa-circle-check"></i>
         <span>الرسمية</span>
      </div>
      <div id="exams" class="super-item item " onclick="viewPages('exams')">
         <i class="fas fa-lightbulb"></i>
         <span>الاختبارات</span>
      </div>
   </div>
</div>
   <main class="section flow">
      <div class="container main-container">
         <div id="grid" class="grid">
           
         </div>
      </div>
      <!-- <a href="index.php?view=12" class="button flow-btn">عرض الكل</a> -->
   </main>
<script>
   onload = function(){
      viewPages('all');
   }
   const pages = <?php echo json_encode($all_pages) ?> ;

   const pageIcons = {
      12:{'color':'#deb887','icon':'fa-newspaper'},
      13:{'color':'#4a7fa0','icon':'fa-bullhorn'},
      14:{'color':'#deb887','icon':'fa-landmark'},
      15:{'color':'#deb887','icon':'fa-landmark'},
      16:{'color':'#2e8b57','icon':'fa-circle-check'},
      'private':{'color':'#7e6f6f','icon':'fa-lock'},
      'public':{'color':'#900300','icon':'fa-circle-question'},
   }
   <?php if(isset($_SESSION['id'])){ ?>
      const mySub = <?php echo json_encode(get_user_subscripes($_SESSION['id'])) ?> ;
   <?php }else{ ?>
      document.getElementById('my-sub').style.display = 'none';
   <?php } ?>
   function viewPages(type){
      turn(type);
      const grid = document.getElementById('grid');
      grid.innerHTML = '';
      if(type >= 12 && type <= 16 ){
         for(let id in pages){
            if(pages[id]['type'] == type){
               grid.appendChild(printPage(pages[id]));
            }
         }
      }else if(type == 'my-sub'){
         for(let id in mySub){
            grid.appendChild(printPage(pages[id]));
         }
      }else if(type == 'all'){
         for(let id in pages){
            grid.appendChild(printPage(pages[id]));
         }
      }else if(type == 'exams'){
         exams = <?php echo json_encode(get_all_exams()); ?>;
         for(let id in exams){
            grid.appendChild(printPage(exams[id]));
         }
      }
   }
   function getExams(){
      const output = fetch('./api/get_all_exams.php')
      .then(response => response.json())
      .then(data => {
         if(data['ok'] == 'succes'){
            return data['data'];
         }else{
            return 'no exams';
         }
      })
      return output;
   }
   function printPage(page){
      const pagediv = document.createElement('div');
      pagediv.classList.add('page-templete');
      const icon = document.createElement('div');
      icon.classList.add('icon');
      const info = document.createElement('div');
      info.classList.add('info');
      let i = document.createElement('i');
      i.classList.add('fas');
      i.classList.add(pageIcons[page['type']]['icon']);
      i.style.color = pageIcons[page['type']]['color'];
      icon.style.backgroundColor = pageIcons[page['type']]['color'] + '0f';
      icon.style.border = '2px solid ' + pageIcons[page['type']]['color'] + '58';
      i.classList.add('fa-3x');
      icon.appendChild(i);
      pagediv.appendChild(icon);
      const h3 = document.createElement('h3');
      h3.innerHTML = (page['title']);
      const p = document.createElement('p');
      p.innerHTML = (page['bio']);
      info.appendChild(h3);
      info.appendChild(p);
      const about = document.createElement('div');
      about.classList.add('about');
      i = document.createElement('i');
      i.classList.add('fas');
      i.classList.add('fa-user');
      const span = document.createElement('span');
      span.innerHTML = (page['members']);
      about.appendChild(i);
      about.appendChild(span);
      info.appendChild(about);
      pagediv.appendChild(info);
      const a = document.createElement('a');
      a.href =  'p/index.php?page_id=' + page['id'];
      a.appendChild(pagediv);
      return a;
   }
   function turn(type){
      // const items = document.getElementsByClassName('super-item');
      // for(item in items){
      //    // console.log(items[item].classList);
      //    itemm= items[item].classList;
      //    itemm.remove('on');
      //    console.log(itemm);
      //    // items
      //    ;
      // }
      const item12 = document.getElementById('12');
      const item13 = document.getElementById('13');
      const item15 = document.getElementById('15');
      const exam = document.getElementById('exams');
      const my = document.getElementById('my-sub');
      const all = document.getElementById('all');
      const item16 = document.getElementById('16');
      item16.classList.remove('on');
      item12.classList.remove('on');
      item13.classList.remove('on');
      item15.classList.remove('on');
      exam.classList.remove('on');
      all.classList.remove('on');
      my.classList.remove('on');
      document.getElementById(type).classList.add('on');
   }

</script>
<?php
   require_once 'static/footer.php';
?>