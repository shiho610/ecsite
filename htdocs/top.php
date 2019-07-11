<?php

require_once '../include/conf/const.php';
require_once '../include/model/function.php';

$user_name = '';
$password = '';
$err = '';
$row = array();
$user_data = array();

//セッション開始
session_start();

//-------------------------------------
//セッション変数からログイン済みか確認

if(isset($_SESSION['user_name']) === true && $_SESSION['user_name'] === 'admin'){
    
    //adminでログイン済みなら商品管理ページへ
    header('Location: ./insert.php');
    exit;
    
} else if(isset($_SESSION['user_name']) === true){
    
    //その他のセッション変数なら商品一覧ページへ
    header ('Location: ./item_list.php');
    exit;
}

//---------------------------------------------
//セッション変数からログインエラーフラグを確認

if(isset($_SESSION['login_err_flag']) === true){
    
    //ログインエラーフラグ取得
    $login_err_flag = $_SESSION['login_err_flag'];
    //エラー表示は一度だけ、フラグをfalseへ
    $_SESSION['login_err_flag'] = false;
    
} else {
    //セッション変数なかったらエラーフラグfalse
    $login_err_flag = false;
}

//----------------------------------------
//Cookieに保存されていればuser_nameを取得

if(isset($_COOKIE['user_name']) === true){
    
    $user_name = $_COOKIE['user_name'];
    
    //DB接続
    $link = get_db_connect();
    
    //Cookie保存のuser_nameがDBにあればパスワード取得
    $user_data = get_ec_user_table_top_login($link, $user_name);
    $password = $user_data[0]['password'];

    //DB切断
    close_db_connect($link);
    
} else {
    $user_name = '';
    $password = '';
}

//HTML表示
include_once '../include/view/top.php';
