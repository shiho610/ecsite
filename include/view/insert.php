<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>商品管理ページ</title>
        
        <!-- CSS -->
        <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
        <link rel="stylesheet" href="./css/shopping.css">
        <link rel="stylesheet" href="./css/responsive.css">
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="page_header wrapper">
                <div class="logo"><a href="./item_list.php">NANDEMO-YA</a></div>
                <p><a href="./logout.php">ログアウト</a></p>
            </div><!-- page_header -->
        </header>   
        
        
        <!--エラーメッセージの表示-->
        
        <section class="message wrapper">
        <?php foreach($err as $value){ ?>
            <p class="red"><?php echo $value; ?></p>
        <?php } ?>
        <?php foreach($msg as $value){ ?>
            <p class="red"><?php echo $value; ?></p>
        <?php } ?>            
        </section><!-- /.message -->


        <!--商品追加フォーム-->

        <section class="insert">
            <h2><i class="fas fa-pencil-alt"></i> 商品を追加する</h2>
            
            <form enctype="multipart/form-data" action="insert.php" method="post">
                <input type="hidden" name="mode" value="insert">
                
                <p>商品名：<input type="text" name="name" class="insert_form"></p>
                <p>値　段：<input type="text" name="price" class="insert_form"></p>
                <p>個　数：<input type="text" name="stock" class="insert_form"></p>
                <p>商品画像：<input type="file" name="img" value="ファイルを選択"></p>
                <p>ステータス：
                    <select name="status">
                        <option value="0">非公開</option>
                        <option selected value="1">公開</option>
                    </select>
                </p>
                
                <input type="submit" class="insert_btn button" value="商品を登録する">
            </form>
        </section><!-- /.insert_form -->
        
        
        <!--商品一覧-->
        
        <section class="insert_list">
        <h2><i class="fas fa-pencil-alt"></i> 商品情報を変更する</h2>
        
        <table>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>ステータス</th>
            <th>操作</th>
            
            <?php foreach($disp_data as $value){
                if((int)$value['status'] === 0){ ?>
            <tr class="background">
            <?php } else { ?>
            <tr>
            <?php } ?>
            
            
                <!--商品情報 / 画像 / 商品名 / 値段-->
            
                <td><img src="./img/<?php echo $value['img']; ?>"></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['price']; ?>円</td>
                
                
                <!--在庫数変更-->
                
                <form method="post">
                    <td><input type="text" name="stock_chg" class="amount_chg" value="<?php echo $value['stock']; ?>">個　<input type="submit" class="insert_chg" value="変更"></td>
                    <input type="hidden" name="item_id" value="<?php echo $value['item_id']; ?>">
                    <input type="hidden" name="mode" value="update_stock">
                </form>
                
                
                <!--公開、非公開設定の変更-->
                
                <form method="post">
                    <?php if((int)$value['status'] === 0){ ?>
                    <td><input type="submit" name="status_chg" class="insert_chg" value="非公開→公開"></td>
                    <?php } else { ?>
                    <td><input type="submit" name="status_chg" class="insert_chg" value="公開→非公開"></td>
                    <?php } ?>
                    <input type="hidden" name="item_id" value="<?php echo $value['item_id']; ?>">
                    <input type="hidden" name="status" value="<?php echo $value['status']; ?>">
                    <input type="hidden" name="mode" value="update_status">
                </form>
                
                
                <!--削除ボタン-->
                
                <form method="post">
                <td><input type="submit" name="delete" class="insert_chg" value="削除する"></td>
                    <input type="hidden" name="item_id" value="<?php echo $value['item_id']; ?>">
                    <input type="hidden" name="mode" value="delete">
                </form>
                
            </tr>
            <?php } ?>

        </table>
        </section><!-- /.insert_list -->
        
        
        <!--ページネーション-->
        
        <section class="page">
            <?php for($i = 1; $i <= $max_page; $i++){
                if($i == $now){ ?>
                    <span><?php echo $now; ?></span>　
                <?php } else { ?>
                    <a href="insert.php?page_id=<?php echo $i; ?>"><?php echo $i; ?></a>　
                <?php }
            } ?>
        </section><!-- /.page -->
    </body>
</html>