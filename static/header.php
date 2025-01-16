<!-- updated  -->
</head>
<body dir='rtl' lang="ar">
   <header>
      <div class="container">
         <a href=<?php echo $root . (($sign) ? 'profile.php?id=' . $_SESSION['id'] : 'login.php')?>>
            <div class="profile">
               <?php if($sign){ ?>
                  <div class="image">
                     <i id="user-icon" class="fas fa-2x"></i>
                  </div>
                  <div class="dis">
                     <h4 id="user-name"></h4>
                     <p id="user-status"></p>
                  </div>
               <?php }else{ ?>
                  <div class="signup">
                     تسجيل الدخول
                  </div>
               <?php } ?>
            </div>
         </a>
         <div class="logo">
            <span>سهل الطالب</span>
         </div>
         <div class="menu">
            <ul id="list">
               <li><a id="home-page">الرئيسية</a></li>
               <li><a id="favorite-page">المفضلة</a></li>
               <?php if(isset($_SESSION['status']) and $_SESSION['status'] >= STATUS_POSTER){ ?>
                  <li><a id="exams-page">انشاء اختبار</a></li>
                  <li><a id="add-page">انشاء صفحة</a></li>
               <?php } ?>
               <li><a id="privacy-page">سياسة الخصوصية</a></li>
               <li><a id="contact-page">اتصل بنا</a></li>
               <li><a id="about-page">عنا</a></li>
            </ul>
            <i class="fas fa-bars-staggered" id="menu-btn"></i>
         </div>
      </div>
   </header>

   <script>
      document.getElementById('home-page').href = root + 'index.php';
      document.getElementById('favorite-page').href = root + 'p/index.php?page_id=favorite';
      <?php if(isset($_SESSION['status']) and $_SESSION['status'] >= STATUS_POSTER){ ?>
         document.getElementById('exams-page').href = root + 'add_exam.php';
         document.getElementById('add-page').href = root + 'add_page.php';
      <?php } ?>
      document.getElementById('privacy-page').href = root + 'privacypolicy.php';
      document.getElementById('contact-page').href = root + 'contact.php';
      document.getElementById('about-page').href = root + 'about.php';

      const menu = document.getElementById('menu-btn');
      menu.onclick = function (){
         if(menu.classList.contains('visible')){
            document.getElementById('list').style.display = 'none';
            menu.classList.remove('visible');
            menu.classList.add('fa-bars-staggered');
            menu.classList.remove('fa-xmark');
         }else{
            document.getElementById('list').style.display = 'flex';
            menu.classList.add('visible');
            menu.classList.remove('fa-bars-staggered');
            menu.classList.add('fa-xmark');
         }
      }

      <?php if($sign){ ?>
      function getIconUser(icon){
         const allIcons = {
            '-1':{'color':'#ff0000','icon':'fa-wrong','text':'محظور'},
         1:{'color':'#000','icon':'fa-user','text':'مستخدم'},
         2:{'color':'#000','icon':'fa-user','text':'مستخدم'},
         3:{'color':'#000','icon':'fa-user-graduate','text':'طالب'},
         4:{'color':'#000','icon':'fa-chalkboard-user','text':'معلم'},
         5:{'color':'#000','icon':'fa-user-check','text':'موثق'},
         6:{'color':'#000','icon':'fa-user-tag','text':'مميز'},
         7:{'color':'#000','icon':'fa-user-pen','text':'كاتب'},
         8:{'color':'#666','icon':'fa-user-tie','text':'ناشر'},
         9:{'color':'#000','icon':'fa-user-tie','text':'مشرف'},
         10:{'color':'#1197bc','icon':'fa-user-gear','text':'مطور'},
         }
         return allIcons[icon];
      }
      const i = document.getElementById('user-icon');
      i.classList.add(getIconUser(myData['status'])['icon'])
      i.style.color = getIconUser(myData['status'])['color'];
      i.parentElement.style.backgroundColor = getIconUser(myData['status'])['color'] + '10';
      const myName = document.getElementById('user-name');
      myName.innerText = myData['name'];
      myName.style.color = getIconUser(myData['status'])['color'];
      myName.style.fontWeight = 'bold';
      document.getElementById('user-status').innerText = getIconUser(myData['status'])['text'];
      <?php } ?>
   </script>