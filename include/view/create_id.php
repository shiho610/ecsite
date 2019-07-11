<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>アカウント新規作成</title>
        
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

        <?php foreach($err as $value){ ?>
            <p class="red"><?php echo $value; ?></p>    
        <?php } ?>
        
        <?php foreach($msg as $value){ ?>
            <p><?php echo $value; ?></p>    
        <?php } ?>
        
        
            <!--入力フォーム-->
            
            <form method="post" action="create_id.php">
                <p>ユーザーID：<input type="text" name="user_name" class="login_form"></p>
                <p>パスワード：<input type="password" name="password" class="login_form"></p>
                <input type="submit" class="login_btn button" value="アカウント登録">
            </form>
            
            
            <!--会員の場合ログインページへ案内-->

            <div class="login_info">
                <p>会員様は<a href="top.php">こちら</a>よりログインしてください</p>
            </div>
        </section><!-- /.login -->
    </body>
</html>