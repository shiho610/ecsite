<?php

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

define('DB_HOST', $url['us-cdbr-iron-east-02.cleardb.net']);
define('DB_USER', $url['be7bc579bec5cb']);
define('DB_PASSWD', $url['52e10e23']);
define('DB_NAME', substr($url['heroku_bde924a0558fa07'], 1));
 
define('HTML_CHARACTER_SET', 'UTF-8');  // HTML文字エンコーディング
define('DB_CHARACTER_SET',   'UTF8');   // DB文字エンコーディング
