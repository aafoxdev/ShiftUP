<?php
require('../dbconnect.php');
ob_start(); // 出力バッファリングを開始
session_start();
$error = []; // $error を初期化

if (isset($_COOKIE['employeenumber']) && $_COOKIE['employeenumber'] != '') {
  $_POST['employeenumber'] = $_COOKIE['employeenumber'];
  $_POST['password'] = $_COOKIE['password'] ?? ''; // password キーが存在しない場合は空の文字列を使用
  $_POST['save'] = $_COOKIE['save'] ?? 'off'; // save キーが存在しない場合は 'off' をデフォルト値とする
}

if (!empty($_POST)) {
  // ログインの処理
  if ($_POST['employeenumber'] != '' && $_POST['password'] != '') {
    $login = $db->prepare('SELECT * FROM members WHERE employeenumber=? AND password=?');
    $login->execute([
      $_POST['employeenumber'],
      sha1($_POST['password'])
    ]);
    $member = $login->fetch();
    if ($member && $member['name'] == "admin") {
      // ログイン成功
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      // ログイン情報を記録する
      if (isset($_POST['save']) && $_POST['save'] == 'on') {
        setcookie('employeenumber', $_POST['employeenumber'], time() + 60 * 60 * 24 * 14, '/');
        setcookie('password', $_POST['password'], time() + 60 * 60 * 24 * 14, '/');
      }
      $makedir = "../admin-home.php";
      header("Location: $makedir");
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } else {
    $error['login'] = 'blank';
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <link rel="icon" href="../img/common/favicon.ico">
  <title>シフト自動勤務シフト表 管理者画面</title>
  <meta name="description" content="はじめに">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <!--共通CSS-->
  <link href="../css/home.css" rel="stylesheet">
  <link href="../css/common.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/bg-move.css">

  <!--共通JS-->
  <script src="../js/toggle-menu.js"></script>
</head>

<div id="splash">
  <div id="splash_text"></div>
  <div class="loader_cover loader_cover-up"></div>
  <div class="loader_cover loader_cover-down"></div>
</div>
<!-- バックグラウンド回転用 -->
<div id='particles-js'></div>
<!-- headerここから -->
<header class="header">
  <div class="header-inner">
    <a class="header-logo" href="../">
      <img src="../img/common/logo.png" alt="ehimeshinbun">
    </a>
    <button class="toggle-menu-button"></button>
    <div class="header-site-menu">
      <nav class="site-menu">
        <ul>
          <li><a href="./index.php">ログイン</a></li>
          <li><a href="../join/index.php">会員登録</a></li>
        </ul>
      </nav>
    </div>
  </div>
</header>
<!-- headerここまで -->

<body>
  <main class="main">
    <div class="title">
      <h1>シフト社自動勤務シフト表</h1>
      <p>管理者用ログイン画面</p>
    </div>
    <div>
      <form action="" method="post">
        <table border="0" cellspacing="0" cellpadding="0">

          <!-- 表示切り替えfirstBox -->
          <div class="contact startfade2" id="firstBox">
            <h2>管理者用ログインフォーム</h2>
            <dl class="form-area">

              <dt><span class="required">社員番号</span></dt>
              <dd>
              <input type="text" class="input-text" name="employeenumber" size="35" maxlength="255"
                  value="<?php echo htmlspecialchars($_POST['employeenumber'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                <?php if (isset($error['employeenumber']) && $error['employeenumber'] == 'blank'): ?>
                  <p class="error">* 社員番号とパスワードをご記入ください</p>
                <?php endif; ?>
                <?php if (isset($error['employeenumber']) && $error['employeenumber'] == 'failed'): ?>
                  <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
                <?php endif; ?>
              </dd>

              <dt><span class="required">パスワード</span></dt>
              <dd>
                <input type="password" class="input-text" name="password" size="35" maxlength="255"
                  value="<?php echo htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES); ?>" />
              </dd>
            </dl>
            <p class="confirm-text">
              <input id="save" type="checkbox" name="save" value="on"><label for="save">次回からは自動的にログインする</label>
            </p>
            <p class="confirm-text"><a href="./index.php">
                <font color="blue">一般ユーザーはこちらへ</font>
              </a></p>
            <div class="submit-button-area">
              <button class="submit-button" type="submit">ログインする</button>
            </div>

            </dl>
          </div>


        </table>
      </form>
    </div>

  </main>
  <!-- footerここから -->

  <div class="footer_top_img"><img src="../img/common/footer-top.jpg" width="100%" alt=""></div>
  <footer class="footer">
    <nav class="site-menu">
      <ul>
        <li><a href="./index.php">ログイン</a></li>
        <li><a href="../join/index.php">会員登録</a></li>
      </ul>
    </nav>
    <a class="footer-logo" href="index.php">
      <img src="../img/common/logo.png" alt="ehimeshinbun">
    </a>
    <p class="copyright"><small>&copy;シフト社</small></p>
  </footer>
  <!-- footerここまで -->
  <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
  <script src="../js/bg-move.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <script src="../js/img-move.js"></script>
</body>

</html>