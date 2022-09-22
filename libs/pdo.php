<?php


// ! RailWay Param-----------------------------------------------------------
define('DB_HOST', getenv('MYSQLHOST'));
define('DB_PORT', getenv('MYSQLPORT'));
define('DB_USER', getenv('MYSQLUSER'));
define('DB_PASS', getenv('MYSQLPASSWORD'));
define('DB_NAME', getenv('MYSQLDATABASE'));

$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';port=' . DB_PORT;

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
} catch (Exception $e) {
    echo "exception message: " . $e->getMessage();
}


// ! Localhost ----------------------------------------
// $host = "localhost";
// $user = "root";
// $password = "";
// $dbname = "todo_app";

// $dsn = "mysql:host=$host;dbname=$dbname";
// try {
//     $pdo = new PDO($dsn, $user, $password);
// } catch (Exception $e) {
//     echo "exception message: " . $e->getMessage();
// }



// $dsn = "mysql:host=$host;dbname=$dbname";

// try {
//     $pdo = new PDO($dsn, $user, $password);
// } catch (Exception $e) {
//     echo " message error : " . $e->getMessage();
// }