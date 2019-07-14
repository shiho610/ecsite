<?php

//-----------------------
//   　　 POST
//-----------------------

//-----------------------
//リクエストメソッド取得

function get_request_method(){
    return $_SERVER['REQUEST_METHOD'];
}

//セッション変数からuser_name取得

function get_user_name($user_name){
    
    if(isset($_SESSION['user_name']) === true){
        return $_SESSION['user_name'];
        
    } else {
        //非ログインの時はログインページへ
        header ('Location: ./top.php');
    }
}

//---------
//POST取得

function get_post_data($value){
    $str = '';
    
    if(isset($_POST[$value]) === true){
        $str = preg_replace('/\A[\x00\s]++|[\x00\s]++\z/u', '', $_POST[$value]);
    }
    return $str;
}

//------------
//POST整数取得

function get_post_data_int($value){
    $str = '';
    
    if(isset($_POST[$value]) === true){
        $str = (int)$_POST[$value];
    }
    return $str;
}


//---------------
//エラーチェック

//名前、値段、個数
function err_check($value, $item){
        
    if(mb_strlen($value) === 0){
        return $item . 'を入力してください';
        
    } else if(!preg_match('/^([1-9][0-9]*|0)$/', $value) && ($item === '値段' || $item === '個数')){
        return $item . 'は半角数字で入力してください';
        
    } else if(!preg_match('/^([1-9][0-9]*)$/', $value) && $item === '数量'){
        return $item . 'は1以上の半角数字で入力してください';
    }
    return true;
}

//ステータス
function err_check_status($status){
    
    if(!preg_match('/[0-1]/', $status)){
        return '公開、非公開を選択してください';
    } 
    return true;
}

//画像
function err_check_img($tempfile, $filename, $ext){
    
    if(is_uploaded_file($tempfile)){
        if($ext !== '.jpeg' && $ext !== '.jpg' && $ext !== '.png'){
            return 'ファイル形式はJPEG、PNGのみ可能です';
                
        //$tempfileの一時的なファイル名を'./img/'.$filenameにしてアップする
        } else if(move_uploaded_file($tempfile, './img/'.$filename) === false){
            return 'ファイルがアップロードできませんでした';
        }
    } else {
        return 'ファイルが選択されていません';
    }
    return true;
}

//ユーザーID、パスワード
function err_check_login($value, $item){

    if(mb_strlen($value) < 6){
        return $item . 'は6文字以上で入力してください';
        
    } else if(!preg_match('/^[a-zA-Z0-9]+$/', $value)){
        return $item . 'は半角英数字で入力してください';
        
    }
    return true;
}



//---------------------
//HTMLエンティティ変換

function entity_str($str){
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

function entity_assoc_array($assoc_array){
    
    foreach($assoc_array as $key => $value){
        foreach($value as $keys => $values){
            //HTMLエンティティへ変換
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    return $assoc_array;
}



//------------------------
//   　　　DB
//------------------------


//---------------
//DBハンドル取得

function get_db_connect(){
    
    //コネクション開始
    if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)){
        die('error: ' . mysqli_connect_error());
    }
    //文字コード
    mysqli_set_charset($link, DB_CHARACTER_SET);
    return $link;
}


//------------------
//DBコネクション切断

function close_db_connect($link){
    mysqli_close($link);
}


//-----------------------------
//クエリ実行、結果を配列で取得

function get_as_array($link, $sql){
    
    $data = array();
    
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                $data[] = $row;
            }
        }
        //セット解放
        mysqli_free_result($result);
    }
    return $data;
}

//----------------------------------------------------
//WHERE指定した時のクエリ実行、1行だけ結果取得する

function get_id_as_array($link, $sql){
     
    $data = array();
    
    if($result = mysqli_query($link, $sql)){
        $row = mysqli_fetch_assoc($result);
        $data[] = $row;
        
        //セット解放
        mysqli_free_result($result);
    }
    
    return $data;
}



//＊＊＊＊＊＊＊＊＊＊＊
//　　商品管理ページ
//＊＊＊＊＊＊＊＊＊＊＊


//---------------------------------------------
//ec_item_table,ec_stock_tableから情報取得

function get_ec_item_table($link){
    
    $sql = 'SELECT eit.item_id, name, price, stock, status, img FROM ec_item_table AS eit JOIN ec_stock_table AS est ON eit.item_id = est.item_id';
    return get_as_array($link, $sql);
}



//-----------
//  INSERT
//-----------


//----------------------------------
//INSERT UPDATE結果

function db_result($link, $sql){
    
    if(mysqli_query($link, $sql) === true){
        return true;
    } else {
        return false;
    }
}


//-----------------------------
//ec_stock_tableへINSERT

