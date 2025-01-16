<?php


require_once 'check.php';


if(isset($_GET['search'])){
   $phrese = htmlentities($_GET['search']);
   $pages = search($phrese);
}

?>

</head>
<!-- <header class="header-search">
   </header> -->
   <div class="search-container">
      <div class="search-bar">
         <a href="search.php?search=" ></a><i class="fas fa-search"></i>
         <input class="text-input" type="text" placeholder="search" value="<?php echo $phrese?>">
      </div>
   </div>

   <main>
      <div class="container">
      <div id="grid" class="grid">
            
         </div>
      </div>
   </main>
<script>
onload = function(){
      viewPages('all');
   }
   const pages = <?php echo json_encode($pages) ?> ;

   const pageIcons = {
      12:{'color':'#deb887','icon':'fa-newspaper'},
      13:{'color':'#4a7fa0','icon':'fa-bullhorn'},
      14:{'color':'#deb887','icon':'fa-landmark'},
      15:{'color':'#deb887','icon':'fa-landmark'},
      16:{'color':'#2e8b57','icon':'fa-circle-check'},
      'private':{'color':'#7e6f6f','icon':'fa-lock'},
      'public':{'color':'#900300','icon':'fa-circle-question'},
   }
   function viewPages(type){
      // turn(type);
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
   // function getExams(){
   //    const output = fetch('./api/get_all_exams.php')
   //    .then(response => response.json())
   //    .then(data => {
   //       if(data['ok'] == 'succes'){
   //          return data['data'];
   //       }else{
   //          return 'no exams';
   //       }
   //    })
   //    return output;
   // }
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
</script>
