<?php

define('DB_HOST', 'us-cdbr-iron-east-02.cleardb.net');
define('DB_USER', 'be7bc579bec5cb');
define('DB_PASSWD', '52e10e23');
define('DB_NAME', 'heroku_bde924a0558fa07');


/*
$db = parse_url($_SERVER['CLEARDB_DATABASE_URL']);
$db['heroku_bde924a0558fa07'] = ltrim($db['path'], '/');
$dsn = "mysql:host={$db['us-cdbr-iron-east-02.cleardb.net']};dbname={$db['heroku_bde924a0558fa07']};charset=utf8";
$user = $db['be7bc579bec5cb'];
$password = $db['52e10e23'];
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY =>true,
  );
*/
 
define('HTML_CHARACTER_SET', 'UTF-8');  // HTML文字エンコーディング
define('DB_CHARACTER_SET',   'UTF8');   // DB文字エンコーディング
