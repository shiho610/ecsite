<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once '../include/conf/const.php';
require_once '../include/model/function.php';

$user_name = '';
$password = '';
$err = array();
$msg = array();
$user_data = array();


//リクエストメソッド取得
$request_method = get_request_method();

//DB接続
$link = get_db_connect();

if($request_method === 'POST'){
    
    //セッション開始
    session_start();
    
    //POSTデータ取得
    $user_name = get_post_data('user_name');
    $password = get_post_data('password');
    
    //エラーチェック
    $result = err_check_login($user_name, 'ユーザーID');
    if($result !== true){
        $err[] = $result;
    }
    $result = err_check_login($password, 'パスワード');
    if($result !== true){
        $err[] = $result;
    }
    
    //既存のユーザーIDかどうか調べる
    //POST取得したuser_nameと同じものをSELECTして配列に入れる、
    //中に入っているものがあったらエラーメッセージ
    
    $user_data = get_ec_user_table_login($link, $user_name);

    if(count($user_data[0]) !== 0 ){
        $err[] = '入力されたユーザーIDはすでに登録されています';
    }


    //エラーなかったらcookie保存、ec_user_tableへインサート
    if(count($err) === 0){
        $date = date('Y-m-d H:i:s');

        //ユーザーIDをcookieへ保存
        setcookie('user_name', $user_name, time() + 60 * 60 * 24 * 365);
        
        if(insert_ec_user_table($link, $user_name, $password, $date) === true){
            $msg[] = 'ユーザー登録が完了しました';
            
        } else {
            $msg[] = 'ユーザー登録できませんでした';
        }
    }
}


//DB切断
close_db_connect($link);

//HTML表示
include_once '../include/view/create_id.php';