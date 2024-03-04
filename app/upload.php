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
<!-- main要素ここから-->
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
ini_set('default_charset', 'UTF-8');
if (is_uploaded_file($_FILES["csvfile"]["tmp_name"])) {
  $file_tmp_name = $_FILES["csvfile"]["tmp_name"];
  $file_name = $_FILES["csvfile"]["name"];

  //拡張子を判定
  if (pathinfo($file_name, PATHINFO_EXTENSION) != 'csv') {
    $err_msg = 'CSVファイルのみ対応しています。';
           echo '<div class="contact">';
           echo "<h2>シフト作成者 様へ</h2>";
           echo '<dl class="form-area">';
           echo "<div class='form-text'>本シフト作成ツールをご利用いただき、ありがとうございます。
           恐れ入りますが、入力したファイル、もしくは入力していただいた年月に誤りがあるようです。下記ボタンから再度やり直してください。</div>";
           echo '</dl>';
           echo '</div>';
           echo
               "
                <div class='link-button-area'>
                <a class='link-button' href='admin-home.php'>戻る</a>
                </div>
             ";
	   //ファイルの削除
           unlink('./data/uploaded/'.$file_name);
  } else {
    //ファイルをdataディレクトリに移動
    if (move_uploaded_file($file_tmp_name, "./data/uploaded/" . $file_name)) {
      //後で削除できるように権限を644に
      chmod("./data/uploaded/" . $file_name, 0644);
      $msg = $file_name . "をアップロードしました。";
      $file = './data/uploaded/'.$file_name;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームからのデータを取得
    $selectedOption = $_POST["部署選択"];

    // 部署によって処理を分岐
    switch ($selectedOption) {
        case "デジタル報道部配信班":
            // デジタル報道部配信班の処理
            #echo "デジタル報道部配信班が選択されました。";
            $cmd = '/venv/bin/python3 degitalstreaming_shift_make.py'.' '.$file.' '.$_POST['year'].' '.$_POST['month'].' '.'2>&1';
            exec($cmd, $opt, $return_ver);
	        //print_r($opt);
            //echo '実行結果：'.$return_ver;
	    break;
        case "システム部ローテ業務":
            // システム部ローテ業務の処理
	    #echo "システム部ローテ業務が選択されました。";
            $cmd = '/venv/bin/python3 systemrotation_shift_make.py'.' '.$file.' '.$_POST['year'].' '.$_POST['month'].' '.'2>&1';
	        exec($cmd, $opt, $return_ver);
	        //print_r($opt);
          //  echo '実行結果：'.$return_ver;
            break;
        case "新聞編集部整理班":
            // 新聞編集部整理班の処理
            #echo "新聞編集部整理班が選択されました。";
            $cmd = '/venv/bin/python3 renewspaper_editing.py'.' '.$file.' '.$_POST['year'].' '.$_POST['month'].' '.'2>&1';
            exec($cmd, $opt, $return_ver);
            //print_r($opt);
            //echo '実行結果：'.$return_ver;		
	    break;
        default:
            // 未知の選択に対する処理
            #echo "不明な部署が選択されました。";
    }
}


      //$cmd = 'python3 systemrotation_shift_make.py'.' '.$file.' '.$_POST['year'].' '.$_POST['month'].' '.'2>&1';
      //exec($cmd, $opt, $return_ver);
      //print_r($opt);
      //echo '実行結果：'.$return_ver;
      
      if ($return_ver == 0) {
      $contents = file_get_contents("searchpath.txt");
      //echo $contents;
      echo '<div class="contact">';
            echo "<h2>シフト作成者 様へ</h2>";
            echo '<dl class="form-area">';
            echo "<div class='form-text'>本シフト作成ツールをご利用いただき、ありがとうございます。
            作成に成功しましたので、お手数ですが下記ボタンからダウンロードをお願いします。</div>";
            echo '</dl>';
            echo '</div>';
      echo 
      "
      <ul class='item-button'>
      <li>
        <div class='link-button-area'>
          <a class='link-button' href='admin-home.php'>戻る</a>
        </div>
      </li>
      <li>
        <div class='link-button-area'>
        <div class='link-button'>
        <a href='data/dl/$contents' download='data/dl/$contents'>
          ダウンロード
        </a>
        </div>
        </div>
      </li>

      </ul>
      ";

      //ファイルの削除
      unlink('./data/uploaded/'.$file_name);
      }
      else {
           $err_msg = "プログラムの実行不可能";
           echo '<div class="contact">';
           echo "<h2>シフト作成者 様へ</h2>";
           echo '<dl class="form-area">';
           echo "<div class='form-text'>本シフト作成ツールをご利用いただき、ありがとうございます。
           恐れ入りますが、入力したファイル、もしくは入力していただいた年月に誤りがあるようです。下記ボタンから再度やり直してください。</div>";
           echo '</dl>';
           echo '</div>';
           echo
               "
                <div class='link-button-area'>
                <a class='link-button' href='admin-home.php'>戻る</a>
                </div>
             ";
	   //ファイルの削除
           unlink('./data/uploaded/'.$file_name);
      }
    } else {
      $err_msg = "ファイルをアップロードできません。";
    }
  }
} else {
  $err_msg = "ファイルが選択されていません。";
  echo '<div class="contact">';
  echo "<h2>シフト作成者 様へ</h2>";
  echo '<dl class="form-area">';
  echo "<div class='form-text'>本シフト作成ツールをご利用いただき、ありがとうございます。
  恐れ入りますが、入力したファイルに誤りがあるようです。下記ボタンから再度やり直してください。</div>";
  echo '</dl>';
  echo '</div>';
  echo 
      "
        <div class='link-button-area'>
          <a class='link-button' href='admin-home.php'>戻る</a>
        </div>
      ";
}
//echo $msg;
//echo $err_msg;

?>
</main>
<!-- main要素ここまで-->
<!-- footerここから -->
<div class="footer_top_img"><img src="./img/common/footer-top.jpg" width="100%" alt=""></div>
<footer class="footer">
  <nav class="site-menu">
      <ul>
        <li><a href="../index.php">ホーム</a></li>
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
