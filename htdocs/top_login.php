<?php

//---------------------------
//トップページのログイン処理
//---------------------------

require_once '../include/conf/const.php';
require_once '../include/model/function.php';

$err = '';

//リクエストメソッド取得
$request_method = get_request_method();

//POSTでなければログインページへ
if($request_method !== 'POST'){
    header ('Location: ./top.php');
    exit;
}


if($request_method === 'POST'){
    
    //セッション開始
    session_start();
    
    //POSTデータ取得
    $user_name = get_post_data('user_name');
    $password = get_post_data('password');
    
    // データベース接続
    $link = get_db_connect();
    
    //adminが入っていたら商品管理ページへ
    $result = admin_check($user_name, $password);

    if($result === true){
        
        $_SESSION['user_name'] = $user_name;
        header ('Location: ./insert.php');
        exit;
        
    //ユーザーID、パスワードが一致していたら商品一覧ページへ
    } else if($user_data = get_ec_user_table_login($link, $user_name, $password)) {

        if(count($user_data[0]) !== 0){
            
            //user_nameをクッキーへ保存
            setcookie('user_name', $user_name, time() + 60 * 60 * 24 * 365);
            
            //登録したuser_nameをセッション変数に保存
            $_SESSION['user_name'] = $user_data[0]['user_name'];
            
            //ec_uer_tableのuser_name一致するところをupdate
            $date = date('Y-m-d H:i:s');
            update_ec_user_table($link, $user_name, $date);
            
            //商品一覧ページへ移動
            header ('Location: ./item_list.php');
            exit;
            
        } else if(count($user_data[0]) === 0) {
            $_SESSION['login_err_flag'] = true;
            $err = 'ユーザーIDとパスワードが一致しません';
        }
    }
    
    // データベース切断
    close_db_connect($link);

}


include_once '../include/view/top.php'; 