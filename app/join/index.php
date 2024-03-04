<?php
require('../dbconnect.php');

session_start();
$error = []; // $error を初期化

if (!empty($_POST)) {
	// エラー項目の確認
	if ($_POST['employeenumber'] == '') {
		$error['employeenumber'] = 'blank';
	}
	if ($_POST['name'] == '') {
		$error['name'] = 'blank';
	}
	if ($_POST['email'] == '') {
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] == '') {
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);
		if ($ext != 'jpg' && $ext != 'gif') {
			$error['image'] = 'type';
		}
	}

	// 重複アカウントのチェック
	if (empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE	employeenumber=?');
		$member->execute(array($_POST['employeenumber']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['employeenumber'] = 'duplicate';
		}
	}

	if (empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE	name=?');
		$member->execute(array($_POST['name']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['name'] = 'duplicate';
		}
	}

	if (empty($error)) {
		// 画像をアップロードする
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}
}
// 書き直し処理
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
	$_POST = $_SESSION['join'];
	$error['rewrite'] = true; // この行を追加
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<link rel="icon" href="./img/common/favicon.ico">
	<title>シフト社自動勤務シフト表 登録画面</title>
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
					<li><a href="../login/index.php">ログイン</a></li>
					<li><a href="index.php">会員登録</a></li>
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
			<p>会員登録画面</p>
		</div>
		<div>
			<form action="" method="post" enctype="multipart/form-data">
				<table border="0" cellspacing="0" cellpadding="0">

					<!-- 表示切り替えfirstBox -->
					<div class="contact startfade2" id="firstBox">
						<h2>会員登録フォーム</h2>
						<dl class="form-area">

							<dt><span class="required">社員番号</span></dt>
							<dd>
								<input class="input-text" type="text" name="employeenumber" size="35" maxlength="255"
									value="<?php echo htmlspecialchars($_POST['employeenumber'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
								<?php if (isset($error['employeenumber']) && $error['employeenumber'] == 'blank'): ?>
									<p class="error">* 社員番号を入力してください</p>
								<?php endif; ?>
								<?php if (isset($error['employeenumber']) && $error['employeenumber'] == 'duplicate'): ?>
									<p class="error">* 申し訳ございません。指定された社員番号はすでに登録されています</p>
								<?php endif; ?>
							</dd>

							<dt><span class="required">お名前</span></dt>
							<dd>
								<input class="input-text" type="text" name="name" size="35" maxlength="255"
									value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
								<?php if (isset($error['name']) && $error['name'] == 'blank'): ?>
									<p class="error">* お名前を入力してください</p>
								<?php endif; ?>
								<?php if (isset($error['name']) && $error['name'] == 'duplicate'): ?>
									<p class="error">* 申し訳ございません。指定されたお名前はすでに登録されています</p>
								<?php endif; ?>
							</dd>


							<dt>メールアドレス<span class="required">必須</span></dt>
							<dd>
								<input class="input-text" type="text" name="email" size="35" maxlength="255"
									value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
								<?php if (isset($error['email']) && $error['email'] == 'blank'): ?>
									<p class="error">* メールアドレスを入力してください</p>
								<?php endif; ?>
							</dd>

							<dt><span class="required">パスワード</span></dt>
							<dd>
								<input class="input-text" type="password" name="password" size="10" maxlength="20"
									value="<?php echo htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
								<?php if (isset($error['password']) && $error['password'] == 'blank'): ?>
									<p class="error">* パスワードを入力してください</p>
								<?php endif; ?>
								<?php if (isset($error['password']) && $error['password'] == 'length'): ?>
									<p class="error">* パスワードは4文字以上で入力してください</p>
								<?php endif; ?>
							</dd>

							<dt>写真など(gif or jpg)</dt>
							<dd><input type="file" name="image" size="35" />
								<?php if (isset($error['image']) && $error['image'] == 'type'): ?>
									<p class="error">* 写真などは「.gif」または「.jpg」の画像を指定してください
									</p>
								<?php endif; ?>
								<?php if (isset($error['image']) && !empty($error)): ?>
									<p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
								<?php endif; ?>
							</dd>
						</dl>
						<div class="submit-button-area">
							<button class="submit-button" type="submit">確認画面へ</button>
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
				<li><a href="../login/index.php">ログイン</a></li>
				<li><a href="index.php">会員登録</a></li>
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