function insert_ec_stock_table($link, $item_id, $stock, $date){
    
    $sql = "INSERT INTO ec_stock_table(item_id, stock, create_at, update_at) VALUES ('$item_id', '$stock', '$date', '$date')";
    return db_result($link, $sql);
}

//--------------------
//ec_item_tableへINSERT

function insert_ec_item_table($link, $name, $price, $status, $filename, $date){
    
    $sql = "INSERT INTO ec_item_table(name, price, status, img, create_at, update_at) VALUES ('$name', '$price', '$status', '$filename', '$date', '$date')";
    return db_result($link, $sql);
}



//-------------
//   UPDATE
//-------------

//---------
//個数変更

function update_ec_stock_table($link, $stock_chg, $date, $item_id){
    
    $sql = 'UPDATE ec_stock_table SET stock = ' . $stock_chg . ', update_at = \'' . $date . '\' WHERE item_id = ' . $item_id;
    return db_result($link, $sql);
}

//---------------
//ステータス変更

function update_ec_item_table($link, $status, $date, $item_id){
    
    if($status === 0){
        $sql = 'UPDATE ec_item_table SET status = 1, update_at = \'' . $date . '\' WHERE item_id = ' .$item_id;
    } else {
        $sql = 'UPDATE ec_item_table SET status = 0, update_at = \'' . $date . '\' WHERE item_id = ' .$item_id;
    }
    return db_result($link, $sql);
}

//------------------
//削除ボタン(DELETE)

function delete_list($link, $item_id){
    
    $sql = 'DELETE eit, est FROM ec_item_table AS eit JOIN ec_stock_table AS est ON eit.item_id = est.item_id WHERE eit.item_id = ' .$item_id;
    return db_result($link, $sql);
}



//＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
//　　　ユーザー管理ページ
//＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊

//ユーザー情報取得

function get_ec_user_table($link){
    
    $sql = 'SELECT user_name, create_at FROM ec_user_table';
    return get_as_array($link, $sql);
}



//＊＊＊＊＊＊＊＊＊＊＊＊＊＊
//　　　商品一覧ページ
//＊＊＊＊＊＊＊＊＊＊＊＊＊＊

//ステータスが公開のものだけ取得

function get_ec_table_status($link){
    
    $sql = 'SELECT eit.item_id, name, price * 1.08 AS price, img, status, stock FROM ec_item_table AS eit JOIN ec_stock_table AS est ON eit.item_id = est.item_id WHERE status = 1';
    return get_as_array($link, $sql);
}

//ec_user_tableのuser_id取得

function get_user_id($link, $user_name){
    
    $sql = 'SELECT user_id FROM ec_user_table WHERE user_name = \''. $user_name . '\'';
    return get_id_as_array($link, $sql);
}

//ec_cart_tableに同じものをカートに入れているか調べる
function get_cart_table($link, $user_id, $item_id){
    
    $sql = 'SELECT user_id FROM ec_cart_table WHERE user_id = ' . $user_id . ' AND item_id = ' . $item_id;
    return get_id_as_array($link, $sql);
}

//同じもの買った時のカートUPDATE

function update_ec_cart_table($link, $user_id, $item_id, $date){
    
    $sql = 'UPDATE ec_cart_table SET amount = amount+1, update_at = \'' . $date . '\' WHERE user_id = ' . $user_id . ' AND item_id = ' . $item_id;
    return db_result($link, $sql);
}

//重複なければcart_tableへINSERT

function insert_ec_cart_table($link, $user_id, $item_id, $date){

    $sql = "INSERT INTO ec_cart_table(user_id, item_id, amount, create_at, update_at) VALUES ('$user_id', '$item_id', 1, '$date', '$date')";
    return db_result($link, $sql);
}


//ec_cart_tableに同じものをカートに入れているか調べる
function get_want_table($link, $user_id, $item_id){
    
    $sql = 'SELECT user_id FROM ec_want_table WHERE user_id = ' . $user_id . ' AND item_id = ' . $item_id;
    return get_id_as_array($link, $sql);
}

//ほしい物リスト登録時　want_tableへINSERT

function insert_ec_want_table($link, $user_id, $item_id, $date){
    
    $sql = "INSERT INTO ec_want_table(user_id, item_id, create_at, update_at) VALUES ('$user_id', '$item_id', '$date', '$date')";
    return db_result($link, $sql);    
}

//＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
//　　ほしいものリストのページ
//＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊

//ec_want_tableの情報一覧取得

function get_ec_want_list($link, $user_id){
    
    $sql = 'SELECT img, name, price * 1.08 AS price, want_id, eit.item_id , stock, status FROM ec_item_table AS eit JOIN ec_want_table AS ewt ON eit.item_id = ewt.item_id JOIN ec_stock_table AS est ON ewt.item_id = est.item_id WHERE user_id = \'' . $user_id . '\'';
    return get_as_array($link, $sql);
}

