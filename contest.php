<?php
class contest {
  //connection handle
  private $conn;
  //database name
  private $dbname;
  //user id
  private $userid;
  //user password
  private $password = "";
  
  private $time_start;
  private $time_end;
  private $execution_time;
  
  //do not modify this function!
  function __construct($dbname = "", $userid = "") {
    $this->dbname = $dbname;
    $this->userid = $userid;
  } 

  //do not modify this function!
  public function __destruct() {
    if(is_resource($this->conn)) {
      cubrid_disconnect($this->conn);
    }
  }

  //do not modify this function!
  private function connect() {
    $this->conn = cubrid_connect("localhost", 30000, $this->dbname, "public", $this->password);
  }  
  
  //do not modify this function!
  private function display_results() {
    $sql = "select most_duplicated_value, total_occurrences, execution_time from results where userid = '".$this->userid."'";
    $result = cubrid_query($sql);
    if (!$result) {
      die(cubrid_error());
    }

    while ($row = cubrid_fetch_assoc($result)) {
      echo "Most duplicated value: [".$row["most_duplicated_value"]."], having ".$row["total_occurrences"]." occurrences.".PHP_EOL;
      echo "Elapsed time: ".($row["execution_time"]/1000)." sec.".PHP_EOL;
    }
    cubrid_free_result($result);
  }  
  
  public function run() {
    $this->connect();
    if(!$this->conn) {
      echo "Error connecting to the database!".PHP_EOL;
      return false;
    }
    
    //set auto-commit OFF
    if(function_exists("cubrid_set_autocommit")) {
      cubrid_set_autocommit($this->conn, CUBRID_AUTOCOMMIT_FALSE);
    }
    
    //start timing
    $this->time_start = microtime(true);

    ///////////////////////////////////////////////////////////////////////////////////////////
    // Here below you will need to write the code to solve the contest problem
    ///////////////////////////////////////////////////////////////////////////////////////////


    
          
    ///////////////////////////////////////////////////////////////////////////////////////////
    // !!!do not modify the code from here below!!!
    ///////////////////////////////////////////////////////////////////////////////////////////
    
    //end timing
    $this->time_end = microtime(true);
    $this->execution_time = ($this->time_end - $this->time_start)*1000; //get microseconds
    
    // Update execution time
    $sqlUpdateExecutionTime = "update results set execution_time = ".$this->execution_time." where userid = '".$this->userid."'";
    $result = cubrid_execute($this->conn, $sqlUpdateExecutionTime);
    cubrid_close_request($result);
    cubrid_commit($this->conn);

    echo PHP_EOL;
    echo "===========================================================".PHP_EOL;
    echo "User Id: ".$this->userid.PHP_EOL;
    $this->display_results();
    echo "===========================================================".PHP_EOL;
    
    return true;
  }
}  
?>
