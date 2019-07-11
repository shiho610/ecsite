<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ショッピングカートページ</title>
        
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
        
        
        <!--カートに入れたもの一覧-->
        
        <section class="cart_list wrapper">
            <h1><i class="fas fa-shopping-cart cart"></i> ショッピングカート</h1>
        
        
        <!--エラーメッセージまたは変更完了メッセージ-->
        
        <div class="message">   
            <?php foreach($err as $value){ ?>
                <p class><?php echo $value; ?></p>
            <?php } ?>
            <?php foreach($msg as $value){ ?>
                <p class><?php echo $value; ?></p>
            <?php } ?>        
        </div><!-- /.message -->
        
        <!--カートに入れたもの一覧、テーブル-->
            
            <table>
                <th></th>
                <th></th>
                <th>数量</th>
                <th>価格</th>
                <th></th>
                
                <?php foreach($item_data as $value){ ?>
                <tr>
                    <td class="img"><img src="./img/<?php echo $value['img']; ?>"></td>
                    <td><?php echo $value['name']; ?></td>
                    
                    
                    <!--個数変更-->
                    
                    <form method="post">
                        <td><p><input type="text" name="amount_chg" class="amount_chg" value="<?php echo $value['amount']; ?>"> 個</p>
                        <input type="submit" class="amount_chg_btn" value="変更"></td>
                        <input type="hidden" name="cart_id" value="<?php echo $value['cart_id']; ?>">
                        <input type="hidden" name="item_id" value="<?php echo $value['item_id']; ?>">
                    </form>
                    
                    
                    <!--税込表示-->
                    
                    <td class="red price">¥<?php echo ceil($value['price']); ?>(税込)</td>
                    
                    
                    <!--削除ボタン-->
                    
                    <form method="post">
                        <td><input type="submit" name="delete" class="delete_btn button" value="削除"></td>
                        <input type="hidden" name="cart_id" value="<?php echo $value['cart_id']; ?>">
                    </form>                    
                </tr>
                <?php } ?>
                
                <tr class="bottom">
                    <!--合計-->
                    
                    <td></td>
                    <td></td>
                    <td>合計</td>
                    <td class="sum"><p>¥<?php echo ceil($sum); ?>(税込)</td>
                    <td>
                        
                        <!--購入ボタン-->
                        
                        <form action="./confirm.php" method="post">
                            <input type="submit" name="buy" class="buy_btn button" value="購入する">
                        </form>
                    </td>
                </tr>
            </table>
            
        </section><!-- /.cart_list -->
    </body>
</html>