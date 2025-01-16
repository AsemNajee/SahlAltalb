<?php
echo $_SERVER['PHP_SELF'];
echo "<br>";
echo $_SERVER['SERVER_NAME'];
echo "<br>";
echo $_SERVER['HTTP_HOST'];
echo "<br>";
// echo $_SERVER['HTTP_REFERER'];
echo "<br>";
echo $_SERVER['HTTP_USER_AGENT'];
echo "<br>";
echo $_SERVER['SCRIPT_NAME'];
 // union of $x and $y
// define('LOCAL_ROOT', '');
// include('newFiles/function.php');
// $array = ['hi'=>'hello world'];
// unset($array['hi']);
// print_r(save_data('temp.json', json_encode($array)));
/*
// <?php foreach($page_users as $id => $member ){ ?>
//    <a href="<?php echo 'profile.php?id=' . $id?> " >
//       <div onclick="setPoster(<?php echo $page_info['id'] . ',' . $id ?>)" id="<?php echo $id?>" class="item <?php echo (( isset($member['status']) and $member['status'] == 'poster') ? ' start' : '')?>">
//          <i class="<?php echo (isset($member['status']) ? get_icon($member['status']) :'fas fa-userx') ?>"></i>
//          <h4><?php echo $member['name'] ?></h4>
//       </div>
//    </a>
// <?php } ?>*/   