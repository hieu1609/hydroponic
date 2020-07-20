<?php
header("Content-type: text/html; charset=utf-8");
<<<<<<< HEAD
class Database
{

=======
class Database {
 
>>>>>>> 8a9e945c6de9dd5ab48a331996c7665fb807757a
    public $host = "localhost";
    public $user = "root";
    public $pass = "";
    public $dbname = "raspberry";

    public $link;
    public $error;

    public function __construct()
    {
        $this->connectDB();
    }
<<<<<<< HEAD

    private function connectDB()
    {
        $this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        mysqli_set_charset($this->link, 'UTF8');
        if (!$this->link) {
            $this->error = "connection fail" . $this->link->connect_error;
=======
 
    private function connectDB(){
        $this->link = new mysqli($this->host,$this->user, $this->pass, $this->dbname);
        mysqli_set_charset($this->link, 'UTF8');
        if(!$this->link) {
            $this->error ="connection fail".$this->link->connect_error;
>>>>>>> 8a9e945c6de9dd5ab48a331996c7665fb807757a
            return false;
        }
    }

    // Select or Read Data From Database 
    public function select($query)
    {
        $result = $this->link->query($query) or die($this->link->error . __LINE__);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Create Data into the database 
    public function insert($query)
    {
        $insert_row = $this->link->query($query) or die($this->link->error . __LINE__);
        if ($insert_row) {
        } else {
            die("Error: (" . $this->link->erron . ")" . $this->link->error);
        }
    }

    /// Update Data into the database
    public function update($query)
    {
        $update_row = $this->link->query($query) or die($this->link->error . __LINE__);
        if ($update_row) {
        } else {
            die("Error: (" . $this->link->erron . ")" . $this->link->error);
        }
    }

    /// Delete Data into the database

    public function delete($query)
    {
        $delete_row = $this->link->query($query) or die($this->link->error . __LINE__);
        if ($delete_row) {
            header("Location: index.php?msg=" . urlencode('Data Delete Successfully'));
            exit();
        } else {
            die("Error: (" . $this->link->erron . ")" . $this->link->error);
        }
    }
}
