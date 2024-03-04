<?php
try {
    $db = new PDO('mysql:dbname=mydatabase;host=mysql;charset=utf8','user', 'userps');
} catch (PDOException $e) {
    echo 'DB接続エラー： ' . $e->getMessage();
}
?>