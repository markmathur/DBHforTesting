<?php

namespace DBhandler;

class SQLgenerator{

  private $dbh;

  function __construct(DBhandler $DBhandler)
  {
    $this->dbh = $DBhandler;
  }

  public function SQL_storePost() {
    return "INSERT INTO $this->dbh->table 
    ($this->dbh->stringOfColumns) VALUES ($this->dbh->stringOfValues);";
  }

  public function SQL_getPostWithId() {
    return "SELECT * FROM $this->dbh->table WHERE $this->dbh->incomingIdColumn = '$this->dbh->incomingIdValue';";
  }

  public function SQL_getAllPosts() {
    return "SELECT * FROM $this->dbh->table;";
  }

  public function SQL_deletePostWithId() {
    return "DELETE FROM $this->dbh->table WHERE $this->dbh->incomingIdColumn = $this->dbh->incomingIdValue;";
  }

  public function SQL_updatePost() {
    
    $sql = "UPDATE $this->dbh->table SET ";
    foreach($this->dbh->incomingUpdateDataAsArray as $col => $val) {
      $sql .= " $col = '$val', ";
    }
    $this->takeAwayTrailingComa($sql);
    $sql .= " WHERE $this->dbh->incomingIdColumn = $this->dbh->incomingIdValue;";
    return $sql;
  }

  public static function takeAwayTrailingComa(&$str) {
    $str = rtrim($str, ", ");

  }
}