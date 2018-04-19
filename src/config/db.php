<?php
  class db{
    // Properties
    // private $dbhost = 'localhost';
    // private $dbuser = 'root';
    // private $dbpass = '';
    // private $dbname = 'pslphonebook';

    private $dbhost = 'eu-cdbr-west-02.cleardb.net';
    private $dbuser = 'b43b6bfcd1021b';
    private $dbpass = '4216e2b5';
    private $dbname = 'heroku_07ded48d706a095';

    // Connect
    public function connect(){
      $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname";
      $db_connection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
      $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $db_connection;
    }
  }
 ?>
