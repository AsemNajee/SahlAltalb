<?php 

// error_reporting(0);
/*
define('LANGUAGE' , 'ar');

define('DATA_ROOT' ,  LOCAL_ROOT . 'data/');
define('FILE_PAGES' , DATA_ROOT . 'pages.json');
define('FILE_EXAMS' , DATA_ROOT . 'exams.json');
define('FILE_USERS' , DATA_ROOT . 'users.json');
define('ID_PAGE' , -1001);
define('ID' , 1001);
define('ENCRIPT' , 'sahlAltalb.net');

define('MAX_POST_TITLE_LETTERS' , 70);
define('MAX_CONTENT_LETTERS' , 1000);

define('MIN_USERNAME_LETTERS' , 5);
define('MAX_USERNAME_LETTERS' , 16);
define('MIN_NAME_LETTERS' , 3);
define('MAX_NAME_LETTERS' , 30);
define('MIN_PASSWORD_LETTERS' , 4);
define('MAX_PASSWORD_LETTERS' , 16);

define('MAX_BIO_LETTERS' , 70);
define('MAX_TAGS_LETTERS' , 100);
define('MAX_TAGS_COUNT' , 100);


define('STATUS_TEACHER' , 4);
define('STATUS_REAL' , 5);
define('STATUS_VIP' , 6);
define('STATUS_WRITER' , 7);
define('STATUS_POSTER' , 8);
define('STATUS_ADMIN' , 9);
define('STATUS_OWNER' , 10);

function is_user($user , $status){
   if(isset($user['status']) and $user['status'] >= $status){
      return true;
   }else{
      return false;
   }
}

// print_r(FILE_PAGES);
// header('Location: ' . FILE_PAGES);


function add_to_favorite($page_id , $post_id , $user_id){
   if(($posts = get_all_messages(get_page($page_id))) == false){
      return false;
   }
   if($post_id > count($posts)){
      return false;
   }
   if(file_exists(DATA_ROOT . '/users/' . $user_id . '/favorite.json')){
      if(($data = file_get_contents(DATA_ROOT . '/users/' . $user_id . '/favorite.json')) != false){
         $data = json_decode($data ,  true);
      }else{
         mkdir(DATA_ROOT . '/users/' . $user_id , 0777 , true);
         $data = [];
      }
   }else{
      $data = [];
   }
   if(isset($data[$page_id . ':' . $post_id])){
      unset($data[$page_id . ':' . $post_id]);
      $output = 'deleted';
   }else{
      $posts[$post_id]['id'] = $page_id . ':' . $post_id;
      $data[$page_id . ':' . $post_id] = $posts[$post_id];
      $output = 'added';
   }
   file_put_contents(DATA_ROOT . '/users/' . $user_id . '/favorite.json' , json_encode($data));
   return $output;
}

function check_username($username){
   if(!file_exists(FILE_USERS)){
      return false;
   }
   if(is_numeric(substr($username , 0 , 1))){
      return true;
   }
   if(($data = file_get_contents(FILE_USERS)) != false){
      $data = json_decode($data, true);
      foreach($data as $user){
         if(strtolower($user['username']) == strtolower($username)){
            return true;
         }
      }
      return false;
   }
   return false;
}
function check_login($user_id , $password){
   if(!file_exists(FILE_USERS)){
      return false;
   }
   if(($data = file_get_contents(FILE_USERS)) != false){
      $data = json_decode($data, true);
      if(is_numeric($user_id)){
         if(isset($data[$user_id])){
            if($data[$user_id]['password'] == md5($password . ENCRIPT)){
               return $user_id;
            }else{
               return false;
            }
         }else{
            return false;
         }
      }else{
         foreach($data as $id => $user){
            if($user['username'] == $user_id){
               if($user['password'] == md5($password . ENCRIPT)){
                  return $id;
               }else{
                  return false;
               }
            }
         }
      }
      return false;
   }
   return false;
}
function check_session($session){
   if(isset($_COOKIE['userinfo'])){
      $info = json_decode($_COOKIE['userinfo'] , true);
      if(isset($info['id']) and isset($info['password'])){
         return check_login($info['id'] , $info['password']);
      }else{
         return false;
      }
   }else{
      return false;
   }
}
function check_user_data($input){
   if(isset($input['name'] , $input['username'])){
      if(strlen($input['name']) >= MIN_NAME_LETTERS and strlen($input['username']) >= MIN_USERNAME_LETTERS){
         if($input['username'] == $_SESSION['username']){
            return true;
         }
         return !check_username($input['username']);
      }else{
         return false;
      }
   }else{
      return false;
   }
}
function create_page($page){
   if(($pages = file_get_contents(FILE_PAGES)) != false){
      $pages = json_decode($pages, true);
   }else{
      $pages = [];
   }
   $temp['title'] = $page['title'];
   $temp['type'] = '12';
   $temp['bio'] = $page['bio'];
   $temp['tags'] = $page['tags'];
   $temp['dir'] = $page['dir'];
   $temp['date'] = (int)(time());
   $temp['creator'] = $_SESSION['id'];
   $temp['members'] = 1;
   $temp['id'] = ID_PAGE - count($pages);
   $temp['posts'] = 1;
   $path = get_page_path($temp);
   $pages[$temp['id']] = $temp;
   save_data(FILE_PAGES , json_encode($pages));
   // file_put_contents(FILE_PAGES , json_encode($pages)); // add to main file 'PAGES' . 
   $post['create']['type'] = 'notify';
   $post['create']['content'] = 'created';
   $post['create']['date'] = $temp['date'];
   mkdir($path . 'posts/' , 0777 , true);
   save_data(($path . 'posts/' . get_file_name($temp['posts'] , 'post') . '.json') , json_encode($post));
   // file_put_contents($path . 'posts.json' , json_encode($post));
   return $temp['id'];
}
function create_post($post , $page){
   $path = get_page_path($page) . 'posts/';
   $page['posts'] += 1;
   if(isset($page['posts'])){
      $path .= get_file_name($page['posts'] , 'post');
   }else{
      $path .= 'posts';
   }
   $path .= '.json';
   if(($posts = file_get_contents($path)) != false){
      $posts = json_decode($posts, true);
   }else{
      $posts = [];
   }
   if(isset($post['type'])){
      $temp['type'] = $post['type'];
   }
   $temp['title'] = $post['title'];
   $temp['content'] = $post['content'];
   $temp['dir'] = (isset($post['dir']) ? $post['dir'] : $page['dir']);
   if(isset($post['url'])){
      $temp['url'] = $post['url'];
   }
   if(isset($post['code'])){
      $temp['code'] = $post['code'];
   }
   $temp['date'] = (int)(time());
   $temp['poster_id'] = $_SESSION['id'];
   $temp['poster'] = $_SESSION['name'];
   $temp['views'] = 1;
   $temp['id'] = $page['posts'];
   $posts[$temp['id']] = $temp;
   save_data($path , json_encode($posts));
   edit_page($page, $page);
   // file_put_contents($path , json_encode($posts));
   return $temp['id'];
}
function create_question($ques , $exam){
   $path = get_exam_path($exam);
   if(($questions = file_get_contents($path)) != false){
      $questions = json_decode($questions, true);
   }else{
      $questions = [];
   }
   $temp['title'] = $ques['title'];
   $temp['choose'] = $ques['choose'];
   $temp['id'] = count($questions);
   $questions[$temp['id']] = $temp;
   file_put_contents($path , json_encode($questions));
   return $temp['id'];
}
function create_exam($exam){
   if(($exams = file_get_contents(FILE_EXAMS)) != false){
      $exams = json_decode($exams, true);
   }else{
      $exams = [];
   }
   $temp['title'] = $exam['title'];
   $temp['type'] = $exam['type'];
   $temp['bio'] = $exam['bio'];
   $temp['time'] = $exam['time'];
   $temp['dir'] = $exam['dir'];
   $temp['date'] = (int)(time());
   $temp['creator'] = $_SESSION['id'];
   $temp['members'] = 0;
   $temp['id'] = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz') , -16);
   $exams[$temp['id']] = $temp;
   file_put_contents(FILE_EXAMS , json_encode($exams)); // add to main file 'exams' . 
   return $temp['id'];
}
function check_page_status($status){
   if($status >= 12 and $status <= 16){
      return true;
   }else{
      return false;
   }
}
function edit_user($user){
   if(($data = filter_user_data($user)) != false){
      if(check_username($data['username'])){
         return false;
      }
      $users = get_all_users();
      if(isset($users[$_SESSION['id']])){
         $users[$_SESSION['id']]['name'] = $data['name'];
         $users[$_SESSION['id']]['username'] = $data['username'];
         $users[$_SESSION['id']]['bio'] = $data['bio'];
         $users[$_SESSION['id']]['tags'] = $data['tags'];
         file_put_contents(FILE_USERS , json_encode($users));
         return true;
      }else{
         session_destroy();
         setcookie('userinfo', '');
         header('Location: login.php');
         exit;
      }
   }else{
      return false;
   }
}
function edit_page($new_page , $page){
   $pages = get_all_pages();
   $page = $pages[$page['id']];
   $page['title'] = $new_page['title'];
   $page['bio'] = $new_page['bio'];
   $page['tags'] = $new_page['tags'];
   $page['dir'] = $new_page['dir'];
   $page['posts'] = $new_page['posts'];
   $pages[$page['id']] = $page;
   save_data(FILE_PAGES, json_encode($pages));
   return $page['id'];
}
function edit_post($new_data , $page , $post_id){
   if($post_id > $page['posts']){
      return false;
   }
   $path = get_page_path($page) . 'posts/';
   $path .= get_file_name($post_id , 'post') . '.json';
   
   if(($all_posts = get_all_messages($page , true)) == false){
      return false;
   }
   if(!isset($all_posts[$post_id])){
      return false;
   }
   $path = get_page_path($page) . 'posts.json';
   $temp = $all_posts[$post_id];
   $temp['type'] = $new_data['type'];
   $temp['title'] = $new_data['title'];
   $temp['content'] = $new_data['content'];
   $temp['dir'] = $new_data['dir'];
   if(isset($new_data['url'])){
      $temp['url'] = $new_data['url'];
   }else{
      unset($temp['url']);
   }
   if(isset($new_data['code'])){
      $temp['code'] = $new_data['code'];
   }else{
      unset($temp['code']);
   }
   if(isset($new_data['views'])){
      $temp['views'] = $new_data['views'];
   }
   $temp['edit'] = (int)(time());
   $all_posts[$post_id] = $temp;
   save_data($path , json_encode($all_posts));
   return $temp['id'];
}

function str_filter($string){
   $output = htmlentities($string);
   $output = str_replace('  ', ' ' , $output);
   $output = trim($output);
   return $output;
}

function filter_page_data($input){
   $output = null;
   if(isset($input['title']) and !empty($input['title'])){
      $output['title'] = substr($input['title'] , 0 , MAX_NAME_LETTERS);
      $output['title'] = str_filter($output['title']);
   }
   if(isset($input['bio']) and !empty($input['bio'])){
      $output['bio'] = substr($input['bio'] , 0 , MAX_BIO_LETTERS);
      $output['bio'] = str_filter($output['bio']);
   }
   if(isset($input['dir']) and !empty($input['dir'])){
      $output['dir'] = ($input['dir'] == 'ltr' ? 'ltr' : 'rtl');
   }
   if(isset($input['tags']) and !empty($input['tags'])){
      $output['tags'] = substr($input['tags'] , 0 , MAX_TAGS_LETTERS);
      $output['tags'] = str_filter($output['tags']);
      $output['tags'] = explode(',' , $output['tags']);
   }
   if(isset($output['title'] , $output['bio'] , $output['tags']) and !(empty($output['title']) or empty($output['bio']) or empty($output['tags']))){
      return $output;
   }else{
      return false;
   }
}
function filter_post_data($input){
   $output = null;
   if(isset($input['title']) and !empty($input['title'])){
      $output['title'] = substr($input['title'] , 0 , MAX_POST_TITLE_LETTERS);
      $output['title'] = str_filter($output['title']);
   }
   if(isset($input['content']) and !empty($input['content'])){
      $output['content'] = substr($input['content'] , 0 , MAX_CONTENT_LETTERS);
      $output['content'] = str_filter($output['content']);
   }
   if(isset($input['type']) and !empty($input['type'])){
      $output['type'] = preg_replace('/[^a-zA-Z0-9_ ]/' , '' , $input['type']);
   }
   if(isset($input['dir']) and !empty($input['dir'])){
      $output['dir'] = ($input['dir'] == 'ltr' ? 'ltr' : 'rtl');
   }
   if(isset($input['url']) and !empty($input['url'])){
      $output['url'] = filter_var($input['url'] , FILTER_SANITIZE_URL);
   }
   if(isset($input['code']) and !empty($input['code'])){
      $output['code'] = '<pre><code>' . htmlentities($input['code']) . '</code></pre>';
   }
   if(isset($output['title'] , $output['content']) and !(empty($output['title']) or empty($output['content']))){
      return $output;
   }else{
      return false;
   }
}
function filter_question_data($input){
   // print_r($input);
   $output = null;
   if(isset($input['title']) and !empty($input['title'])){
      $output['title'] = substr($input['title'] , 0 , MAX_POST_TITLE_LETTERS);
      $output['title'] = str_filter($output['title']);
   }
   if(isset($input['choose']) and isset($input['choose']['answer'])){
      $output['choose'] = $input['choose'];
   }
   if(isset($output['title'] , $output['choose']) and !(empty($output['title']) or empty($output['choose']))){
      return $output;
   }else{
      return false;
   }
}
function filter_user_data($input){
   $output = null;
   if(isset($input['name'])){
      $output['name'] = substr($input['name'] , 0 , MAX_NAME_LETTERS);
      $output['name'] = str_filter($output['name']);
   }
   if(isset($input['username'])){
      $output['username'] = preg_replace('/[^a-zA-Z0-9_]/' , '' , $input['username']);
      $output['username'] = substr($output['username'] , 0 , MAX_USERNAME_LETTERS);
   }
   if(isset($input['bio'])){
      $output['bio'] = substr($input['bio'] , 0 , MAX_BIO_LETTERS);
      $output['bio'] = str_filter($output['bio']);
   }
   if(isset($input['tags'])){
      $output['tags'] = substr($input['tags'] , 0 , MAX_TAGS_LETTERS);
      $output['tags'] = str_filter($output['tags']);
      $output['tags'] = explode(',' , $output['tags']);
   }
   if(check_user_data($output) != false){
      return $output;
   }else{
      return false;
   }
}
function filter_exam_data($input){
   $output = null;
   if(isset($input['title']) and !empty($input['title'])){
      $output['title'] = substr($input['title'] , 0 , MAX_NAME_LETTERS);
      $output['title'] = str_filter($output['title']);
   }
   if(isset($input['bio']) and !empty($input['bio'])){
      $output['bio'] = substr($input['bio'] , 0 , MAX_BIO_LETTERS);
      $output['bio'] = str_filter($output['bio']);
   }
   if(isset($input['dir']) and !empty($input['dir'])){
      $output['dir'] = ($input['dir'] == 'ltr' ? 'ltr' : 'rtl');
   }
   if(isset($input['time']) and !empty($input['time'])){
      $output['time'] = ($input['time'] < 100 and $input['time'] > 0) ? $input['time'] : 24;
   }
   $output['type'] = 'private';
   if(isset($output['title'] , $output['bio'] , $output['time']) and !(empty($output['title']) or empty($output['bio']) or empty($output['time']))){
      return $output;
   }else{
      return false;
   }
}

function get_all_posts($path){
   if(!is_file($path)){
      return false;
   }
   return json_decode(file_get_contents($path) , true);
}
function get_session($username , $password){
   if(($id = check_login($username , $password)) != false){
      return get_user_data($id);
   }
}
function get_user_data($id , $users_data = 'all'){
   if($users_data == 'all'){
      if(!file_exists(FILE_USERS)){
         return false;
      }
      if(($data = file_get_contents(FILE_USERS)) != false){
         $data = json_decode($data, true);
      }
   }else{
      $data = $users_data;
   }
   if(isset($data[$id])){
      $info = $data[$id];
      $info['id'] = $id;
      return $info;
   }
   return false;
}
function get_all_users(){
   if(!file_exists(FILE_USERS)){
      return false;
   }
   if(($data = file_get_contents(FILE_USERS)) != false){
      return json_decode($data, true);
   }
   return false;
}
function get_all_pages(string $path = 'all'){
   if($path == 'all'){
      if(!file_exists(FILE_PAGES)){
         return false;
      }
      if(($pages = file_get_contents(FILE_PAGES)) != false){
         return json_decode($pages, true);
      }else{
         return [];
      }
   }
}
function get_page($id , $all_pages = ''){ 
   if($id == 'favorite'){
      return array(
         'id' => 'favorite',
         'type' => 'private',
         'title' => 'favorite',
         'bio' => 'all your favorite posts',
         'creator' => '',
         'members' => '0',
      );
   }
   if($all_pages == ''){
      $all_pages = get_all_pages();
   }
   if(isset($all_pages[$id])){
      return $all_pages[$id];
   }
   return false;
}
function get_posts_files($page){
   $path = get_page_path($page);
   $path .= 'posts/';
   $dir = opendir($path);
   $output = [];
   while(($file = readdir($dir)) != false){
      if($file == '.' || $file == '..'){
         continue;
      }
      $output[] = $file;
   }
   closedir($dir);
   return $output;
}
function get_all_messages($page_info , bool $checked = false){
   if($page_info['id'] == 'favorite'){
      if(file_exists(DATA_ROOT . '/users/' . $_SESSION['id'] . '/favorite.json')){
         return json_decode(file_get_contents(DATA_ROOT . '/users/' . $_SESSION['id'] . '/favorite.json') , true);
      }else{
         return false;
      }
   }
   $path = get_page_path($page_info) . 'posts/';
   $pathes = get_posts_files($page_info);
   $posts = [];
   for($i = 0, $count = count($pathes); $i < $count; $i++){
      $data = file_get_contents($path . $pathes[$i]);
      $data = json_decode($data , true);
      array_push($posts , $data);
   }
   return $posts;
   // if(isset($page_info['id']) and ( $checked or (($page_info = get_page($page_info['id'])) != false))){
   //    $content_path = get_page_path($page_info) . 'posts.json';
   //    if(file_exists($content_path)){
   //       return json_decode(file_get_contents($content_path) , true);
   //    }else{
   //       return false;
   //    }
   // }else{
   //    return false;
   // }
}
function get_icon($key , $inner_key = 0){
   $icons = array(
      'file' => ['fas fa-file','unknown','download'],
      'pdf' => ['fas fa-file-pdf','pdf','download'],
      'zip' => ['fas fa-file-zipper','ZIP','download'],
      'audio' => ['fas fa-file-audio','sound',''],
      'video' => ['fas fa-file-video','video',''],
      'file-code' => ['fas fa-file-code','code','download'],
      'word' => ['fas fa-file-word','DOC','download'],
      'notify' => ['fas fa-slack','',''],

      'user' => ['fas fa-user','',''],
      'student' => ['fas fa-user-graduate','',''],
      'graduation' => ['fas fa-graduation-cap','',''],
      'reader' => ['fas fa-book-open-reader','',''],
      'real' => ['fas fa-user-check','',''],
      'doctor' => ['fas fa-user-doctor','',''],
      'tag' => ['fas fa-user-tag','',''],
      'vip' => ['fas fa-user-tag','',''],
      'poster' => ['fas fa-user-pen','',''],
      'tie' => ['fas fa-user-tie','',''],
      'admin' => ['fas fa-user-tie','',''],
      'dev' => ['fas fa-user-gear','',''],
      'king' => ['fab fa-web-awesome','',''],
      'panned' => ['fa fa-user-slash','',''],

      'page' => ['fas fa-newspaper','PAGE',''],
      'super-page' => ['fas fa-bullhorn','SUPER PAGE',''],
      'private' => ['fas fa-lock','SUPER PAGE',''],
      'tags' => ['fas fa-tags','',''],
      'tag' => ['fas fa-tag','',''],

      'message' => ['fas fa-message','TEXT','copy'],
      'link' => ['fas fa-link','URL','GO'],
      'code' => ['fas fa-code','code','copy code'],
      'url' => ['fas fa-globe','URL','GO'],
      'exam' => ['fas fa-puzzle-piece','QUSTION',''],
      'user-link' => ['fas fa-id-card','PROFILE',''],
      'profile' => ['fas fa-id-card','PROFILE',''],
      'page-link' => ['fas fa-window-maximize','PAGE',''],

      'youtube' => ['fab fa-youtube','YouTube','watch'],
      'github' => ['fab fa-github','github','visite'],
      'google' => ['fab fa-google','google','GOOGLE'],
      'instagram' => ['fab fa-instagram','instagram','instagram'],
      'telegram' => ['fab fa-telegram','telegram','telegram'],
   );
   return isset($icons[$key]) ? $icons[$key][$inner_key] : 'fas fa-circle';
}
function get_short_nembers($number){
   $float = '';
   if($number < 900){
      return $number;
   }
   if($number < 1000000){
      if(($float = $number % 1000) > 100){
         $float = trim('.' . floor(($float*10)/1000)  , '0');
      }
      return floor($number / 1000) . $float . 'k';
   }
   if($number >= 1000000){
      if(($float = $number % 1000000) > 100000){
         $float = trim('.' . floor(($float*10)/1000000)  , '0');
      }
      return floor($number / 1000000) . $float . 'M';
   }
}
function get_short_string($string , $letters){
   if($letters < strlen($string)){
      return substr($string , 0 , $letters) . '...';
   }else{
      return $string;
   }
}
function get_page_path($page){
   if(!isset($page['creator'] , $page['id'])){
      return false;
   }
   return DATA_ROOT . 'users/' . $page['creator'] . 
         '/pages/P' . $page['id'] . '/';
}
function get_user_path($user_id){
   $path = DATA_ROOT . 'users/' . $user_id . '/';
   if(!is_dir($path)){
      mkdir($path , 777 , true);
   }
   return $path;
}
function get_exam_path($exam){
   if(!isset($exam['creator'])){
      return false;
   }
   $path = DATA_ROOT . 'users/' . $exam['creator'] ; 
   if(!is_dir($path)){
      mkdir($path , 777 , true);
   }
   return $path . '/exam.json';
}
function get_user_subscripes($user_id){
   $path = get_user_path($user_id);
   if(!is_dir($path)){
      return false;
   }
   if(!file_exists($path . 'subscripe.json')){
      return false;
   }
   return json_decode(file_get_contents($path . 'subscripe.json') , true);
}
function get_singular($num, $one, $plural, $tow){
   if($num == 1){
      return 'قبل ' . $one;
   }
   if($num == 2){
      return 'قبل '. $tow;
   }
   if($num <=  10){
      return 'منذ ' . $num . ' ' .$plural;
   }
   return 'منذ ' . $num . ' ' . $one;
}
function get_time($date){
   $now = time();
   $time = $now - $date;
   if($time < 60 ){
      return 'الان';
   }
   if($time < 60 * 60){
      $num = (int)($time / 60) ;
      $txt = get_singular($num, 'دقيقة','دقائق','دقيقتين');
   }else
   if($time < (60 * 60 * 24)){
      $num = (int)floor($time / (60 * 60 ));
      $txt = get_singular($num, 'ساعة','ساعات','ساعتين');
   }else
   if($time < 60 * 60 * 24 * 2){
      $txt = 'امس';
   }else
   if($time < 60 * 60 * 24 * 7){
      $num = (int)($time / (60 * 60 * 24 )) ;
      $txt = get_singular($num, 'يوم','ايام','يومين');
   }else
   if($time < 60 * 60 * 24 * 7 * 4){
      $num = (int)($time / (60 * 60 * 24 * 7)) ;
      $txt = get_singular($num, 'اسبوع','اسابيع','اسبوعين');
   }else
   if($time < 60 * 60 * 24 * 7 * 4 * 12){
      $d = getdate($date);
      $txt =  $d['mday'] . ' ' . $d['month'] . ' ' ;
   }else{
      $d = getdate($date);
      $txt =  $d['mday'] . ' ' . $d['month'] . ' ' . $d['year'];
   }
   return ' ' . $txt;
}

function is_favorite($page_id , $post_id , $user_id){
   // if(($messages = get_all_messages(get_page($page_id))) == false){
   //    return false;
   // }
   // if($post_id > count($messages)){
   //    return false;
   // }
   $path = DATA_ROOT . '/users/' . $user_id . '/favorite.json';
   if(!file_exists($path)){
      return false;
   }
   if(($data = file_get_contents($path))){
      $data = json_decode($data ,  true);
      return (isset($data[$page_id . ':' . $post_id]));
   }else{
      return false;
   }
}
function is_page($page){
   return file_exists(get_page_path($page));
}

function like($page_info , $post_id, $user_id , $state){
   if(($posts = get_all_messages($page_info)) == false){
      return false;
   }
   if(!isset($posts[$post_id])){
      return false;
   }
   $post = $posts[$post_id];
   if(isset($post['likes'][$user_id]) and ($state == $post['likes'][$user_id])){
      unset($post['likes'][$user_id]);
      $output = 'deleted';
   }else{
      $post['likes'][$user_id] = $state;
      $output = 'added';
   }
   $posts[$post_id] = $post;
   $content_path = get_page_path($page_info) . 'posts.json';
   file_put_contents($content_path , json_encode($posts));
   return $output;
}
function login($data_user){
   if(check_username($data_user['username']) == false){
      if(($data = file_get_contents(FILE_USERS)) != false){
         $data = json_decode($data, true);
      }else{
         $data = [];
      }
      $temp['name'] = htmlentities($data_user['name']);
      $temp['username'] = $data_user['username'];
      $temp['password'] = md5($data_user['password'] . ENCRIPT);
      $temp['date'] = (int)(time());
      $id = ID + count($data);
      $data[$id] = $temp;
      if(!is_dir(DATA_ROOT)){
         mkdir(DATA_ROOT , 0777 , true);
      }
      file_put_contents(FILE_USERS , json_encode($data, true));
      $temp['id'] = $id;
      return $temp;
   }else{
      return false;
   }
}

function set_status($id , $status){
   if(check_page_status($status) != false){
      if($id > 1000){
         $users = get_all_users();
         if(isset($users[$id])){
            $users[$id]['status'] = $status;
            file_put_contents(FILE_USERS , json_encode($users));
            return true;
         }else{
            return false;
         }
      }else if($id < -1000){
         $pages = get_all_pages();
         if(isset($pages[$id])){
            $pages[$id]['type'] = $status;
            file_put_contents(FILE_PAGES , json_encode($pages));
            return true;
         }else{
            return false;
         }
      }else{
         return false;
      }
   }else{
      return false;
   }
}
function subscripe($user_id , $page_id){
   $users = get_all_users();
   $pages = get_all_pages();
   if(!isset($users[$user_id])){
      return false;
   }
   if(!isset($pages[$page_id])){
      return false;
   }
   $page = $pages[$page_id];
   $user = $users[$user_id];
   $path = get_page_path($page);
   $user_path = get_user_path($user_id);
   if(!file_exists($path . 'users.json')){
      mkdir($path , 0777 , true);
      $page_users = [];
   }else{
      $page_users = file_get_contents($path . 'users.json');
      $page_users = json_decode($page_users , true);
   }
   $user_pages = file_get_contents($user_path . 'subscripe.json');
   $user_pages = json_decode($user_pages , true);
   if(isset($user_pages[$page_id])){
      unset($user_pages[$page_id]);
      unset($page_users[$user_id]);
      $output = 'leave';
      $num = -1;
   }else{
      $temp['name'] = $user['name'];
      $temp['status'] = 'user';
      $page_users[$user_id] = $temp;
      $user_pages[$page_id] = time();
      $output = 'join';
      $num = 1;
   }
   $page['members'] += $num;
   $pages[$page['id']] = $page;
   file_put_contents(FILE_PAGES , json_encode($pages));
   file_put_contents($path . 'users.json' , json_encode($page_users));
   file_put_contents($user_path . 'subscripe.json' , json_encode($user_pages));
   return $output;
}

function get_url_data($url){
   if(empty($url)){
      return ['type'=> false , 'data'=> 'the url is empty'];
   }
   if(!str_contains($url , 'p/')){
      return ['type'=> false , 'data'=> 'unknwon url'];
   }
   $ids = explode('/' , explode('p/' , $url)[1]);
   if(count($ids) == 1 and !is_numeric($ids[0]) and $ids[0] != 'favorite'){
      if(($exam = get_exam_data($ids[0])) != false){ 
         if(($ques = get_all_questions($exam)) != false){
            return ['type'=> 'exam' , 'data'=> $ques , 'exam' => $exam];
         }
      }
      return ['type'=> false , 'data'=> 'not exam url'];
   }
   if(count($ids) > 2){
      return ['type'=> false , 'data'=> 'long url'];
   }
   if(count($ids) == 2){
      $page_id = $ids[0];
      $post_id = $ids[1];
      if(($post = getPostData(($page = get_page($page_id)), $post_id)) == false){
         return ['type'=> false , 'data'=> 'wrong page id'];
      }else{
         return ['type'=> 'post' , 'data'=> $post , 'page'=> $page];
      }
      return ['type'=> false , 'data'=> 'wrong post id'];
   }
   if(count($ids) == 1){
      if(($page = get_page($ids[0])) != false){
         return ['type'=> 'page' , 'page'=> $page];
      }
   }
   return ['type'=> false , 'data'=> 'unknown wrong'];
}
function get_all_exams(){
   if(!file_exists(FILE_EXAMS)){
      return false;
   }
   if(($exams = file_get_contents(FILE_EXAMS)) != false){
      return json_decode($exams , true);
   }
   return false;
}
function get_exam_data($id){
   if(($exams = get_all_exams()) != false){
      if(isset($exams[$id])){
         return $exams[$id];
      }
   }
   return false;
}
function get_all_questions($exam){
   if(!isset($exam['creator'])){
      return false;
   }
   if(!file_exists(($path = get_exam_path($exam)))){
      return false;
   }
   if(($queses = file_get_contents($path)) != false){
      return json_decode($queses, true);
   }
   return false;
}
function get_preview($local_url){
   if(empty($local_url)){
      return ['type'=> false , 'data'=> 'the url is empty'];
   }
   if(!str_contains($local_url , 'profile.php?')){
      $url = str_replace(['page_id=', 'post_id=' , '&', '?', 'index.php/'] , ['/','/','', '', ''] , $local_url);
      if(($data = get_url_data($url))['type'] == 'post'){
         $output['title'] = $data['data']['title'];
         $output['bio'] = $data['data']['content'];
         $output['type'] = $data['data']['type'];
         return ['type'=> 'post' , 'data'=> $output];
      }
      if($data['type'] == 'page'){
         $output['title'] = $data['page']['title'];
         $output['bio'] = $data['page']['bio'];
         $output['type'] = $data['page']['type'];
         return ['type'=> 'page' , 'data'=> $output];
      }
      return ['type'=> false , 'data'=> 'unknwon url'];
   }
   $id = explode('profile.php?id=' , $local_url)[1];
   if(!is_numeric($id)){
      return ['type'=> false , 'data'=> 'unknwon id'];
   }
   if($id > 1000){
      if(($data = get_user_data($id)) != false){
         $output['title'] = $data['name'];
         $output['bio'] = $data['bio'];
         $output['type'] = $data['status'];
         return ['type'=> 'user' , 'data'=> $output];
      }
   }else if($id < -1000){
      if(($data = get_page($id) )!= false){
         $output['title'] = $data['title'];
         $output['bio'] = $data['bio'];
         $output['type'] = $data['type'];
         return ['type'=> 'page' , 'data'=> $output];
      }
   }
   return ['type'=> false , 'data'=> 'unknwon error'];
}

function lang($en , $ar){
   return (LANGUAGE == 'en') ? $en : $ar;
}

function get_status($num , $lang = 'en'){
   $array_en = array(
      'panned', // 0
      'graduation', // 1
      'user', // 2
      'student', // 3
      'teacher', // 4
      'real', // 5
      'vip', // 6
      'doctor', // 7
      'poster', // 8
      'admin', // 9
      'dev', // 10
      'king', // 11
      'page', // 12
      'super-page', // 13
   );
      $array = array(
      'محظور',
      'خريج',
      'مستخدم',
      'طالب',
      'معلم',
      'موثق',
      'مميز',
      'دكتور',
      'ناشر',
      'مشرف',
      'مطور',
      'مالك',
      'صفحة',
      'صفحة موثقة',
   );
   if($lang == 'en'){
      if(!isset($array_en[$num])) {
         return false;
      }
      return $array_en[$num];
   }else{
      if(!isset($array[$num])) {
         return false;
      }
      return $array[$num];
   }
}

function get_post_type($post){
   if(!isset($post['url'] , $post['code'])){
      return 'post';
   }
   if(isset($post['code'])){
      return 'code';
   }
   if(str_contains($post['url'] , 'profile.php?id=') or str_contains($post['url'] , 'p/index.php?page_id=' )){
      return 'local-url';
   }
   return 'url';
}
function search($phrese){
   if(empty($phrese)){
      return false;
   }
   if(($pages = get_all_pages()) == false){
      return false;
   }
   foreach($pages as $id => $page){
      if(!isset($page['tags'])){
         return false;
      }
      if(in_array($phrese , $page['tags'])){
         $search[$id] = $page;
      }
   }
   if(isset($search)){
      return $search;
   }else{
      return false;
   }
}


function getPagesData(){
   if(($all = get_all_pages()) != false){
      foreach($all as $id => $page){
         if($page['type'] == -1){
            continue;
         }
         $output[$id]['title'] = $page['title'];
         $output[$id]['members'] = $page['members'];
         $output[$id]['bio'] = $page['bio'];
         $output[$id]['id'] = $page['id'];
         if(!empty($page['status'])){
            $output[$id]['status'] = $page['status'];
         }
         $output[$id]['type'] = $page['type'];
      }
      return $output;
   }else{
      return null;
   }
}

function getPostsData($page){
   define('MAX_POST_SHORT_CONTENT' , 50);
   $path = get_page_path($page) . 'posts/' . get_file_name($page['posts'], 'post') . '.json';
   if(($posts = get_all_posts($path)) != false){
      foreach($posts as $id => $post){
         if(isset($post['type']) and $post['type'] == 'notify'){
            $output[$id] = $post;
            continue;
         }
         if(!empty($post['code'])){
            $output[$id]['type'] = 'code';
         }else if(!empty($post['url'])){
            $output[$id]['type'] = 'url';
         }else{
            $output[$id]['type'] = 'post';
         }
         $output[$id]['title'] = $post['title'];
         $output[$id]['content'] = substr($post['content'] , 0 , MAX_POST_SHORT_CONTENT) . '...';
         $output[$id]['poster'] = $post['poster'];
         $output[$id]['views'] = $post['views'];
         $output[$id]['id'] = $post['id'];
      }
      return $output;
   }else{
      return false;
   }
}

function getPostData($page, $post_id){
   $posts = get_all_posts(get_page_path($page) . 'posts/' . get_file_name($post_id , 'post') . '.json');
   if(isset($posts[$post_id])){
      $post = $posts[$post_id];
      if(!empty($post['code'])){
         $post['type'] = 'code';
      }else if(!empty($post['url'])){
         $post['type'] = 'url';
      }else{
         $post['type'] = 'post';
      }
      return $post;
   }else{
      return false;
   }
}

function getUsersData(){
   $users = get_all_users();
   $output = [];
   foreach($users as $id => $user){
      $output[$id]['name'] = $user['name'];
      $output[$id]['username'] = $user['username'];
      $output[$id]['id'] = $id;
      $output[$id]['date'] = $user['date'];
      if(isset($user['status'])){
         $output[$id]['status'] = $user['status'];
      }
   }
   return $output;
}

function getPostsFromUrl($page_id, $post_id = 1){
   if(($page = get_page($page_id)) == false){
      return false;
   }
   if(($data_posts = getPostsData($page)) == false){
      return false;
   }
   if(isset($data_posts[$post_id])){
      $output['post'] = $data_posts[$post_id];
   }else{
      $output['post'] = false;
   }
   $output['posts'] = $data_posts;
   $output['page'] = $page;
   return $output;
}

function save_data($file_name, $data){
   $temp_file = tempnam(sys_get_temp_dir(), 'json');
   file_put_contents($temp_file, $data);
   rename($temp_file, $file_name);
}
function get_file_name($id, $type){
   if($type == 'user'){
      return 'users_' . floor($id/1000);
   }else{
      return 'posts_' . floor($id/100);
   }
}*/