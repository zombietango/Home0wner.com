<?php
class Database{
 
    // specify your own database credentials
    private $host = "mysql";
    private $db_name = "home0wner_com";
    private $username = "home0wner.com";
    private $password = "Home0wner.com-is-vulnerable";
    public $conn;
 
    // get the database connection
    public function connect(){
        try {
            $con = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
            $this->myconn = $con;
        } catch(mysqli_sql_exception $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->myconn;
    }

    public function close() {
        mysqli_close($con);
        echo 'Connection closed!';
    }
}
?>