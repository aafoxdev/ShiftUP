<?php
session_start();
require('../dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	// ログインしている
	$_SESSION['time'] = time();

	$members = $db->prepare('SELECT * FROM members WHERE id=?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
} else {
	// ログインしていない
	header('Location: ../login/'); exit();
}

// 投稿を記録する
if (!empty($_POST)) {


	if (isset($_POST['_upload'])) {
    $filename = date('YmdHis').$_FILES['send_file']['name'];
    if (move_uploaded_file($_FILES['send_file']['tmp_name'], './file/' . $filename)) {
      echo $_FILES['send_file']['name'].'をアップロードしました';
    } else {
      //エラー処理
    }

		$message = $db->prepare('INSERT INTO messages SET message=?, sender_id=?, address_id=?, send_file=?, created=NOW()');
		$message->execute(array(
			$_POST['message'],
      $member['id'],
      $_POST['address_id'],
			!empty($_FILES['send_file']['name']) ? $filename : null,
		));

		header("Location: index.php?id={$_POST['address_id']}"); exit();
	}
}


// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// 本文内のURLにリンクを設定します
function makeLink($value) {
	return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>' , $value);
}
?>

<?php
// メンバー一覧を取得する
$mlists = $db->query("SELECT * FROM members WHERE id != '".$member['id']."'");
?>

<head>
  <meta charset="UTF-8">
  <link rel="icon" href="./img/common/favicon.ico">
  <title>シフト自動勤務シフト表 ログイン画面</title>
  <meta name="description" content="はじめに">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <!--共通CSS-->
  <link href="./css/load.css" rel="stylesheet">
  <link href="../css/common.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/bg-move.css">

  <!--共通JS-->
  <script src="../js/toggle-menu.js"></script>
  
  <!--選択CSS-->
  <link href="../css/message.css" rel="stylesheet">
  <link href="../css/chat.css" rel="stylesheet">

</head>
<!-- バックグラウンド回転用 -->
<div id='particles-js'></div>
 <!-- headerここから -->
 <header class="header">
  <div class="header-inner">
      <a class="header-logo" href="../index.php">
          <img src="../img/common/logo.png" alt="ehimeshinbun">
      </a>
      <button class="toggle-menu-button"></button>
      <div class="header-site-menu">
          <nav class="site-menu">
              <ul>
              <li><a href="../index.php">ホーム</a></li>
              <li><a href="../message/">メッセージ</a></li>
				      <li><a href="../logout.php">ログアウト</a></li>
              </ul>
          </nav>
      </div>
  </div>
</header>
<!-- headerここまで -->

 <!-- mainここから -->
<body>
<main class="main">
<div class="title">
    <h1>シフト自動勤務シフト表</h1>
    <p>メッセージ欄</p>
</div>

<div class="content">

<dl class="content-area">

<?php
$memberid=$member['id']; 
if(isset($_GET["id"])){ 
$getid=$_GET["id"];
$getmessages = 
$db->query("SELECT m.id, m.name, m.picture, p.* FROM members m INNER JOIN messages p ON m.id = p.sender_id 
WHERE (p.sender_id = '". $memberid."' AND p.address_id = '". $getid."') OR (p.sender_id = '". $getid."' AND p.address_id = '". $memberid."')");
?>

<div class="kaiwa line">    

    <?php 
    $checkstatus=0;
    foreach ($getmessages as $getmessage):
        //右側の吹き出し 自分が送信したもの
          if($getmessage["sender_id"]==$member['id']){
            $checkstatus=0;
            echo 
                "<div class='fukidasi right'>",
                $getmessage['message'],
                "</div>"
            ;
          if(!empty($getmessage["send_file"])){
            $re_getmessage = substr($getmessage["send_file"], 14);
            echo 
                "<div class='fukidasi right'>
                <a href='./file/",
                $getmessage["send_file"],
                "'>
                <font color='read'>",
                $re_getmessage,
                "</font>
                </a>
                </div>"
            ;
          }
          }
          else {
            if($checkstatus==0){
                echo 
                "<div class='name'>",
                $getmessage['name'],
                "</div>",
                "<div class='fukidasi left'>
                <img class='icon' src='../member_picture/",
                $member['picture'],
                "' alt=''>",
                $getmessage['message'],
                "</div>"
            ;
            if(!empty($getmessage["send_file"])){
              $re_getmessage = substr($getmessage["send_file"], 14);
              echo 
                  "<div class='fukidasi left'>
                  <a href='./file/",
                  $getmessage["send_file"],
                  "'>
                  <font color='read'>",
                  $re_getmessage,
                  "</font></a>
                  </div>"
              ;
            }
            $checkstatus=1;
            }
            else{
            echo 
                "<div class='fukidasi left'>",
                $getmessage['message'],
                "</div>"
            ;
            if(!empty($getmessage["send_file"])){
              $re_getmessage = substr($getmessage["send_file"], 14);
              echo 
                  "<div class='fukidasi left'>
                  <a href='./file/",
                  $getmessage["send_file"],
                  "'>
                  <font color='read'>",
                  $re_getmessage,
                  "</font></a>
                  </div>"
              ;
            }
            }
          }
        
    endforeach;
    ?>

</div>

<div class="form-group" >
    <form method="post" enctype="multipart/form-data">
    <!--
    <input type="file" name="send_file" size="35" />
     !-->
        <table>
             <tr>
                 <td>
                  <dd>
                  <textarea type="text" class="message" name="message" required maxlength="100" ></textarea>
                  <input type="hidden" name="address_id" value="<?php echo htmlspecialchars($_GET["id"], ENT_QUOTES); ?>" />
                  </dd>
                  </td>
                 <td>
           
                 <dd>
                <div class="originalFileBtn ">
                 <input type="file" name="send_file" size="100000">
                </div></dd>
                 </td>
               
                 <td >
                  <div class="originalSubmitBtn ">
                 
                  <input type="submit"  name="_upload">
                  </div>
                 </td>
             </tr>
         </table>
    </form>
</div>
<?php } ?>



</dl>
<aside class="content-menu">
          <div class="content-menu-inner">
              <h2>MEMBER LIST</h2>
              <ul>
                  <?php foreach ($mlists as $mlist):?>
                    <li><a href="index.php?id=<?php echo h($mlist['id']); ?>">
                    <?php echo htmlspecialchars($mlist['name'], ENT_QUOTES);?></a></li>
                  <?php endforeach;?>
                
                 
              </ul>
          </div>
      </aside>
</div>
</main>

</body>

<!-- mainここまで -->
<div class="footer_top_img"><img src="../img/common/footer-top.jpg" width="100%" alt=""></div>
<!-- footerここから -->
<footer class="footer">
  <nav class="site-menu">
      <ul>
        <li><a href="../index.php">ホーム</a></li>
        <li><a href="../message/">メッセージ</a></li>
				<li><a href="../logout.php">ログアウト</a></li>
      </ul>
  </nav>
  <a class="footer-logo" href="../index.php">
    <img src="../img/common/logo.png" alt="ehimeshinbun">
  </a>
  <p class="copyright"><small>&copy;シフト社</small></p>
</footer>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="../js/bg-move.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="../js/img-move.js"></script>
<!-- footerここまで -->