<?php  
  
 class database{
  private $host = 'localhost';
  private $name = 'root';
  private $pass = ''; 
  private $db = 'it6_lms';
  public $conn;


  public function getConnection(){
    try{
      $this->conn = new PDO("mysql:host=" .$this->host.";dbname=".$this->db, $this->name, $this->pass);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
      echo "Connection failed: " . $e->getMessage();
    }
        return $this->conn;
  }
    

  
 }

?>