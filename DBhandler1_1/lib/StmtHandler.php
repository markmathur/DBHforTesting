<?php

namespace DBhandler;

use mysqli_stmt;
use PhpParser\Node\Stmt;

class StmtHandler {

  private $dbh;

  function __construct(DBhandler $dBhandler)
  {
    $this->dbh = $dBhandler;
  }

  // *** MAIN METHODS ***

  public function getPostWithId($dbConn) {
    
    $stmt = $dbConn->prepare("SELECT * FROM {$this->dbh->getTable()} WHERE {$this->dbh->getIncomingCritColumn()} = ?");
    $id='';
    $stmt->bind_param("s", $id);
    $id = $this->dbh->getIncomingCritValue();
    $stmt->execute();
    $postAsArray = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $dbConn->close();

    return $postAsArray;
  }

  public function deletePostWithId($dbConn) {
    
    $stmt = $dbConn->prepare("DELETE FROM {$this->dbh->getTable()} WHERE {$this->dbh->getIncomingCritColumn()} = ?");
    $id='';
    $stmt->bind_param("s", $id);
    $id = $this->dbh->getIncomingCritValue();
    // Should we call gePostWithId here to confirm?
    $success = $stmt->execute();
    $stmt->close();
    $dbConn->close();

    return $success;
  }


  public function getPostsByCriteria($dbConn) {
    

    $stmt = $dbConn->prepare("SELECT * FROM {$this->dbh->getTable()} WHERE {$this->dbh->getIncomingCritColumn()} = ?");
    $crit = '';
    $stmt->bind_param("s", $crit);
    $crit = $this->dbh->getIncomingCritValue();
    $stmt->execute();
    $postAsArray = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $dbConn->close();

    return $postAsArray;
  }

  public function storePost(\mysqli $dbConn, array $postData) {

    $preparedStatement = $this->makePreparedStatementForStorePost($postData);
    $stmt = $dbConn->prepare($preparedStatement);
    $this->bindParameters($stmt, $postData);

    $success = $stmt->execute();
    $stmt->close();
    $dbConn->close();

    return $success;
  }

  public function updatePost(\mysqli $dbConn, array $postData) {

    $preparedStatement = $this->makePreparedStatementForUpdatePost($postData);
    $stmt = $dbConn->prepare($preparedStatement);
    $arrOfParameters = $this->makeArrayOfParametersForUpdatePost($postData);
    $this->bindParameters($stmt, $arrOfParameters);
    $success = $stmt->execute();
    $stmt->close();
    $dbConn->close();

    return $success;
  }



  // *** END MAIN METHODS ***


  // *** SUPPORTING METHODS
  
  public function makePreparedStatementForStorePost(array $postData) {
    $rowOfQmarks = $this->getStringOfQmarks(sizeof($postData));
    $str = "INSERT INTO {$this->dbh->getTable()} ({$this->dbh->getStringOfColumns()}) VALUES ($rowOfQmarks);";

    return $str;
  }

  public function makePreparedStatementForUpdatePost(array $postData) {
    $colValString = $this->makeColValString($postData);
    $str = "UPDATE {$this->dbh->getTable()} SET {$colValString} WHERE {$this->dbh->getIncomingCritColumn()} = ?;";

    return $str;
  }

  private function bindParameters(mysqli_stmt $stmt, array $postData) {
    $strOfTypes = $this->getStrOfTypeInitials($postData); // Like "ssis"
    $listOfVals = array_values($postData);
    $stmt->bind_param($strOfTypes, ...$listOfVals);
  }

  private function getStringOfQmarks(int $numberOfValues) {

    if($numberOfValues < 1)
      throw new \Exception('Argument must not be < 1.');
    
    $rowOfQmarks = str_repeat('?, ', $numberOfValues);
    $this->takeAwayTrailingComa($rowOfQmarks);
    
    return $rowOfQmarks;
  }

  private function getStrOfTypeInitials($postData) {
    $str = '';

    foreach($postData as $col => $val) {
      $str .= substr(gettype($val), 0, 1);
    }

    return $str;
  }

  public function makeArrayOfParametersForUpdatePost($postData) {
    $arr = $postData;
    $arr[$this->dbh->getIncomingCritColumn()] = $this->dbh->getIncomingCritValue();
    return $arr;
  }

  private function makeColValString($postData) {
    $str = '';

    foreach($postData as $col => $val) {
      $str .= "$col = ?, ";
    }

    $this->takeAwayTrailingComa($str);

    return $str;
  }

  private static function takeAwayTrailingComa(&$str) {
    $str = rtrim($str, ", ");
  }

}

class ValueWithType {
  private string $type;
  private $value; //  Can be of äny type.
  
  public function __construct($value)
  {
    $this->value = $value;
    $this->type =substr(gettype($value), 0, 1);
  }

  public function getValue() {
    return $this->value;
  }

  public function getType() {
    return $this->type;
  }

} 