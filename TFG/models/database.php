<?php

class Database {

    protected $connection;

    public function __construct() {
        $aux = mysql_connect("localhost", "mydbuser", "mydbpass");
        if (!$aux) {
            die('Cannot connect to database:' . mysql_error());
        }
        mysql_select_db("mydb", $aux);
        mysql_set_charset('utf8');
        $this->connection = $aux;
    }

    public function getConnection() {
        return $this->connection;
    }
	
	public function endConnection(){
		mysql_close($this->connection);
	}

}

?>