//削除ボタン(DELETE)

function delete_want_list($link, $want_id){
    
    $sql = 'DELETE FROM ec_want_table WHERE want_id = ' . $want_id;
    return db_result($link, $sql);

}


//合計の計算

function want_sum($link, $user_id){
    
    $sql = 'SELECT SUM(eit.price) * 1.08 AS sum FROM ec_item_table AS eit JOIN ec_want_table AS ewt ON eit.item_id = ewt.item_id WHERE user_id = ' . $user_id;
    return get_id_as_array($link, $sql);
}

//＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
//　　ショッピングカートページ
//＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊

//ec_cart_tableの情報一覧取得

function get_ec_cart_list($link, $user_id){
    
    $sql = 'SELECT img, name, (price * amount) * 1.08 AS price, amount, cart_id, eit.item_id , stock, status FROM ec_item_table AS eit JOIN ec_cart_table AS ect ON eit.item_id = ect.item_id JOIN ec_stock_table AS est ON ect.item_id = est.item_id WHERE user_id = \'' . $user_id . '\'';
    return get_as_array($link, $sql);
}

//数量の変更があった時

function update_ec_cart_amount($link, $amount_chg, $date, $cart_id){
 
    $sql = 'UPDATE ec_cart_table SET amount = ' . $amount_chg . ', update_at = \'' . $date . '\' WHERE cart_id = ' . $cart_id;
    return db_result($link, $sql);
}


//削除ボタン(DELETE)

function delete_cart_list($link, $cart_id){
    
    $sql = 'DELETE FROM ec_cart_table WHERE cart_id = ' . $cart_id;
    return db_result($link, $sql);
}

//合計の計算

function cart_sum($link, $user_id){
    
    $sql = 'SELECT SUM(eit.price * ect.amount) * 1.08 AS sum FROM ec_item_table AS eit JOIN ec_cart_table AS ect ON eit.item_id = ect.item_id WHERE user_id = ' . $user_id;
    return get_id_as_array($link, $sql);
}



//＊＊＊＊＊＊＊＊＊＊＊＊＊
//　　　購入完了ページ
//＊＊＊＊＊＊＊＊＊＊＊＊＊

//購入するボタンが押されたらec_stock_tableのstockを減らす

function chg_ec_stock($link, $item_id, $amount, $date){
    
    $sql ='UPDATE ec_stock_table SET stock = stock- ' . $amount . ', update_at = \'' . $date . '\' WHERE item_id = ' . $item_id;
    return db_result($link, $sql);
}

//購入するボタンが押されたらec_cart_tableの履歴を削除

function delete_cart_want_table($link, $user_id){
    
    $sql = 'DELETE ect, ewt FROM ec_cart_table AS ect JOIN ec_want_table AS ewt ON ect.item_id = ewt.item_id WHERE ect.user_id = ' . $user_id;
    return db_result($link, $sql);
}



//＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
//　　　ユーザー登録ページ
//＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊

//ユーザーIDとパスワードをec_user_tableへINSERT

function insert_ec_user_table($link, $user_name, $password, $date){
    
    $sql = "INSERT INTO ec_user_table(user_name, password, create_at, update_at) VALUES ('$user_name', '$password', '$date', '$date')";
    return db_result($link, $sql);
}

//ユーザーIDとパスワードを取得する（エラーチェックもここで）

function get_ec_user_table_login($link, $user_name){
    
    $sql = 'SELECT user_name FROM ec_user_table WHERE user_name = \''. $user_name . '\'';
    return get_id_as_array($link, $sql);
}




//＊＊＊＊＊＊＊＊＊＊＊＊＊＊
//　　　ログインページ
//＊＊＊＊＊＊＊＊＊＊＊＊＊＊

function admin_check($user_name, $password){

    if($user_name === 'admin' && $password === 'admin'){
        return true;
    } else {
        return 'ユーザーIDとパスワードが一致しません';
    }
}

//cooieにuser_nameあった時DBからパスワード取得

function get_ec_user_table_top_login($link, $user_name){
    
    $sql = 'SELECT user_name, password FROM ec_user_table WHERE user_name = \''. $user_name . '\'';
    return get_id_as_array($link, $sql);
}

//登録済みユーザーに動きがあったらec_user_tableのupdate_atを更新

function update_ec_user_table($link, $user_name, $date){
    
    $sql = 'UPDATE ec_user_table SET update_at = \'' . $date . '\' WHERE user_name = \''. $user_name . '\'';
    return db_result($link, $sql);
}

