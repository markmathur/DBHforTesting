<?php

namespace DBhandler;

class StmtHandler {

  private $dbh;

  function __construct(DBhandler $dBhandler)
  {
    $this->dbh = $dBhandler;
  }

  public function getPostWithId($dbConn) {
    // $id='';
    $stmt = $dbConn->prepare("SELECT * FROM {$this->dbh->getTable()} WHERE {$this->dbh->getIncomingIdColumn()} = ?");
    $stmt->bind_param("s", $id);
    $id = $this->dbh->getIncomingIdValue();
    $stmt->execute();
    $postAsArray = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $dbConn->close();
    return $postAsArray;
  }

  public function storePost(\mysqli $dbConn, array $postData) {

    $preparedStatement = $this->makePreparedStatementForStorePost($postData);
    $stmt = $dbConn->prepare($preparedStatement);
    
    
    

  }

  public function getTypesOfValues() {

  }

  // public function bindParamametersFromArray($stmt, array $post) {
    
    
  //   $stmt->bind_param()
  // }

  public function makePreparedStatementForStorePost(array $postData) {

    $rowOfQmarks = $this->getStringOfQmarks(sizeof($postData));
    $str = "INSERT INTO {$this->dbh->getTable()} ({$this->dbh->getStringOfColumns()}) VALUES ($rowOfQmarks);";

    return $str;
  }

  public function getStringOfQmarks(int $numberOfValues) {

    if($numberOfValues < 1)
      throw new \Exception('Argument must not be < 1.');
    
    $rowOfQmarks = str_repeat('?, ', $numberOfValues);
    $this->takeAwayTrailingComa($rowOfQmarks);
    
    return $rowOfQmarks;
  }

  public static function takeAwayTrailingComa(&$str) {
    $str = rtrim($str, ", ");
  }

}

class ValueWithType {
  private string $type;
  private $value; //  Can be of äny type.
  
  public function __construct($value)
  {
    $this->value = $value;
    $this->type = gettype($value);
  }

  public function getValue() {
    return $this->value;
  }

  public function getType() {
    return $this->type;
  }

} 