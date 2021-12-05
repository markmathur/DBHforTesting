<?php

namespace DBhandler;

class StmtHandler {

  private $dbh;

  function __construct(DBhandler $dBhandler)
  {
    $this->dbh = $dBhandler;
  }

  public function getPostWithId($dbConn) {
    $id='';
    $stmt = $dbConn->prepare("SELECT * FROM {$this->dbh->getTable()} WHERE {$this->dbh->getIncomingIdColumn()}  = ?");
    $stmt->bind_param("s", $id);
    $id = $this->dbh->getIncomingIdValue();
    $stmt->execute();
    $postAsArray = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $dbConn->close();
    return $postAsArray;
  }


}