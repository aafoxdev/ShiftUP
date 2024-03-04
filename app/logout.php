<?php
session_start();

// "name"キーの存在を確認してから出力
if (isset($_REQUEST['name'])) {
    echo htmlspecialchars($_REQUEST['name']);
}

// セッション情報を削除
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Cookie情報も削除
setcookie('employeenumber', '', time() - 60 * 60 * 24 * 14, '/login');
setcookie('password', '', time() - 60 * 60 * 24 * 14, '/login');

// 出力バッファリングを使用して、ヘッダ送信前の出力を防ぐ
ob_start();
header('Location: ./login/');
ob_end_flush();
exit();
?>
