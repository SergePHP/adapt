<?php

require_once "db.php";

class Weblog{
    protected $dbh;
    
    public function setDB($dbh) {
        $this->dbh = $dbh;
    }
    public function show_entry($entry_id) {
        $query = "SELECT * FROM lessons WHERE id = :1";
        $stmt = $this->dbh->prepare($query)->execute($entry_id);
        $entry = $stmt->fetch_row();
        return $entry;
    }
}
class Weblog_Std extends Weblog{
    protected $dbh;
    public function __construct() {
        $this->dbh = new Mysql_Weblog;
    }
}
class Mysql_Weblog extends DB_Mysql{
    protected $user = "root";
    protected $pass = "smishin";
    protected $dbhost = "localhost";
    protected $dbname = "web";
    public function __construct() { }
}
class DB_Mysql_Test extends DB_Mysql{
    protected $user = "root";
    protected $pass = "smishin";
    protected $dbhost = "localhost";
    protected $dbname = "web";
    public function __construct() { }
}

function fact($n){
    if($n == 0 || $n == 1){
        return $n;
    } else {
        return $n * fact($n - 1); 
    }
}
//echo fact(100);
//exit;

$query = "SELECT id, teacher FROM lessons";
$dbh = new DB_Mysql_Test;
$stmt = $dbh->prepare($query)->execute();
//$result = $stmt->fetch();
//while($result->next()){
//    echo $result->id." ".$result->teacher."<br>";
//}
foreach ($stmt as $var) {
    echo $var['id'].":".$var['teacher']."<br>";
//    print_r($var);
}
//$blog = new Weblog_Std;
//$blog2 = new Weblog;
//$blog2->setDB(new DB_Mysql("root", "smishin", "localhost", "web"));
//print_r($blog->show_entry(3));
//print_r($blog2->show_entry(2));




?>