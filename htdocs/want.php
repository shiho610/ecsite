<?php

require_once '../include/conf/const.php';
require_once '../include/model/function.php';

$cart_data = array();
$item_data = array();

//セッション開始
session_start();

//セッション変数からuser_name取得
$user_name = get_user_name($user_name);

//リクエストメソッド取得
$request_method = get_request_method();

//DB接続
$link = get_db_connect();

//ec_user_tableのuser_nameと一致するuser_id取得
$user_data = get_user_id($link, $user_name);
$user_id = $user_data[0]['user_id'];


if($request_method === 'POST'){
    
    if(isset($_POST['amount_chg']) === true){

        //--------------------
        //個数の変更があった時
        //--------------------
    
        //値を取得
        $cart_id = get_post_data_int('cart_id');
        $amount_chg = get_post_data('amount_chg');
        $date = date('Y-m-d H:i:s');

        //エラーチェック
        $result = err_check($amount_chg, '数量');
            if($result !== true){
                $err[] = $result;
            }
        
        //アップデート
        if(count($err) === 0){
            if(update_ec_cart_amount($link, $amount_chg, $date, $cart_id) === true){
                $msg[] = '数量を変更しました';
            } else {
                $msg[] = '数量を変更できませんでした';
            }
        }
    }
    
    //--------------------
    //削除ボタン押されたら
    //--------------------
    
    if(isset($_POST['delete']) === true){
            
        //値を取得
        $want_id = get_post_data_int('want_id');

        //DELETE
        if(delete_want_list($link, $want_id) === true){
            $msg[] = '商品が削除されました';
        } else {
            $msg[] = '商品削除失敗';
        }
    }    
    
    //------------------------------
    //カートへ入れるボタン押されたら
    //------------------------------
    
    if(isset($_POST['cart_btn']) === true){
        
        $item_id = get_post_data_int('item_id');
        $cart_data = get_cart_table($link, $user_id, $item_id);
        $date = date('Y-m-d H:i:s');
        
        //同じ商品がカートに入っていなければcart_tableへINSERT
        if(count($cart_data[0]) !== 0){
            $msg[] = 'すでにカートに入っています。リストからは購入後に自動的に削除されます。';
            
        } else if(insert_ec_cart_table($link, $user_id, $item_id, $date) === true) {
            $msg[] = 'カートに登録しました';
            
        } else {
            $msg[] = 'カートの登録に失敗しました';
        }
        
    }

}


//合計を出す
$item_data = want_sum($link, $user_id);
$sum = $item_data[0]['sum'];


//DB情報を取得
$item_data = get_ec_want_list($link, $user_id);

//DB切断
close_db_connect($link);

//HTMLエンティティ変換
$item_data = entity_assoc_array($item_data);


//HTML表示
include_once '../include/view/want.php';