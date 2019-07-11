<?php

require_once '../include/conf/const.php';
require_once '../include/model/function.php';

$user_name = '';
$item_id = '';
$amount_chg = '';
$stock = '';
$name = '';
$sum = '';
$err = array();
$user_data = array();
$sum_data = array();
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

//合計を出す
$sum_data = cart_sum($link, $user_id);
$sum = $sum_data[0]['sum'];



//cart.phpの購入ボタンが押されたら・・・
if($request_method === 'POST'){
    
    //DB情報を取得
    $item_data = get_ec_cart_list($link, $user_id);
    
    //カートに何も入ってなかったらカートページへ
    if(count($item_data) === 0){
        $err[] = 'カートに商品が入っていません';
        
    } else {

        //配列の中から数量を取得
        foreach($item_data as $value){
        
            $amount = $value['amount'];
            $stock = $value['stock'];
            $name = $value['name'];
            $status = $value['status'];
            $date = date('Y-m-d H:i:s');

            //HTMLエンティティ変換
            $name = entity_str($name);
        
            if($stock < $amount){
                $err[] = $name. 'の在庫が足りないため購入できません(現在の在庫：' . $stock . '個)';
            }
        
            if((int)$status === 0){
                $err[] = $name. 'は販売中止です';
            }
        }
    
        if(count($err) === 0) {
            mysqli_autocommit($link, false);
        
            foreach($item_data as $value){
            
                $item_id = $value['item_id'];
                $amount = $value['amount'];
                $date = date('Y-m-d H:i:s');
            
            
                //ec_stock_tableのstock変更
                if(chg_ec_stock($link, $item_id, $amount, $date) === false){
                    $err[] = '購入処理できませんでした';
                }
            }
        
            //ec_cart_tableの履歴も削除
            if(delete_cart_want_table($link, $user_id) === false){
                $err[] = '購入処理できませんでした';
            }
        
            if(count($err) === 0){
                mysqli_commit($link);
            } else {
                mysqli_rollback($link);
            }
        }
    }
}

//DB切断
close_db_connect($link);

//HTMLエンティティ変換
$item_data = entity_assoc_array($item_data);

//HTML表示
include_once '../include/view/confirm.php';