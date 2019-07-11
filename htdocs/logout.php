<?php

//--------------
//ログアウト処理
//--------------

require_once '../include/conf/const.php';
require_once '../include/model/function.php';

//セッション開始
session_start();

//セッション名取得
$session_name = session_name();

//セッション変数削除
$_SESSION = array();

//ユーザーのCookieに保存されているセッションID削除
if(isset($_COOKIE[$session_name])){
    //sessionに関連する設定しゅとkに関連する設定取得
    $params = session_get_cookie_params();
    
    //session利用のcookie有効期限を過去に
    setcookie($session_name, '', time() - 3600, 
    $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

//セッションID無効化
session_destroy();

//ログアウト処理完了したらログインページへ
header ('Location: ./top.php');
exit;