<?php

require_once '../include/conf/const.php';
require_once '../include/model/function.php';

$user_name = '';
$user_id = '';
$item_id = '';
$item_data = array();
$disp_data = array();
$want_data = array();
$user_data = array();
$msg = array();

//セッション開始
session_start();

//セッション変数からuser_name取得
$user_name = get_user_name($user_name);

//リクエストメソッド取得
$request_method = get_request_method();

//DB接続
$link = get_db_connect();

if($request_method === 'POST'){
    
    //-------------------------------
    //カートに入れるを押した時の処理
    //-------------------------------
    
    if(isset($_POST['cart_btn']) === true){
        
        //POSTデータ取得
        $item_id = get_post_data('item_id');
        $date = date('Y-m-d H:i:s');
    
        //ec_user_tableのuser_nameと一致するuser_id取得
        $user_data = get_user_id($link, $user_name);
        $user_id = $user_data[0]['user_id'];

        //ec_cart_tableにuse_id item_id かぶるものあれば個数をUPDATE
        $item_data = get_cart_table($link, $user_id, $item_id);
    
        if($user_name !== 'admin'){
            if(count($item_data[0]) !== 0){
                if(update_ec_cart_table($link, $user_id, $item_id, $date) === true){
                    $msg[] = 'カートに登録しました';
                } else {
                    $msg[] = 'カートの登録に失敗しました';
                }
            } else {   
                //なければec_cart_tableへINSERT
                if(insert_ec_cart_table($link, $user_id, $item_id, $date) === true){
                    $msg[] = 'カートに登録しました';
                } else {
                    $msg[] = 'カートの登録に失敗しました';
                }
            }
        }
    }
    
        
    //-------------------------------------
    //ほしいものリストのボタンが押された時
    //-------------------------------------  
        
    if(isset($_POST['want_btn']) === true){
        
        //POSTデータ取得
        $item_id = get_post_data('item_id');
        $date = date('Y-m-d H:i:s');
    
        //ec_user_tableのuser_nameと一致するuser_id取得
        $user_data = get_user_id($link, $user_name);
        $user_id = $user_data[0]['user_id'];

        //ec_want_tableにuse_id item_id かぶるものあれば個数をUPDATE
        $want_data = get_want_table($link, $user_id, $item_id);
        
    
        if($user_name !== 'admin'){
            if(count($want_data[0]) !== 0){
                $msg[] = 'すでにほしいものリストに登録されています';
                
            } else {   
                //なければec_want_tableへINSERT
                if(insert_ec_want_table($link, $user_id, $item_id, $date) === true){
                    $msg[] = 'ほしいものリストに登録しました';
                } else {
                    $msg[] = 'ほしいものリストへの登録に失敗しました';
                }
            }
        }
    }    
}


//-------------
//DB情報を取得
//-------------

$item_data = get_ec_table_status($link);


//----------------
//ページネーション
//----------------

define('MAX', '9');

//トータルデータ
$item_num = count($item_data);
//トータルページ数
$max_page = ceil($item_num / MAX);

$now = '';

if(!isset($_GET['page_id'])){
    $now = 1;
} else if (isset($_GET['page_id']) === true) {
    $now = $_GET['page_id'];
}

$start_no = ($now - 1) * MAX;

//array_sliceで配列の何番め($start_no)から何番め(MAX)まで切り取る
$disp_data = array_reverse($item_data);
$disp_data = array_slice($disp_data, $start_no, MAX, true);


//DB切断
close_db_connect($link);

//HTMLエンティティ変換
$disp_data = entity_assoc_array($disp_data);

//HTML表示
include_once '../include/view/item_list.php';