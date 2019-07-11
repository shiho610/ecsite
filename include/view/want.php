<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ほしいものリスト</title>
        
        <!-- CSS -->
        <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
        <link rel="stylesheet" href="./css/shopping.css">
        <link rel="stylesheet" href="./css/responsive.css">
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="page_header wrapper">
                <div class="logo">
                    <a href="./item_list.php">NANDEMO-YA</a>
                </div><!-- /.logo -->
                
                <nav>
                    <ul class="main_nav">
                        <li><?php echo $user_name; ?>さん</li>
                        <li class="heart"><a href="./want.php"><i class="fas fa-heart"></i></a></li>
                        <li class="cart"><a href="./cart.php"><i class="fas fa-shopping-cart"></i></a></li>
                        <li class="logout"><a href="./logout.php">ログアウト</a></li>
                    </ul>
                </nav>
            </div><!-- /.page_header -->
        </header>
        
        <section class="cart_list wrapper">
            <h1><i class="fas fa-heart heart"></i> ほしいものリスト</h1>
            
        
        <!--エラーメッセージまたは登録完了メッセージ-->
        <div class="message">    
            <?php foreach($err as $value){ ?>
                <p class><?php echo $value; ?></p>
            <?php } ?>
            <?php foreach($msg as $value){ ?>
                <p class><?php echo $value; ?></p>
            <?php } ?>        
        </div><!-- /.message -->
        
            <!--ほしいものリストテーブル-->
            
            <table>
                <th></th>
                <th></th>
                <th>価格</th>
                <th></th>
                
                <?php foreach($item_data as $value){ ?>
                <tr>
                    <td class="img"><img src="./img/<?php echo $value['img']; ?>"></td>
                    <td><?php echo $value['name']; ?></td>
                    
                    <td class="red">¥<?php echo ceil($value['price']); ?>(税込)</td>
                    
                    <td>
                        <!--削除ボタン-->
                        
                        <form method="post">
                            <input type="submit" name="delete" class="delete_btn button" value="削除">
                            <input type="hidden" name="want_id" value="<?php echo $value['want_id']; ?>">
                        </form>
                        
                        <!--カートに入れるボタン-->
                        
                        <form method="post">
                            <input type="submit" name="cart_btn" class="cart_btn button" value="カートに入れる">
                            <input type="hidden" name="item_id" value="<?php echo $value['item_id']; ?>">
                        </form>
                    </td>
                </tr>
                <?php } ?>
                
                <!--合計表示-->
                <tr class="bottom">
                    <td></td>
                    <td>合計</td>
                    <td class="sum">¥<?php echo ceil($sum); ?>(税込)</td>
                    <td></td>
                </tr>
            </table>
            
        </section><!-- /.cart_list -->
    </body>
</html>