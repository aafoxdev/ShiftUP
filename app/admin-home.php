<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	// ログインしている
	$_SESSION['time'] = time();

	$members = $db->prepare('SELECT * FROM members WHERE id=?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
} else {
	// ログインしていない
	header('Location: ./login/'); exit();
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
<html>
<head>
  <meta charset="UTF-8">
  <title>シフト社の勤務表作成ツール</title>
  <link rel="icon" href="./img/common/favicon.ico">
  
    <!-- CSSはここに追加します -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <!-- 共通CSS -->
    <link href="./css/common.css" rel="stylesheet">
    <link href="./css/bg-move.css" rel="stylesheet">
    <!-- 追加CSS -->
    <link href="./css/home.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./css/bg-move.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="./css/img-move.css">
    <!--共通JS-->
    <script src="./js/toggle-menu.js"></script>
   
</head>

<!-- バックグラウンド回転用 -->
<div id='particles-js'></div>
<!--/wrapperここから--><div id='wrapper'>

<!-- headerここから -->
<header class="header">
  <div class="header-inner">
      <a class="header-logo" href="../index.php">
          <img src="./img/common/logo.png" alt="ehimeshinbun">
      </a>
      <button class="toggle-menu-button"></button>
      <div class="header-site-menu">
          <nav class="site-menu">
              <ul>
              <li><a href="../index.php">ホーム</a></li>
              <li><a href="./message/">メッセージ</a></li>
				      <li><a href="./logout.php">ログアウト</a></li>
              </ul>
          </nav>
      </div>
  </div>
</header>
<!-- headerここまで -->
<!-- body要素ここから-->
<body bgcolor=white background="">
<main class="main">

<div class="titleslide">
  <h1>ホーム</h1>
  <ul class="slider">
    <li class="slider-item slider-item01"></li>
    <li class="slider-item slider-item02"></li>
    <li class="slider-item slider-item03"></li>
  </ul>
<!--/wrapper--></div>

<div class="contact">
<h2>シフト作成フォーム</h2>
<form action="upload.php" method="post" enctype="multipart/form-data">
<div class="form-area">
  <dt><span class="required">年を入力：</span></dt>
  <dd><input class="input-text" type="text" name="year" size="4" placeholder="YYYY" /></dd>

  <dt><span class="required">月を入力：</span></dt>
  <dd><input class="input-text" type="text" name="month" size="2" placeholder="MM" /></dd>

  <dt><span class="required">部署選択：</span></dt>
  <dd>
    <select class="select-box" name="部署選択">
        <option value="デジタル報道部配信班">部署A</option>
        <option value="システム部ローテ業務">部署B</option>
        <option value="新聞編集部整理班">部署C</option>
    </select>
  </dd>
  
  <dt><span class="required">CSVファイル:</span></dt>
  <dd><input  type="file" name="csvfile" size="30" /></dd>
</div>
<div class="submit-button-area">
  <button class="submit-button" type="submit">作成開始</button>
</div>

</form>
</div>

</main>
</body>
<!-- body要素ここまで-->
<!-- footerここから -->
<div class="footer_top_img"><img src="./img/common/footer-top.jpg" width="100%" alt=""></div>
<footer class="footer">
  <nav class="site-menu">
      <ul>
        <li><a href="./admin-home.php">ホーム</a></li>
        <li><a href="./message/">メッセージ</a></li>
				<li><a href="./logout.php">ログアウト</a></li>
      </ul>
  </nav>
  <a class="footer-logo" href="../index.php">
    <img src="./img/common/logo.png" alt="ehimeshinbun">
  </a>
  <p class="copyright"><small>&copy;シフト社</small></p>
</footer>
<!-- footerここまで -->
<!--/wrapperここまで--></div>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="./js/bg-move.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="./js/img-move.js"></script>
</html>

