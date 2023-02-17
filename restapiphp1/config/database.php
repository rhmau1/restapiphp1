<?php 
class Database{
    // variabel private hanya dapat diakses di dalam kelas Database
    private $host = "localhost";
    //variabel $db_name diisi sesuai dengan nama database yang akan digunakan 
    private $db_name = "indonetsource1";
    private $username = "root";
    private $password = "";
    // variabel $conn dapat diakses diluar kelas database
    public $conn;

    public function koneksi(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=".$this->host. ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            // untuk memberi tahu apabila koneksi eror 
            echo "Connection error:" .$exception->getMessage();
        }
        return $this->conn;
    }
}
?> 