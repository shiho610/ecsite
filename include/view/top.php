<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ログイン</title>
        
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
                
                <p>ENJOY SHOPPING!</p>
            </div><!-- /.page_header -->
        </header>
        
        <section class="login">
            
        <!--エラーメッセージ-->

        <?php if($err !== ''){ ?>
            <p class="red"><?php echo $err; ?></p>
        <?php } ?>
        
            <!--入力フォーム-->
        
            <form method="post" action="./top_login.php">
                <p>ユーザーID：<input type="text" name="user_name" class="login_form" value="<?php echo $user_name; ?>"></p>
                <p>パスワード：<input type="password" name="password" class="login_form" value="<?php echo $password; ?>"></p>
                <input type="submit" class="login_btn button" value="ログイン">
            </form>
            
            <!--新規会員の方は登録ページへ案内-->
            
            <div class="login_info">
                <p>
                    NANDEMO-YAは会員様のみご利用頂けます<br>
                    未登録の方は<a href="create_id.php">こちら</a>よりご登録ください
                </p>
            </div>            
        
        </section><!-- /.login -->
    </body>
</html>