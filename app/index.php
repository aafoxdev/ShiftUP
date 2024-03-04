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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="./img/common/favicon.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV File Selection and Display</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <link href="./showTableu.css" rel="stylesheet">
  <link href="../css/common.css" rel="stylesheet">
  <script src="./showTable.js" type="text/javascript"></script>

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
  
<header class="header">
  <div class="header-inner">
      <a class="header-logo" href="../index.php">
          <img src="./img/common/logo.png" alt="ehimeshinbun">
      </a>
      <button class="toggle-menu-button"></button>
      <div class="header-site-menu">
          <nav class="site-menu">
              <ul>
              <li><a href="./index.php">ホーム</a></li>
                    <li><a href="./message/">メッセージ</a></li>
                    
				      <li><a href="./logout.php">ログアウト</a></li>
              </ul>
          </nav>
      </div>
  </div>
</header>

<body>
<main class="main">
<div class="titleslide">
  <h1>ホーム</h1>
  <ul class="slider">
    <li class="slider-item slider-item01"></li>
    <li class="slider-item slider-item02"></li>
    <li class="slider-item slider-item03"></li>
  </ul>
<!--/wrapper--></div>
<?php
$directory = "./data"; // ディレクトリのパスを指定してください
$files = array_diff(scandir($directory), array('.', '.'));

// CSVファイルのみを抽出
$csvFiles = array_filter($files, function ($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'csv';
});
?>

<div class="grid">
  <div class="sercharea">
<label for="search">Search CSV Files: </label>
<input type="text" id="search" oninput="filterFiles()">
<br>
</div>

<ul>
<li>
<div class="exporter">
  <a onclick="exportToCSV()" href=""><font color="blue">CSVに変換してダウンロード</font></a>
</div>
</li>
</ul>


<div id="table-container"></div>

<div class="filearea">
    <form>
        <label for="csvFile">Select CSV File: </label>
        <select id="csvFile" name="csvFile" onchange="loadCSV(this.value)">
            <option value="" disabled selected>Select a CSV File</option>
            <?php foreach ($csvFiles as $csvFile): ?>
                <option value="<?php echo $directory . '/' . $csvFile; ?>"><?php echo $csvFile; ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>




</main>
</body>
<div class="footer_top_img"><img src="./img/common/footer-top.jpg" width="100%" alt=""></div>
<footer class="footer">
  <nav class="site-menu">
      <ul>
      <li><a href="./index.php">ホーム</a></li>
      <li><a href="./message/">メッセージ</a></li>    
			<li><a href="./logout.php">ログアウト</a></li>
      </ul>
  </nav>
  <a class="footer-logo" href="./index.php">
    <img src="./img/common/logo.png" alt="ehimeshinbun">
  </a>
  <p class="copyright"><small>&copy;シフト社</small></p>
</footer>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="./js/bg-move.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="./js/img-move.js"></script>
</html>
