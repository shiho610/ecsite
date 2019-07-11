<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>商品一覧ページ</title>
        
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


        <!--商品一覧-->
        
        <section class="wrapper">
            
            <!--処理が完了、またはエラーがあればメッセージ表示-->
            
            <div class="message">
                <?php foreach($msg as $value){ ?>
                <p><?php echo $value; ?></p>
                <?php } ?>
            </div><!-- /.message -->
            
            <!--商品一覧表示-->
            <div class="item_list">
                <?php foreach($disp_data as $value){ ?>
                <div class="item">
                    <img src="./img/<?php echo $value['img']; ?>">
                    <div class="item_info">
                        <p><?php echo $value['name']; ?></p>
                        <p>¥<?php echo ceil($value['price']); ?>(税込)</p>
                    </div><!-- /.item_info -->
                
                    <!--ほしい物リストボタン-->
                
                    <form method="post">
                        <input type="submit" class="button want_btn fas" name="want_btn" value="&#xf004; ほしい">
                        <input type="hidden" name="item_id" value="<?php echo $value['item_id']; ?>">
                    </form>
                
                <!--カートボタン / 在庫が０でなければカートに入る-->
                
                    <?php if((int)$value['stock'] === 0){ ?>
                    <p class="red">売り切れ</p>
                    <?php } else { ?>
                    <form method="post">
                        <input type="submit" class="button cart_btn" name="cart_btn" value="カートに入れる">
                        <input type="hidden" name="item_id" value="<?php echo $value['item_id']; ?>">
                    </form>
                    <?php } ?>
            
                </div><!-- /.item -->
                <?php } ?>
            </div><!-- /.item_list -->
        </section>
        
        
        <!--ページネーション-->
        
        <section class="page">
            <?php for($i = 1; $i <= $max_page; $i++){
                if($i == $now){ ?>
                    <span><?php echo $now; ?></span>　
                <?php } else { ?>
                    <a href="item_list.php?page_id=<?php echo $i; ?>"><?php echo $i; ?></a>　
                <?php }
            } ?>
        </section><!-- /.page -->
    </body>
</html>