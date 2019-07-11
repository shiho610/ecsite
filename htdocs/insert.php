<?php

require_once '../include/conf/const.php';
require_once '../include/model/function.php';

$user_name = '';
$name = '';
$price = '';
$stock = '';
$stock_chg = '';
$status = '';
$item_id = '';
$mode = '';
$err = array();
$msg = array();
$disp_data = array();
$drink_data = array();

//セッション開始
session_start();

//セッションIDからuser_name取得
$user_name = get_user_name($user_name);

//セッション変数からuser_name取得
if(isset($_SESSION['user_name']) === true && $_SESSION['user_name'] === 'admin'){
    $user_name = $_SESSION['user_name'];
} else {
    //非ログインの時、admin以外のユーザーだったらログインページへ、ログイン済みならitem_listページへ
    header ('Location: ./top.php');
}

//リクエストメソッド取得
$request_method = get_request_method();

//DB接続
$link = get_db_connect();

if($request_method === 'POST'){
    
    if(isset($_POST['mode']) === true){
        $mode = $_POST['mode'];
    }
    
    if($mode === 'insert'){

        $name = get_post_data('name');
        $price = get_post_data('price');
        $stock = get_post_data('stock');
        $status = get_post_data_int('status');
        $date = date('Y-m-d H:i:s');
        

    
    //--------------
    //エラーチェック
    //--------------
    
        $result = err_check($name, '商品名');
            if($result !== true){
                $err[] = $result;
            }
        
        $result = err_check($price, '値段');
            if($result !== true){
                $err[] = $result;
            }
            
        $result = err_check($stock, '個数');
            if($result !== true){
                $err[] = $result;
            }
            
        $result = err_check_status($status);
            if($result !== true){
                $err[] = $result;
            }
    
        //画像ファイルチェック
        //一時的なファイル名取得
        $tempfile = $_FILES['img']['tmp_name'];
        //ランダムに文字を取得→シャッフルして同じ画像が上がっても重複しないようにする
        $sha1 = sha1(uniqid(mt_rand(),true));
        //拡張子を取得
        $ext = strrchr($_FILES['img']['name'], '.');
        $filename = $sha1.$ext;
    
        //画像エラーチェック
        $result = err_check_img($tempfile, $filename, $ext);
            if($result !== true){
                $err[] = $result;
            }
    

    //-----------
    //  INSERT
    //-----------
    
        if(count($err) === 0){
        
            mysqli_autocommit($link, false);
        
            //入力した値をec_item_tableへINSERT
            if(insert_ec_item_table($link, $name, $price, $status, $filename, $date, $date) === true){
                $item_id = mysqli_insert_id($link);
            
                //個数をec_stock_tableへINSERT
                if(insert_ec_stock_table($link, $item_id, $stock, $date, $date) === true){
                    $msg[] = '追加完了';
                    mysqli_commit($link);
                } else {
                    $msg[] = 'ec_stock_tableへ追加失敗';
                    mysqli_rollbaack($link);
                }
            } else {
                $msg[] = 'ec_item_tableへ追加失敗';
            }
        }
    }
    
    
//--------------------
//個数変更があったとき
//--------------------

    if($mode === 'update_stock'){
        
        //値を取得
        $item_id = get_post_data_int('item_id');
        $stock_chg = get_post_data('stock_chg');
        $date = date('Y-m-d H:i:s');
        
        //エラーチェック
        $result = err_check($stock_chg, '個数');
        if($result !== true){
            $err[] = $result;
        }
        
        //アップデート
        if(count($err) === 0){
            if(update_ec_stock_table($link, $stock_chg, $date, $item_id) === true){
                $msg[] = '個数変更完了';
            } else {
                $msg[] = '個数更新失敗';
            }
        }
    }


//--------------------------
//ステータス変更があったとき
//--------------------------
    
    if($mode === 'update_status'){
        
        //値を取得
        $item_id = get_post_data_int('item_id');
        $status = get_post_data_int('status');
        $date = date('Y-m-d H:i:s');
        
        //エラーチェック
        $result = err_check_status($status);
        if($result !== true){
            $err[] = $result;
        }
        
        //アップデート
        if(count($err) === 0){
            
            if(update_ec_item_table($link, $status, $date, $item_id) === true){
                $msg[] = 'ステータス変更完了';
            } else {
                $msg[] = 'ステータス変更失敗';
            }
        }
    }
    

//----------------------
//削除ボタンが押されたら
//----------------------

    if($mode === 'delete'){
        
        //値を取得
        $item_id = get_post_data_int('item_id');
        
        //DELETE
        if(delete_list($link, $item_id) === true){
            $msg[] = '商品が削除されました';
        } else {
            $msg[] = '商品削除失敗';
        }
    }
    
}


//DB情報を取得
$item_data = get_ec_item_table($link);


//----------------
//ページネーション
//----------------

define('MAX', '10');

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
include_once '../include/view/insert.php';